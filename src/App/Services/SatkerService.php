<?php

namespace Paparee\BaleInv\App\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class SatkerService
{
    protected string $url = 'https://sadap.ponorogo.go.id/api/dataPeta';
    protected string $cacheKey = 'bale_inv_maps';
    protected int $cacheMinutes = 1440;

    /**
     * Ambil lokasi dari cache atau API.
     */
    public function getLocations()
    {
        return 
        Cache::remember($this->cacheKey, now()->addMinutes($this->cacheMinutes), function () {
            $response = Http::get($this->url)->json();

            $data = $response['data'] ?? [];

            return collect($data)->map(function ($item) {
                return [
                    'id' => $item['id'] ?? '',
                    'name' => $item['name'] ?? '',
                    'description' => $item['description'] ?? '',
                    'alamat' => $item['alamat'] ?? '',
                    'map' => $item['map'] ?? '',
                ];
            });
        });
    }
    
    /**
     * Ambil src dari iframe.
     */
    // protected function extractIframeSrc(string $iframe): ?string
    // {
    //     return preg_match('/src="([^"]+)"/', $iframe, $match) ? $match[1] : null;
    // }
}
