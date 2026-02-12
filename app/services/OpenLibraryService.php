<?php

namespace App\Services;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OpenLibraryService
{
    private const BASE_URL = 'https://openlibrary.org';
    private const COVERS_URL = 'https://covers.openlibrary.org/b';
    private const TIMEOUT = 10;

    public function search(string $query, int $limit = 150): array
    {
        try {
            $url = self::BASE_URL . '/search.json';
            $params = [
                'q' => $query,
                'limit' => $limit,
                'fields' => 'key,title,author_name,first_publish_year,isbn,cover_i,publisher,number_of_pages_median',
            ];

            Log::info('OpenLibrary API Request', ['url' => $url, 'params' => $params]);

            /** @var Response $response */
            $response = Http::withoutVerifying()
                ->timeout(self::TIMEOUT)
                ->get($url, $params);

            Log::info('OpenLibrary API Response', [
                'status' => $response->status(),
                'successful' => $response->successful(),
                'body_length' => strlen($response->body()),
                'body_preview' => substr($response->body(), 0, 500),
            ]);

            if ($response->successful()) {
                $data = $response->json('docs', []);
                Log::info('Parsed docs', ['count' => count($data), 'first_item' => $data[0] ?? null]);
                return $data;
            }

            Log::warning('OpenLibrary search failed', ['status' => $response->status(), 'body' => $response->body()]);
            return [];
        } catch (\Exception $e) {
            Log::error('OpenLibrary search exception', ['message' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return [];
        }
    }

    public function getCoverUrl(?string $isbn = null, ?int $coverId = null, string $size = 'M'): ?string
    {
        if ($isbn) {
            return self::COVERS_URL . "/isbn/{$isbn}-{$size}.jpg";
        }

        if ($coverId) {
            return self::COVERS_URL . "/id/{$coverId}-{$size}.jpg";
        }

        return null;
    }

    public function transformBook(array $book): array
    {
        return [
            'title' => $book['title'] ?? 'Unknown Title',
            'author' => $book['author_name'][0] ?? 'Unknown Author',
            'isbn' => $book['isbn'][0] ?? null,
            'openlibrary_key' => $book['key'] ?? null,
            'publish_year' => $book['first_publish_year'] ?? null,
            'publisher' => $book['publisher'][0] ?? null,
            'cover_url' => $this->getCoverUrl(
                isbn: $book['isbn'][0] ?? null,
                coverId: $book['cover_i'] ?? null
            ),
            'pages' => $book['number_of_pages_median'] ?? null,
        ];
    }
}
