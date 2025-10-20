<?php

namespace App\Services\Ai;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class CloudflareImageProvider
{
    protected string $accountId;
    protected string $apiToken;
    protected string $model;
    protected int $width;
    protected int $height;
    protected float $guidance = 4.5;
    protected int $steps = 24;

    public function __construct()
    {
        $cfg = config('flyer.image.cloudflare');
        $this->accountId = (string) ($cfg['account_id'] ?? '');
        $this->apiToken = (string) ($cfg['api_token'] ?? '');
        $this->model = (string) ($cfg['model'] ?? '@leonardo/lucid-origin');
        $this->width = (int) ($cfg['width'] ?? 1792);
        $this->height = (int) ($cfg['height'] ?? 1024);
    }

    public function generate(string $prompt, string $disk, string $path): ?string
    {
        if (empty($this->accountId) || empty($this->apiToken)) {
            return null;
        }

        $client = new Client(['timeout' => 60]);
        // Cloudflare expects the model path with slashes intact, no URL-encoding
        $modelPath = trim($this->model);
        $modelPath = trim($modelPath, "\"'\t\n\r ");
        if (str_starts_with($modelPath, '/')) {
            $modelPath = ltrim($modelPath, '/');
        }
        $url = sprintf('https://api.cloudflare.com/client/v4/accounts/%s/ai/run/%s', $this->accountId, $modelPath);

        Log::info('[CF-Image] POST', ['url' => $url, 'model' => $modelPath, 'w' => $this->width, 'h' => $this->height]);
        $resp = $client->post($url, [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->apiToken,
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'prompt' => $prompt,
                'width' => $this->width,
                'height' => $this->height,
                'guidance' => $this->guidance,
                'steps' => $this->steps,
            ],
        ]);

        $contentType = $resp->getHeaderLine('content-type');
        Log::info('[CF-Image] Response', ['content_type' => $contentType, 'status' => $resp->getStatusCode()]);
        $body = (string) $resp->getBody();

        if (stripos($contentType, 'image/') === 0) {
            $bin = $body; // already binary image
        } else {
            $data = json_decode($body, true);
            $b64 = $data['result']['image'] ?? ($data['result']['images'][0] ?? null);
            if (!$b64) return null;
            $bin = base64_decode($b64);
        }

        Storage::disk($disk)->put($path, $bin);
        Log::info('[CF-Image] Saved', ['disk' => $disk, 'path' => $path, 'bytes' => strlen($bin)]);
        return Storage::disk($disk)->path($path);
    }

    public function generateToAbsolute(string $prompt, string $absPath): ?string
    {
        if (empty($this->accountId) || empty($this->apiToken)) {
            return null;
        }
        $client = new Client(['timeout' => 60]);
        $modelPath = trim($this->model, "\"'\t\n\r ");
        if (str_starts_with($modelPath, '/')) {
            $modelPath = ltrim($modelPath, '/');
        }
        $url = sprintf('https://api.cloudflare.com/client/v4/accounts/%s/ai/run/%s', $this->accountId, $modelPath);

        Log::info('[CF-Image] POST(abs)', ['url' => $url, 'path' => $absPath]);
        $resp = $client->post($url, [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->apiToken,
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'prompt' => $prompt,
                'width' => $this->width,
                'height' => $this->height,
                'guidance' => $this->guidance,
                'steps' => $this->steps,
            ],
        ]);

        $contentType = $resp->getHeaderLine('content-type');
        $body = (string) $resp->getBody();
        if (stripos($contentType, 'image/') === 0) {
            $bin = $body;
        } else {
            $data = json_decode($body, true);
            $b64 = $data['result']['image'] ?? ($data['result']['images'][0] ?? null);
            if (!$b64) return null;
            $bin = base64_decode($b64);
        }
        @mkdir(dirname($absPath), 0775, true);
        file_put_contents($absPath, $bin);
        Log::info('[CF-Image] Saved(abs)', ['abs' => $absPath, 'bytes' => strlen($bin)]);
        return $absPath;
    }
}
