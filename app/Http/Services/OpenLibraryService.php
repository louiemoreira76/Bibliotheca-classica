<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class OpenLibraryService
{
    private string $baseUrl = 'https://openlibrary.org';
    private string $coversUrl = 'https://covers.openlibrary.org';

    public function search(string $query, int $limit = 10, int $page = 1): array
    {
        $cacheKey = "openlibrary_search_{$query}_{$limit}_{$page}";

        return Cache::remember($cacheKey, now()->addMinutes(30), function () use ($query, $limit, $page) {
            $response = Http::timeout(10)->get("{$this->baseUrl}/search.json", [
                'q'     => $query,
                'limit' => $limit,
                'page'  => $page,
                'fields' => 'key,title,author_name,first_publish_year,cover_i,subject,isbn,number_of_pages_median,edition_count,language',
            ]);

            if ($response->failed()) {
                return ['docs' => [], 'numFound' => 0];
            }

            $data = $response->json();

            return [
                'numFound' => $data['numFound'] ?? 0,
                'docs'     => array_map([$this, 'formatDoc'], $data['docs'] ?? []),
            ];
        });
    }

    public function getWorkDetails(string $workKey): ?array
    {
        $cacheKey = "openlibrary_work_{$workKey}";

        return Cache::remember($cacheKey, now()->addHours(24), function () use ($workKey) {
            $response = Http::timeout(10)->get("{$this->baseUrl}{$workKey}.json");

            if ($response->failed()) {
                return null;
            }

            $data = $response->json();

            return [
                'key'         => $data['key'] ?? $workKey,
                'title'       => $data['title'] ?? 'Unknown Title',
                'description' => is_array($data['description'] ?? null)
                    ? ($data['description']['value'] ?? null)
                    : ($data['description'] ?? null),
                'subjects'    => $data['subjects'] ?? [],
                'subjectPlaces' => $data['subject_places'] ?? [],
                'subjectPeople' => $data['subject_people'] ?? [],
                'subjectTimes'  => $data['subject_times'] ?? [],
                'firstPublishDate' => $data['first_publish_date'] ?? null,
                'links'       => array_map(fn($l) => ['title' => $l['title'] ?? '', 'url' => $l['url'] ?? ''], $data['links'] ?? []),
                'coversUrl'   => isset($data['covers'][0])
                    ? "{$this->coversUrl}/b/id/{$data['covers'][0]}-L.jpg"
                    : null,
            ];
        });
    }

    public function getAuthorDetails(string $authorKey): ?array
    {
        $cacheKey = "openlibrary_author_{$authorKey}";

        return Cache::remember($cacheKey, now()->addHours(24), function () use ($authorKey) {
            $response = Http::timeout(10)->get("{$this->baseUrl}{$authorKey}.json");

            if ($response->failed()) {
                return null;
            }

            $data = $response->json();
            $photoId = $data['photos'][0] ?? null;

            return [
                'key'         => $data['key'] ?? $authorKey,
                'name'        => $data['name'] ?? 'Unknown Author',
                'bio'         => is_array($data['bio'] ?? null) ? ($data['bio']['value'] ?? null) : ($data['bio'] ?? null),
                'birthDate'   => $data['birth_date'] ?? null,
                'deathDate'   => $data['death_date'] ?? null,
                'wikipedia'   => $data['wikipedia'] ?? null,
                'photoUrl'    => $photoId ? "https://covers.openlibrary.org/a/id/{$photoId}-M.jpg" : null,
            ];
        });
    }

    private function formatDoc(array $doc): array
    {
        $coverId = $doc['cover_i'] ?? null;

        return [
            'key'           => $doc['key'] ?? null,
            'title'         => $doc['title'] ?? 'Unknown Title',
            'authors'       => $doc['author_name'] ?? ['Unknown Author'],
            'publishYear'   => $doc['first_publish_year'] ?? null,
            'subjects'      => array_slice($doc['subject'] ?? [], 0, 5),
            'isbn'          => $doc['isbn'][0] ?? null,
            'pages'         => $doc['number_of_pages_median'] ?? null,
            'editions'      => $doc['edition_count'] ?? null,
            'languages'     => $doc['language'] ?? [],
            'thumbnail'     => $coverId ? "{$this->coversUrl}/b/id/{$coverId}-M.jpg" : null,
            'largeCover'    => $coverId ? "{$this->coversUrl}/b/id/{$coverId}-L.jpg" : null,
        ];
    }
}
