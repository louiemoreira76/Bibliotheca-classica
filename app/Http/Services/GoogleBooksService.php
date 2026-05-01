<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class GoogleBooksService
{
    private string $baseUrl = 'https://www.googleapis.com/books/v1';
    private ?string $apiKey;

    public function __construct()
    {
        $this->apiKey = config('services.google_books.key');
    }

    public function search(string $query, int $maxResults = 20, int $startIndex = 0, string $filter = ''): array
    {
        $cacheKey = "google_books_search_{$query}_{$maxResults}_{$startIndex}_{$filter}";

        return Cache::remember($cacheKey, now()->addMinutes(30), function () use ($query, $maxResults, $startIndex, $filter) {
            $params = [
                'q'          => $query,
                'maxResults' => min($maxResults, 40),
                'startIndex' => $startIndex,
                'printType'  => 'books',
                'langRestrict' => 'en',
            ];

            if ($filter) {
                $params['filter'] = $filter;
            }

            if ($this->apiKey) {
                $params['key'] = $this->apiKey;
            }

            $response = Http::timeout(10)->get("{$this->baseUrl}/volumes", $params);

            if ($response->failed()) {
                return ['items' => [], 'totalItems' => 0, 'error' => 'Google Books API unavailable'];
            }

            $data = $response->json();

            return [
                'totalItems' => $data['totalItems'] ?? 0,
                'items'      => array_map([$this, 'formatVolume'], $data['items'] ?? []),
            ];
        });
    }

    public function getById(string $id): ?array
    {
        $cacheKey = "google_books_volume_{$id}";

        return Cache::remember($cacheKey, now()->addHours(24), function () use ($id) {
            $params = $this->apiKey ? ['key' => $this->apiKey] : [];
            $response = Http::timeout(10)->get("{$this->baseUrl}/volumes/{$id}", $params);

            if ($response->failed()) {
                return null;
            }

            return $this->formatVolume($response->json(), true);
        });
    }

    public function getClassics(string $subject = 'classic literature', int $maxResults = 10): array
    {
        return $this->search("subject:{$subject} classic", $maxResults);
    }

    private function formatVolume(array $volume, bool $detailed = false): array
    {
        $info = $volume['volumeInfo'] ?? [];
        $sale = $volume['saleInfo'] ?? [];
        $access = $volume['accessInfo'] ?? [];

        $imageLinks = $info['imageLinks'] ?? [];
        $thumbnail = $imageLinks['thumbnail']
            ?? $imageLinks['smallThumbnail']
            ?? null;

        // Force HTTPS
        if ($thumbnail) {
            $thumbnail = str_replace('http://', 'https://', $thumbnail);
        }

        $formatted = [
            'id'            => $volume['id'] ?? null,
            'title'         => $info['title'] ?? 'Unknown Title',
            'subtitle'      => $info['subtitle'] ?? null,
            'authors'       => $info['authors'] ?? ['Unknown Author'],
            'publisher'     => $info['publisher'] ?? null,
            'publishedDate' => $info['publishedDate'] ?? null,
            'description'   => $info['description'] ?? null,
            'categories'    => $info['categories'] ?? [],
            'thumbnail'     => $thumbnail,
            'pageCount'     => $info['pageCount'] ?? null,
            'language'      => $info['language'] ?? null,
            'maturityRating' => $info['maturityRating'] ?? null,
            'averageRating' => $info['averageRating'] ?? null,
            'ratingsCount'  => $info['ratingsCount'] ?? null,
            'previewLink'   => $info['previewLink'] ?? null,
            'infoLink'      => $info['infoLink'] ?? null,
            'canonicalLink' => $volume['selfLink'] ?? null,
            'isbn10'        => null,
            'isbn13'        => null,
            'isEbook'       => $access['epub']['isAvailable'] ?? false,
            'isPdf'         => $access['pdf']['isAvailable'] ?? false,
            'webReaderLink' => $access['webReaderLink'] ?? null,
            'buyLink'       => $sale['buyLink'] ?? null,
            'saleability'   => $sale['saleability'] ?? null,
        ];

        // Extract ISBNs
        foreach ($info['industryIdentifiers'] ?? [] as $identifier) {
            if ($identifier['type'] === 'ISBN_10') {
                $formatted['isbn10'] = $identifier['identifier'];
            }
            if ($identifier['type'] === 'ISBN_13') {
                $formatted['isbn13'] = $identifier['identifier'];
            }
        }

        if ($detailed) {
            $formatted['tableOfContents'] = $info['tableOfContents'] ?? null;
            $formatted['contentVersion']  = $info['contentVersion'] ?? null;
            $formatted['country']         = $access['country'] ?? null;
            $formatted['viewability']     = $access['viewability'] ?? null;
            $formatted['embeddable']      = $access['embeddable'] ?? false;
            $formatted['publicDomain']    = $access['publicDomain'] ?? false;
            if (!empty($imageLinks['large'])) {
                $formatted['thumbnail'] = str_replace('http://', 'https://', $imageLinks['large']);
            } elseif (!empty($imageLinks['medium'])) {
                $formatted['thumbnail'] = str_replace('http://', 'https://', $imageLinks['medium']);
            }
        }

        return $formatted;
    }
}
