<?php

namespace App\Http\Controllers\Sponsor;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the sponsor dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index(): View
    {
        $events = Event::where('status', 'published')->latest()->take(5)->get();
        return view('sponsor.dashboard', compact('events'));
    }
}
