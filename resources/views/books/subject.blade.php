@extends('layouts.app')

@section('title', ucwords($subject) . ' — Bibliotheca Classica')

@section('content')

<div class="search-page-header" style="background:linear-gradient(180deg,#0D0404 0%,#180808 100%);border-bottom:2px solid rgba(184,134,11,0.4);padding:40px 24px 32px;">
    <div class="container">
        <div style="font-family:var(--ff-display);font-size:0.6rem;font-weight:700;letter-spacing:0.2em;text-transform:uppercase;color:rgba(184,134,11,0.45);margin-bottom:16px;">
            <a href="{{ route('home') }}" style="color:inherit;text-decoration:none;">Grand Hall</a>
            <span style="opacity:.4;margin:0 8px;">›</span>
            Subject
        </div>
        <h1 style="font-family:var(--ff-heading);font-weight:900;font-size:clamp(1.8rem,4vw,3rem);color:var(--parchment);margin-bottom:8px;">
            {{ ucwords($subject) }}
        </h1>
        <p style="font-family:var(--ff-body);font-style:italic;color:rgba(242,232,213,0.45);">
            {{ number_format($totalItems) }} volumes in this collection
        </p>
    </div>
</div>

<div class="container" style="padding-top:48px;padding-bottom:48px;">
    @if (!empty($books))
        <div class="books-grid">
            @foreach ($books as $book)
                <a href="{{ route('books.show', ['id' => $book['id']]) }}" class="book-card">
                    @if (!empty($book['thumbnail']))
                        <img class="book-cover" src="{{ $book['thumbnail'] }}" alt="{{ $book['title'] }}" loading="lazy">
                    @else
                        <div class="book-cover-placeholder">
                            <span class="placeholder-icon">📖</span>
                            <span class="placeholder-title">{{ Str::limit($book['title'], 40) }}</span>
                        </div>
                    @endif
                    <div class="book-info">
                        <div class="book-title">{{ $book['title'] }}</div>
                        <div class="book-author">{{ implode(', ', array_slice($book['authors'] ?? ['Unknown'], 0, 2)) }}</div>
                        @if (!empty($book['publishedDate']))
                            <div class="book-year">{{ substr($book['publishedDate'], 0, 4) }}</div>
                        @endif
                    </div>
                </a>
            @endforeach
        </div>

        @if ($totalPages > 1)
            <div class="pagination">
                @if ($page > 1)
                    <a href="{{ request()->fullUrlWithQuery(['page' => $page - 1]) }}">←</a>
                @endif
                @for ($i = max(1, $page - 2); $i <= min($totalPages, $page + 3); $i++)
                    @if ($i === $page)
                        <span class="current">{{ $i }}</span>
                    @else
                        <a href="{{ request()->fullUrlWithQuery(['page' => $i]) }}">{{ $i }}</a>
                    @endif
                @endfor
                @if ($page < $totalPages)
                    <a href="{{ request()->fullUrlWithQuery(['page' => $page + 1]) }}">→</a>
                @endif
            </div>
        @endif
    @else
        <div style="text-align:center;padding:80px 24px;color:var(--leather);">
            <div style="font-size:3rem;margin-bottom:16px;">📚</div>
            <h3 style="font-family:var(--ff-heading);font-style:italic;color:var(--mahogany);margin-bottom:10px;">No volumes found</h3>
            <p style="font-family:var(--ff-body);">This collection appears to be empty. <a href="{{ route('home') }}" style="color:var(--burgundy);">Return to the Grand Hall</a>.</p>
        </div>
    @endif
</div>

@endsection
