<?php

namespace App\Services;

use App\Models\Donation;
use App\Models\Sponsor;
use App\Models\SponsorEvent;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class SponsorRewardService
{
    private array $tiers = [
        ['slug' => 'seedling', 'label' => 'Seedling', 'min' => 100, 'icon' => '🌱'],
        ['slug' => 'sapling', 'label' => 'Sapling', 'min' => 500, 'icon' => '🌿'],
        ['slug' => 'oak', 'label' => 'Oak', 'min' => 2000, 'icon' => '🌳'],
        ['slug' => 'redwood', 'label' => 'Redwood', 'min' => 5000, 'icon' => '🌲'],
        ['slug' => 'forest-guardian', 'label' => 'Forest Guardian', 'min' => 10000, 'icon' => '🛡️'],
    ];

    public function getPoints(int $sponsorId, ?int $periodDays = null): int
    {
        $donations = Donation::confirmed()
            ->where('type', 'sponsor')
            ->where('sponsor_id', $sponsorId)
            ->when($periodDays, function ($q) use ($periodDays) {
                $q->where(function ($qq) use ($periodDays) {
                    $qq->whereNotNull('donated_at')->where('donated_at', '>=', Carbon::now()->subDays($periodDays))
                        ->orWhere(function ($q2) use ($periodDays) {
                            $q2->whereNull('donated_at')->where('created_at', '>=', Carbon::now()->subDays($periodDays));
                        });
                });
            })
            ->sum('amount');

        $sponsorships = SponsorEvent::where('sponsor_id', $sponsorId)
            ->where('status', 'active')
            ->when($periodDays, function ($q) use ($periodDays) {
                $q->where('created_at', '>=', Carbon::now()->subDays($periodDays));
            })
            ->sum('amount');

        // For now assume same currency baseline.
        $points = (float)$donations + (float)$sponsorships;
        return (int) round($points);
    }

    public function getTier(int $points): array
    {
        $current = ['slug' => 'none', 'label' => 'Supporter', 'min' => 0, 'icon' => '🤝'];
        foreach ($this->tiers as $tier) {
            if ($points >= $tier['min']) {
                $current = $tier;
            }
        }
        $next = null;
        foreach ($this->tiers as $tier) {
            if ($tier['min'] > $current['min']) {
                $next = $tier;
                break;
            }
        }
        return ['current' => $current, 'next' => $next];
    }

    public function getMilestones(int $sponsorId): array
    {
        $confirmedDonationsCount = Donation::confirmed()->where('type', 'sponsor')->where('sponsor_id', $sponsorId)->count();
        $eventsSupported = SponsorEvent::where('sponsor_id', $sponsorId)->where('status', 'active')->distinct('event_id')->count('event_id');

        // Campaigns via events
        $campaignsSupported = SponsorEvent::where('sponsor_id', $sponsorId)
            ->where('status', 'active')
            ->whereHas('event', function ($q) {
                $q->whereNotNull('campaign_id');
            })
            ->with('event:campaign_id,id')
            ->get()
            ->pluck('event.campaign_id')
            ->filter()
            ->unique()
            ->count();

        // Consistency: donations in 3 consecutive months
        $monthly = Donation::confirmed()->where('type', 'sponsor')->where('sponsor_id', $sponsorId)
            ->selectRaw('DATE_FORMAT(COALESCE(donated_at, created_at), "%Y-%m") as ym, COUNT(*) as c')
            ->groupBy('ym')->orderBy('ym', 'desc')->pluck('c', 'ym');
        $consecutive = $this->hasConsecutiveMonths($monthly, 3);

        $milestones = [];
        if ($confirmedDonationsCount >= 1) $milestones[] = ['slug' => 'first-donation', 'label' => 'First Donation', 'icon' => '🎉'];
        if ($eventsSupported >= 5) $milestones[] = ['slug' => 'event-patron', 'label' => 'Event Patron', 'icon' => '🤝'];
        if ($campaignsSupported >= 3) $milestones[] = ['slug' => 'campaign-champion', 'label' => 'Campaign Champion', 'icon' => '🏆'];
        if ($consecutive) $milestones[] = ['slug' => 'consistent-supporter', 'label' => 'Consistent Supporter', 'icon' => '🗓️'];

        // Major gift (single donation >= 1000)
        $major = Donation::confirmed()->where('type', 'sponsor')->where('sponsor_id', $sponsorId)->where('amount', '>=', 1000)->exists();
        if ($major) $milestones[] = ['slug' => 'major-gift', 'label' => 'Major Gift', 'icon' => '💎'];

        return $milestones;
    }

    private function hasConsecutiveMonths(Collection $monthlyCounts, int $needed): bool
    {
        if ($monthlyCounts->isEmpty()) return false;
        $months = $monthlyCounts->keys()->map(fn($ym) => Carbon::createFromFormat('Y-m', $ym))->sort()->values();
        $streak = 1; // each month present counts; check consecutive sequence
        for ($i = 1; $i < $months->count(); $i++) {
            $prev = $months[$i - 1];
            $cur = $months[$i];
            if ($prev->copy()->addMonth()->isSameDay($cur)) {
                $streak++;
                if ($streak >= $needed) return true;
            } else {
                $streak = 1;
            }
        }
        return $needed <= 1 ? true : false;
    }

    public function getSponsorStats(int $sponsorId): array
    {
        $pointsAllTime = $this->getPoints($sponsorId, null);
        $pointsRecent = $this->getPoints($sponsorId, 90);
        $tier = $this->getTier($pointsAllTime);

        $nextMin = $tier['next']['min'] ?? null;
        $progress = null;
        if ($nextMin) {
            $from = $tier['current']['min'];
            $progress = [
                'current' => max(0, $pointsAllTime - $from),
                'total' => max(1, $nextMin - $from),
                'percent' => round(100 * max(0, $pointsAllTime - $from) / max(1, $nextMin - $from))
            ];
        }

        $milestones = $this->getMilestones($sponsorId);
        $badges = $this->getBadges($sponsorId, $pointsAllTime);
        $message = $this->buildAppreciationMessage($tier['current'], $pointsRecent);

        return compact('pointsAllTime', 'pointsRecent', 'tier', 'progress', 'milestones', 'badges', 'message');
    }

    /**
     * Compute professional badges for sponsors.
     * - Bronze: First sponsorship achieved
     * - Silver: Consistent support (donations in 3 consecutive months)
     * - Gold: Total confirmed support >= 5,000
     * - Platinum: Total confirmed support >= 10,000
     */
    public function getBadges(int $sponsorId, ?int $pointsAllTime = null): array
    {
        $pointsAllTime = $pointsAllTime ?? $this->getPoints($sponsorId, null);

        $hasFirstSponsorship = Donation::confirmed()->where('type', 'sponsor')->where('sponsor_id', $sponsorId)->exists()
            || SponsorEvent::where('sponsor_id', $sponsorId)->where('status', 'active')->exists();

        $monthly = Donation::confirmed()->where('type', 'sponsor')->where('sponsor_id', $sponsorId)
            ->selectRaw('DATE_FORMAT(COALESCE(donated_at, created_at), "%Y-%m") as ym, COUNT(*) as c')
            ->groupBy('ym')->orderBy('ym', 'desc')->pluck('c', 'ym');
        $consistent = $this->hasConsecutiveMonths($monthly, 3);

        $badges = [];
        if ($hasFirstSponsorship) {
            $badges[] = [
                'slug' => 'bronze',
                'label' => 'Bronze',
                'description' => 'Première action de sponsoring réalisée.',
                'image_url' => 'images/badges/bronze.png',
            ];
        }
        if ($consistent) {
            $badges[] = [
                'slug' => 'silver',
                'label' => 'Argent',
                'description' => 'Soutien constant au fil des mois.',
                'image_url' => 'images/badges/silver.png',
            ];
        }
        if ($pointsAllTime >= 5000) {
            $badges[] = [
                'slug' => 'gold',
                'label' => 'Or',
                'description' => 'Contribution financière significative (≥ 5 000).',
                'image_url' => 'images/badges/golden.png',
            ];
        }
        if ($pointsAllTime >= 10000) {
            $badges[] = [
                'slug' => 'platinum',
                'label' => 'Platine',
                'description' => 'Engagement exceptionnel et durable (≥ 10 000).',
                'image_url' => 'images/badges/platinum.png',
            ];
        }

        return $badges;
    }

    private function buildAppreciationMessage(array $currentTier, int $pointsRecent): string
    {
        if ($currentTier['slug'] === 'none') {
            return "Merci pour votre soutien à nos initiatives. Votre contribution a un impact réel et mesurable.";
        }
        if ($pointsRecent > 0) {
            return sprintf("Merci pour votre engagement continu. Votre impact récent est remarquable — le niveau %s reconnaît votre contribution soutenue.", $currentTier['label']);
        }
        return sprintf("Merci pour votre généreux soutien. Votre reconnaissance au niveau %s reflète un impact durable et significatif.", $currentTier['label']);
    }

    public function topSponsors(int $limit = 10, int $periodDays = 90): Collection
    {
        $cutoff = Carbon::now()->subDays($periodDays);

        $donations = Donation::confirmed()->where('type', 'sponsor')
            ->where(function ($q) use ($cutoff) {
                $q->whereNotNull('donated_at')->where('donated_at', '>=', $cutoff)
                    ->orWhere(function ($qq) use ($cutoff) {
                        $qq->whereNull('donated_at')->where('created_at', '>=', $cutoff);
                    });
            })
            ->selectRaw('sponsor_id, SUM(amount) as total')
            ->groupBy('sponsor_id')
            ->pluck('total', 'sponsor_id');

        $sponsorships = SponsorEvent::where('status', 'active')
            ->where('created_at', '>=', $cutoff)
            ->selectRaw('sponsor_id, SUM(amount) as total')
            ->groupBy('sponsor_id')
            ->pluck('total', 'sponsor_id');

        $combined = [];
        foreach ($donations as $sid => $sum) {
            $combined[$sid] = ($combined[$sid] ?? 0) + (float)$sum;
        }
        foreach ($sponsorships as $sid => $sum) {
            $combined[$sid] = ($combined[$sid] ?? 0) + (float)$sum;
        }

        arsort($combined);
        $topIds = array_slice(array_keys($combined), 0, $limit);
        $sponsors = Sponsor::validated()->whereIn('id', $topIds)->get()->keyBy('id');

        $result = collect();
        foreach ($topIds as $sid) {
            if (!isset($sponsors[$sid])) continue;
            $points = (int) round($combined[$sid]);
            $tier = $this->getTier($points);
            $result->push([
                'sponsor' => $sponsors[$sid],
                'pointsRecent' => $points,
                'tier' => $tier['current'],
            ]);
        }
        return $result;
    }
}
