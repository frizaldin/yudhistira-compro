<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class BuyBukuProductCatalog
{
    private function baseUrl(): string
    {
        return (string) env('API_URL', '');
    }

    private function headers(): array
    {
        return [
            'key' => env('API_KEY'),
        ];
    }

    /**
     * Map book_id => ['title' => string, 'file' => ?string (URL absolut jika memungkinkan)].
     * Data di-cache singkat agar tidak memanggil API berulang tiap request list.
     *
     * @param  array<int>  $ids
     * @return array<int, array{title: string, file: ?string}>
     */
    public function metaForIds(array $ids): array
    {
        $ids = array_values(array_unique(array_filter(array_map('intval', $ids))));
        if ($ids === []) {
            return [];
        }

        $map = $this->fullMapCached();
        $out = [];
        foreach ($ids as $id) {
            if (isset($map[$id])) {
                $out[$id] = $map[$id];
            }
        }

        return $out;
    }

    /**
     * @return array<int, array{title: string, file: ?string}>
     */
    private function fullMapCached(): array
    {
        $baseUrl = $this->baseUrl();
        if ($baseUrl === '') {
            return [];
        }

        return Cache::remember('buy_buku_product_catalog_map_v1', 600, function () use ($baseUrl) {
            return $this->fetchFullMap($baseUrl);
        });
    }

    /**
     * @return array<int, array{title: string, file: ?string}>
     */
    private function fetchFullMap(string $baseUrl): array
    {
        $map = [];
        $page = 1;
        $maxPages = 100;

        do {
            $response = Http::timeout(20)
                ->withHeaders($this->headers())
                ->get(rtrim($baseUrl, '/') . '/products', [
                    'page' => $page,
                    'per_page' => 100,
                ]);

            if (!$response->successful()) {
                break;
            }

            $rows = $this->rowsFromProductsResponse($response->json() ?? []);
            foreach ($rows as $row) {
                if (!is_array($row)) {
                    continue;
                }
                $id = isset($row['id']) ? (int) $row['id'] : 0;
                if ($id < 1) {
                    continue;
                }
                $title = $row['title'] ?? $row['name'] ?? $row['product_name'] ?? $row['judul'] ?? '';
                $thumb = $row['file_url'] ?? $row['file'] ?? null;
                $map[$id] = [
                    'title' => is_string($title) ? $title : (string) $title,
                    'file' => $thumb,
                    'array' => $row,
                ];
            }

            $page++;
            if (count($rows) < 100) {
                break;
            }
        } while ($page <= $maxPages);

        return $map;
    }

    private function rowsFromProductsResponse(array $body): array
    {
        if (isset($body['data']) && is_array($body['data'])) {
            if (array_is_list($body['data'])) {
                return $body['data'];
            }
            if (isset($body['data']['data']) && is_array($body['data']['data'])) {
                return $body['data']['data'];
            }
        }
        if (isset($body['products']) && is_array($body['products'])) {
            return $body['products'];
        }

        return [];
    }

    private function absoluteMediaUrl(string $apiBaseUrl, mixed $path): ?string
    {
        if ($path === null || $path === '') {
            return null;
        }
        $path = is_string($path) ? trim($path) : '';
        if ($path === '') {
            return null;
        }
        if (preg_match('#^https?://#i', $path)) {
            return $path;
        }

        $root = preg_replace('#/products/?$#i', '', rtrim($apiBaseUrl, '/'));
        $root = rtrim($root, '/');

        return $root . '/' . ltrim($path, '/');
    }
}
