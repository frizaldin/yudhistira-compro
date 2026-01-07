<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ProductApiService
{
    private $apiBaseUrl = 'http://buy.bukuyudhistira.id/shared/product';

    /**
     * Fetch products from external API
     *
     * @return array|null
     */
    public function getProducts()
    {
        try {
            $response = Http::timeout(10)
                ->withHeaders([
                    'key' => '1hQ8scCd7357103168b059a3E4ZUVT3k'
                ])
                ->get($this->apiBaseUrl);

            if ($response->successful()) {
                $data = $response->json();

                return $data['data'];
            }

            Log::warning('Product API returned unsuccessful response', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            return [];
        } catch (\Exception $e) {
            Log::error('Error fetching products from API', [
                'error' => $e->getMessage()
            ]);

            return [];
        }
    }

    /**
     * Transform API product data to match local product structure
     *
     * @param array $apiProduct
     * @return array
     */
    public function transformProduct($apiProduct)
    {
        return [
            'photo' => $apiProduct['file_url'] ?? $apiProduct['file'] ?? null,
            'title' => $apiProduct['title'] ?? '',
            'url' => $apiProduct['url_goto_website'] ?? '',
        ];
    }
}
