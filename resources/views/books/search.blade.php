@extends('layouts.app')

@section('title', 'Search: ' . $query . ' — Bibliotheca Classica')
@section('meta_description', 'Search results for "' . $query . '" in the Bibliotheca Classica catalogue')

@push('styles')
<style>
    /* ── SEARCH PAGE HEADER ──────────────────── */
    .search-page-header {
        background: linear-gradient(180deg, #0D0404 0%, #180808 100%);
        border-bottom: 2px solid rgba(184,134,11,0.4);
        padding: 40px 24px 32px;
    }

    .search-breadcrumb {
        font-family: var(--ff-display);
        font-size: 0.6rem;
        font-weight: 600;
        letter-spacing: 0.2em;
        text-transform: uppercase;
        color: rgba(184,134,11,0.45);
        margin-bottom: 18px;
    }

    .search-breadcrumb a {
        color: rgba(184,134,11,0.45);
        text-decoration: none;
    }

    .search-breadcrumb a:hover {
        color: var(--gold);
    }

    .search-heading {
        font-family: var(--ff-heading);
        font-size: clamp(1.5rem, 3vw, 2.4rem);
        font-weight: 700;
        color: var(--parchment);
        margin-bottom: 6px;
    }

    .search-heading em {
        font-style: italic;
        color: var(--gold-light);
    }

    .search-count {
        font-family: var(--ff-body);
        font-style: italic;
        font-size: 1rem;
        color: rgba(242,232,213,0.45);
        margin-bottom: 28px;
    }

    /* ── REFINE FORM ──────────────────────────── */
    .refine-form {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        align-items: stretch;
        max-width: 700px;
    }

    .refine-input-group {
        display: flex;
        flex: 1;
        min-width: 280px;
        border: 1px solid rgba(184,134,11,0.5);
        border-radius: 3px;
        overflow: hidden;
    }

    .refine-input-group input {
        flex: 1;
        background: rgba(242,232,213,0.06);
        border: none;
        padding: 12px 18px;
        font-family: var(--ff-body);
        font-size: 1rem;
        color: var(--parchment);
        outline: none;
    }

    .refine-input-group input::placeholder {
        color: rgba(242,232,213,0.28);
        font-style: italic;
    }

    .refine-input-group button {
        background: var(--gold);
        border: none;
        padding: 12px 22px;
        font-family: var(--ff-display);
        font-size: 0.65rem;
        font-weight: 700;
        letter-spacing: 0.15em;
        text-transform: uppercase;
        color: var(--mahogany);
        cursor: pointer;
        transition: background 0.2s;
    }

    .refine-input-group button:hover { background: var(--gold-light); }

    .source-toggle {
        display: flex;
        align-items: center;
        gap: 6px;
        border: 1px solid rgba(184,134,11,0.3);
        border-radius: 3px;
        overflow: hidden;
    }

    .source-btn {
        padding: 10px 16px;
        background: transparent;
        border: none;
        border-right: 1px solid rgba(184,134,11,0.2);
        font-family: var(--ff-display);
        font-size: 0.6rem;
        font-weight: 600;
        letter-spacing: 0.12em;
        text-transform: uppercase;
        color: rgba(242,232,213,0.45);
        cursor: pointer;
        transition: all 0.2s;
        white-space: nowrap;
    }

    .source-btn:last-child { border-right: none; }
    .source-btn.active,
    .source-btn:hover {
        background: rgba(184,134,11,0.15);
        color: var(--gold-light);
    }

    /* ── RESULTS LAYOUT ──────────────────────── */
    .results-wrapper {
        padding: 40px 0;
    }

    .results-section-title {
        display: flex;
        align-items: center;
        gap: 14px;
        margin-bottom: 24px;
        padding-bottom: 12px;
        border-bottom: 1px solid rgba(139,67,20,0.15);
    }

    .results-section-title h3 {
        font-family: var(--ff-display);
        font-size: 0.68rem;
        font-weight: 700;
        letter-spacing: 0.2em;
        text-transform: uppercase;
        color: var(--gold);
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .results-section-title .source-icon {
        width: 16px;
        height: 16px;
        opacity: 0.7;
    }

    .count-badge {
        padding: 2px 10px;
        background: rgba(107,26,26,0.12);
        border: 1px solid rgba(107,26,26,0.2);
        border-radius: 20px;
        font-family: var(--ff-display);
        font-size: 0.58rem;
        font-weight: 600;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        color: var(--leather);
    }

    /* ── BOOK LIST ITEM (detailed) ───────────── */
    .book-list {
        display: flex;
        flex-direction: column;
        gap: 1px;
    }

    .book-list-item {
        display: flex;
        gap: 22px;
        padding: 22px;
        background: #FEFAF2;
        border: 1px solid rgba(139,67,20,0.12);
        border-radius: 3px;
        text-decoration: none;
        color: var(--ink);
        transition: box-shadow 0.2s, border-color 0.2s, background 0.2s;
        position: relative;
    }

    .book-list-item::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 4px;
        background: linear-gradient(180deg, var(--burgundy), var(--mahogany));
        border-radius: 3px 0 0 3px;
        opacity: 0.5;
        transition: opacity 0.2s;
    }

    .book-list-item:hover {
        background: #FBF5E6;
        border-color: rgba(184,134,11,0.35);
        box-shadow: 0 4px 20px var(--shadow-light);
    }

    .book-list-item:hover::before { opacity: 1; }

    .book-list-cover {
        flex-shrink: 0;
        width: 68px;
        height: 96px;
        object-fit: cover;
        border-radius: 2px;
        border: 1px solid rgba(139,67,20,0.2);
        box-shadow: 2px 2px 6px rgba(0,0,0,0.12);
    }

    .book-list-cover-placeholder {
        flex-shrink: 0;
        width: 68px;
        height: 96px;
        background: linear-gradient(135deg, var(--mahogany) 0%, var(--burgundy) 100%);
        border-radius: 2px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.6rem;
        color: var(--gold-pale);
        opacity: 0.7;
        border: 1px solid rgba(139,67,20,0.2);
    }

    .book-list-body {
        flex: 1;
        min-width: 0;
    }

    .book-list-title {
        font-family: var(--ff-heading);
        font-size: 1.08rem;
        font-weight: 700;
        color: var(--mahogany);
        line-height: 1.25;
        margin-bottom: 5px;
    }

    .book-list-subtitle {
        font-family: var(--ff-sub);
        font-style: italic;
        font-size: 0.88rem;
        color: var(--leather);
        margin-bottom: 6px;
    }

    .book-list-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 14px;
        align-items: center;
        margin-bottom: 10px;
    }

    .book-list-author {
        font-family: var(--ff-body);
        font-size: 0.9rem;
        color: var(--leather);
        font-style: italic;
    }

    .meta-sep {
        color: rgba(139,67,20,0.25);
        font-size: 0.7rem;
    }

    .book-list-year {
        font-family: var(--ff-display);
        font-size: 0.62rem;
        font-weight: 600;
        letter-spacing: 0.15em;
        color: var(--gold);
        text-transform: uppercase;
    }

    .book-list-desc {
        font-family: var(--ff-body);
        font-size: 0.88rem;
        color: rgba(26,14,5,0.6);
        line-height: 1.65;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .book-list-tags {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
        margin-top: 10px;
    }

    .tag {
        padding: 2px 9px;
        border: 1px solid rgba(139,67,20,0.2);
        border-radius: 2px;
        font-family: var(--ff-display);
        font-size: 0.55rem;
        font-weight: 600;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        color: var(--leather);
        background: rgba(139,67,20,0.04);
    }

    .book-list-arrow {
        flex-shrink: 0;
        display: flex;
        align-items: center;
        padding-left: 12px;
        color: rgba(139,67,20,0.25);
        font-size: 1.1rem;
        transition: color 0.2s, transform 0.2s;
    }

    .book-list-item:hover .book-list-arrow {
        color: var(--gold);
        transform: translateX(4px);
    }

    /* ── NO RESULTS ──────────────────────────── */
    .no-results {
        text-align: center;
        padding: 60px 24px;
        border: 1px dashed rgba(139,67,20,0.2);
        border-radius: 4px;
        background: rgba(254,250,242,0.5);
    }

    .no-results-icon {
        font-size: 3rem;
        margin-bottom: 14px;
        filter: grayscale(0.5);
    }

    .no-results h4 {
        font-family: var(--ff-heading);
        font-size: 1.3rem;
        font-style: italic;
        color: var(--mahogany);
        margin-bottom: 10px;
    }

    .no-results p {
        font-family: var(--ff-body);
        font-size: 0.95rem;
        color: rgba(26,14,5,0.5);
    }

    /* ── DUAL COLUMN ─────────────────────────── */
    .dual-results {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 40px;
    }

    @media (max-width: 900px) {
        .dual-results { grid-template-columns: 1fr; }
    }
</style>
@endpush

@section('content')

{{-- ──────────────── PAGE HEADER ──────────────── --}}
<div class="search-page-header">
    <div class="container">
        <div class="search-breadcrumb">
            <a href="{{ route('home') }}">Grand Hall</a>
            <span style="opacity:0.4;margin:0 8px;">›</span>
            Search Results
        </div>

        <h1 class="search-heading">
            Results for <em>"{{ $query }}"</em>
        </h1>
        <p class="search-count">
            @php
                $total = count($googleResults) + count($openLibResults);
            @endphp
            Approximately {{ number_format($totalItems) }} volumes found across the catalogue
        </p>

        <form action="{{ route('books.search') }}" method="GET" class="refine-form" data-search>
            <input type="hidden" name="source" id="sourceInput" value="{{ $source }}">
            <div class="refine-input-group">
                <input type="text" name="q" value="{{ $query }}" placeholder="Refine your search…" />
                <button type="submit">Search</button>
            </div>
            <div class="source-toggle">
                <button type="button" class="source-btn {{ $source === 'all' ? 'active' : '' }}"
                    onclick="setSource('all', this)">All</button>
                <button type="button" class="source-btn {{ $source === 'google' ? 'active' : '' }}"
                    onclick="setSource('google', this)">Google</button>
                <button type="button" class="source-btn {{ $source === 'openlibrary' ? 'active' : '' }}"
                    onclick="setSource('openlibrary', this)">Open Library</button>
            </div>
        </form>
    </div>
</div>

{{-- ──────────────── RESULTS ──────────────── --}}
<div class="container results-wrapper">

    @if ($source === 'all')
        <div class="dual-results">

            {{-- Google Books column --}}
            <div>
                <div class="results-section-title">
                    <h3>
                        <svg class="source-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm-1-13h2v6h-2zm0 8h2v2h-2z"/>
                        </svg>
                        Google Books
                    </h3>
                    <span class="count-badge">{{ count($googleResults) }} results</span>
                </div>

                @if (!empty($googleResults))
                    <div class="book-list">
                        @foreach ($googleResults as $book)
                            @include('books.partials.list-item', ['book' => $book, 'source' => 'google'])
                        @endforeach
                    </div>
                @else
                    <div class="no-results">
                        <div class="no-results-icon">📚</div>
                        <h4>No volumes found</h4>
                        <p>Google Books returned no results for this query.</p>
                    </div>
                @endif
            </div>

            {{-- Open Library column --}}
            <div>
                <div class="results-section-title">
                    <h3>
                        <svg class="source-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/>
                            <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/>
                        </svg>
                        Open Library
                    </h3>
                    <span class="count-badge">{{ count($openLibResults) }} results</span>
                </div>

                @if (!empty($openLibResults))
                    <div class="book-list">
                        @foreach ($openLibResults as $book)
                            @include('books.partials.ol-list-item', ['book' => $book])
                        @endforeach
                    </div>
                @else
                    <div class="no-results">
                        <div class="no-results-icon">📖</div>
                        <h4>No volumes found</h4>
                        <p>Open Library returned no results for this query.</p>
                    </div>
                @endif
            </div>

        </div>

    @elseif ($source === 'google')
        <div class="results-section-title">
            <h3>Google Books Results</h3>
            <span class="count-badge">{{ number_format($totalItems) }} total</span>
        </div>
        @if (!empty($googleResults))
            <div class="book-list">
                @foreach ($googleResults as $book)
                    @include('books.partials.list-item', ['book' => $book, 'source' => 'google'])
                @endforeach
            </div>
        @else
            <div class="no-results"><div class="no-results-icon">📚</div><h4>No volumes found</h4></div>
        @endif

    @elseif ($source === 'openlibrary')
        <div class="results-section-title">
            <h3>Open Library Results</h3>
            <span class="count-badge">{{ number_format($totalItems) }} total</span>
        </div>
        @if (!empty($openLibResults))
            <div class="book-list">
                @foreach ($openLibResults as $book)
                    @include('books.partials.ol-list-item', ['book' => $book])
                @endforeach
            </div>
        @else
            <div class="no-results"><div class="no-results-icon">📖</div><h4>No volumes found</h4></div>
        @endif
    @endif

    {{-- Pagination --}}
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

</div>

@endsection

@push('scripts')
<script>
    function setSource(val, el) {
        document.getElementById('sourceInput').value = val;
        document.querySelectorAll('.source-btn').forEach(b => b.classList.remove('active'));
        el.classList.add('active');
    }
</script>
@endpush
