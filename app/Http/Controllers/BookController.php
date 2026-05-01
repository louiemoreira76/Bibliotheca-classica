<?php

namespace App\Http\Controllers;

use App\Http\Services\GoogleBooksService;
use App\Http\Services\OpenLibraryService;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function __construct(
        private GoogleBooksService $googleBooks,
        private OpenLibraryService $openLibrary,
    ) {}

    public function index(Request $request)
    {
        // Featured classic categories for the homepage
        $classics = collect([
            'Victorian Literature'    => 'subject:victorian+fiction',
            'Renaissance Masterworks' => 'subject:renaissance+literature',
            'Ancient Philosophy'      => 'subject:ancient+philosophy',
        ]);

        $featured = [];
        foreach ($classics as $label => $query) {
            $result = $this->googleBooks->search($query, 4);
            if (!empty($result['items'])) {
                $featured[$label] = $result['items'];
            }
        }

        return view('home', compact('featured'));
    }

    public function search(Request $request)
    {
        $request->validate([
            'q'      => 'required|string|min:2|max:200',
            'source' => 'nullable|in:google,openlibrary,all',
            'page'   => 'nullable|integer|min:1',
        ]);

        $query  = $request->get('q');
        $source = $request->get('source', 'all');
        $page   = max(1, (int) $request->get('page', 1));
        $perPage = 12;
        $startIndex = ($page - 1) * $perPage;

        $googleResults = [];
        $openLibResults = [];
        $totalItems = 0;

        if (in_array($source, ['google', 'all'])) {
            $res = $this->googleBooks->search($query, $perPage, $startIndex);
            $googleResults = $res['items'] ?? [];
            if ($source === 'google') {
                $totalItems = $res['totalItems'] ?? 0;
            }
        }

        if (in_array($source, ['openlibrary', 'all'])) {
            $res = $this->openLibrary->search($query, $perPage, $page);
            $openLibResults = $res['docs'] ?? [];
            if ($source === 'openlibrary') {
                $totalItems = $res['numFound'] ?? 0;
            }
        }

        if ($source === 'all') {
            $totalItems = max(count($googleResults), count($openLibResults)) * 10;
        }

        $totalPages = $totalItems > 0 ? (int) ceil($totalItems / $perPage) : 1;

        return view('books.search', compact(
            'query', 'source', 'page', 'perPage',
            'googleResults', 'openLibResults',
            'totalItems', 'totalPages'
        ));
    }

    public function show(Request $request, string $id)
    {
        $source = $request->get('source', 'google');
        $book   = null;
        $workDetails = null;
        $authorDetails = null;
        $openLibData = null;

        if ($source === 'google') {
            $book = $this->googleBooks->getById($id);
            if (!$book) {
                abort(404, 'Book not found in Google Books catalogue.');
            }

            // Try to enrich with Open Library
            $isbn = $book['isbn13'] ?? $book['isbn10'] ?? null;
            if ($isbn) {
                $olSearch = $this->openLibrary->search("isbn:{$isbn}", 1);
                if (!empty($olSearch['docs'][0])) {
                    $openLibData = $olSearch['docs'][0];
                    $workKey = $openLibData['key'] ?? null;
                    if ($workKey) {
                        $workDetails = $this->openLibrary->getWorkDetails($workKey);
                    }
                }
            }

        } elseif ($source === 'openlibrary') {
            $workKey     = '/works/' . $id;
            $workDetails = $this->openLibrary->getWorkDetails($workKey);
            if (!$workDetails) {
                abort(404, 'Work not found in Open Library catalogue.');
            }
            $book = $workDetails;
        }

        // Build related books
        $relatedBooks = [];
        if (!empty($book['authors'])) {
            $authorName   = is_array($book['authors']) ? $book['authors'][0] : $book['authors'];
            $relatedQuery = "inauthor:\"{$authorName}\"";
            $related      = $this->googleBooks->search($relatedQuery, 6);
            $relatedBooks = array_filter(
                $related['items'] ?? [],
                fn($r) => $r['id'] !== $id
            );
        }

        return view('books.show', compact('book', 'source', 'workDetails', 'openLibData', 'relatedBooks'));
    }

    public function bySubject(Request $request, string $subject)
    {
        $page   = max(1, (int) $request->get('page', 1));
        $perPage = 12;
        $startIndex = ($page - 1) * $perPage;

        $result = $this->googleBooks->search("subject:{$subject}", $perPage, $startIndex);
        $books  = $result['items'] ?? [];
        $totalItems = $result['totalItems'] ?? 0;
        $totalPages = $totalItems > 0 ? (int) ceil($totalItems / $perPage) : 1;

        return view('books.subject', compact('subject', 'books', 'page', 'totalItems', 'totalPages'));
    }
}
