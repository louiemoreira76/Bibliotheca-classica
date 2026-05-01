@extends('layouts.app')

@section('title', ($book['title'] ?? 'Book Detail') . ' — Bibliotheca Classica')
@section('meta_description', Str::limit(strip_tags($book['description'] ?? ''), 160))

@push('styles')
<style>
    /* ── DETAIL PAGE ─────────────────────────── */
    .book-detail-hero {
        background: linear-gradient(180deg, #080202 0%, #150606 60%, #1C0808 100%);
        border-bottom: 2px solid rgba(184,134,11,0.4);
        padding: 48px 0 0;
        position: relative;
        overflow: hidden;
    }

    .book-detail-hero::before {
        content: '';
        position: absolute;
        inset: 0;
        background:
            repeating-linear-gradient(90deg, transparent, transparent 100px, rgba(184,134,11,0.025) 100px, rgba(184,134,11,0.025) 101px),
            repeating-linear-gradient(0deg, transparent, transparent 100px, rgba(184,134,11,0.025) 100px, rgba(184,134,11,0.025) 101px);
        pointer-events: none;
    }

    .book-detail-inner {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 24px;
        display: grid;
        grid-template-columns: 220px 1fr;
        gap: 48px;
        position: relative;
        z-index: 1;
    }

    /* ── COVER COLUMN ────────────────────────── */
    .cover-col {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 16px;
        padding-bottom: 40px;
    }

    .book-cover-frame {
        width: 100%;
        max-width: 200px;
        position: relative;
    }

    .book-cover-frame::before {
        content: '';
        position: absolute;
        inset: -8px -6px -12px 6px;
        background: linear-gradient(180deg, #3D1212 0%, #1C0808 100%);
        border-radius: 3px;
        z-index: -1;
    }

    .book-cover-frame::after {
        content: '';
        position: absolute;
        inset: -4px -3px -8px 3px;
        background: linear-gradient(180deg, #5A1818 0%, #2C0E0E 100%);
        border-radius: 2px;
        z-index: -1;
        opacity: 0.6;
    }

    .detail-cover-img {
        width: 100%;
        display: block;
        border-radius: 2px;
        box-shadow: -4px 4px 20px rgba(0,0,0,0.6), 0 0 0 1px rgba(184,134,11,0.3);
    }

    .cover-placeholder-large {
        width: 100%;
        aspect-ratio: 2/3;
        background: linear-gradient(135deg, #2C0E0E 0%, #4A1515 50%, #6B1A1A 100%);
        border-radius: 2px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 24px;
        box-shadow: -4px 4px 20px rgba(0,0,0,0.6);
        position: relative;
        overflow: hidden;
    }

    .cover-placeholder-large::before {
        content: '';
        position: absolute;
        inset: 10px;
        border: 1px solid rgba(184,134,11,0.3);
        border-radius: 1px;
    }

    .cover-placeholder-large .pl-title {
        font-family: var(--ff-heading);
        font-weight: 700;
        font-size: 0.85rem;
        color: var(--gold-pale);
        text-align: center;
        line-height: 1.4;
        position: relative;
    }

    .cover-placeholder-large .pl-icon {
        font-size: 3rem;
        margin-bottom: 12px;
    }

    .cover-action-btns {
        display: flex;
        flex-direction: column;
        gap: 8px;
        width: 100%;
        max-width: 200px;
    }

    .action-btn {
        display: block;
        text-align: center;
        padding: 10px 16px;
        border-radius: 3px;
        font-family: var(--ff-display);
        font-size: 0.62rem;
        font-weight: 700;
        letter-spacing: 0.15em;
        text-transform: uppercase;
        text-decoration: none;
        transition: all 0.2s;
    }

    .action-btn-primary {
        background: var(--gold);
        color: var(--mahogany);
        border: 2px solid var(--gold);
    }

    .action-btn-primary:hover { background: var(--gold-light); border-color: var(--gold-light); }

    .action-btn-outline {
        background: transparent;
        color: var(--parchment-dark);
        border: 1px solid rgba(184,134,11,0.4);
    }

    .action-btn-outline:hover {
        background: rgba(184,134,11,0.1);
        border-color: var(--gold);
        color: var(--gold-light);
    }

    /* ── INFO COLUMN ─────────────────────────── */
    .info-col {
        padding-bottom: 48px;
    }

    .book-breadcrumb {
        font-family: var(--ff-display);
        font-size: 0.58rem;
        font-weight: 600;
        letter-spacing: 0.2em;
        text-transform: uppercase;
        color: rgba(184,134,11,0.4);
        margin-bottom: 20px;
    }

    .book-breadcrumb a { color: inherit; text-decoration: none; }
    .book-breadcrumb a:hover { color: var(--gold); }

    .book-categories {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
        margin-bottom: 18px;
    }

    .detail-title {
        font-family: var(--ff-heading);
        font-weight: 900;
        font-size: clamp(1.8rem, 4vw, 3rem);
        color: var(--parchment);
        line-height: 1.08;
        margin-bottom: 10px;
        text-shadow: 0 2px 20px rgba(0,0,0,0.5);
    }

    .detail-subtitle {
        font-family: var(--ff-heading);
        font-style: italic;
        font-weight: 400;
        font-size: clamp(1rem, 2vw, 1.4rem);
        color: rgba(242,232,213,0.55);
        margin-bottom: 18px;
    }

    .detail-authors {
        font-family: var(--ff-sub);
        font-style: italic;
        font-size: 1.1rem;
        color: var(--gold-light);
        margin-bottom: 22px;
    }

    .detail-authors a {
        color: inherit;
        text-decoration: none;
        border-bottom: 1px solid rgba(212,166,50,0.3);
    }

    .detail-authors a:hover { border-color: var(--gold-light); }

    .detail-meta-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        gap: 14px 24px;
        margin-bottom: 28px;
        padding: 20px;
        border: 1px solid rgba(184,134,11,0.15);
        border-radius: 3px;
        background: rgba(184,134,11,0.04);
    }

    .meta-item {
        display: flex;
        flex-direction: column;
        gap: 3px;
    }

    .meta-label {
        font-family: var(--ff-display);
        font-size: 0.55rem;
        font-weight: 700;
        letter-spacing: 0.2em;
        text-transform: uppercase;
        color: rgba(184,134,11,0.5);
    }

    .meta-value {
        font-family: var(--ff-body);
        font-size: 0.92rem;
        color: var(--parchment-dark);
        line-height: 1.3;
    }

    .detail-rating {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 20px;
    }

    .rating-stars {
        color: var(--gold);
        font-size: 1rem;
        letter-spacing: 3px;
    }

    .rating-count {
        font-family: var(--ff-body);
        font-size: 0.85rem;
        color: rgba(242,232,213,0.4);
        font-style: italic;
    }

    /* ── BODY TABS ─────────────────────────── */
    .book-body {
        background: var(--cream);
        min-height: 400px;
    }

    .detail-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 48px 24px;
        display: grid;
        grid-template-columns: 1fr 340px;
        gap: 48px;
    }

    /* ── DESCRIPTION ─────────────────────────── */
    .description-section h3 {
        font-family: var(--ff-heading);
        font-size: 1.3rem;
        font-weight: 700;
        color: var(--mahogany);
        margin-bottom: 16px;
        padding-bottom: 12px;
        border-bottom: 1px solid rgba(139,67,20,0.15);
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .description-section h3::before {
        content: '✦';
        font-size: 0.7rem;
        color: var(--gold);
    }

    .book-description {
        font-family: var(--ff-body);
        font-size: 1.02rem;
        color: rgba(26,14,5,0.78);
        line-height: 1.9;
        margin-bottom: 28px;
    }

    .book-description p + p { margin-top: 16px; }

    /* ── OPEN LIBRARY ENRICHMENT ─────────────── */
    .enrichment-block {
        background: #FEFAF2;
        border: 1px solid rgba(139,67,20,0.15);
        border-radius: 3px;
        padding: 24px;
        margin-top: 28px;
    }

    .enrichment-block h4 {
        font-family: var(--ff-display);
        font-size: 0.65rem;
        font-weight: 700;
        letter-spacing: 0.2em;
        text-transform: uppercase;
        color: var(--gold);
        margin-bottom: 14px;
    }

    .subject-cloud {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
    }

    .subject-pill {
        padding: 4px 12px;
        background: rgba(44,14,14,0.07);
        border: 1px solid rgba(139,67,20,0.2);
        border-radius: 20px;
        font-family: var(--ff-body);
        font-size: 0.8rem;
        color: var(--leather);
        text-decoration: none;
        transition: all 0.2s;
    }

    .subject-pill:hover {
        background: var(--mahogany);
        color: var(--gold-pale);
        border-color: var(--mahogany);
    }

    /* ── SIDEBAR ─────────────────────────────── */
    .detail-sidebar {}

    .sidebar-card {
        background: #FEFAF2;
        border: 1px solid rgba(139,67,20,0.15);
        border-radius: 3px;
        padding: 22px;
        margin-bottom: 22px;
    }

    .sidebar-card h4 {
        font-family: var(--ff-display);
        font-size: 0.63rem;
        font-weight: 700;
        letter-spacing: 0.2em;
        text-transform: uppercase;
        color: var(--gold);
        margin-bottom: 16px;
        padding-bottom: 10px;
        border-bottom: 1px solid rgba(184,134,11,0.15);
    }

    .sidebar-item {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        padding: 8px 0;
        border-bottom: 1px solid rgba(139,67,20,0.08);
        gap: 12px;
    }

    .sidebar-item:last-child { border-bottom: none; }

    .sidebar-item-label {
        font-family: var(--ff-display);
        font-size: 0.58rem;
        font-weight: 600;
        letter-spacing: 0.12em;
        text-transform: uppercase;
        color: rgba(26,14,5,0.35);
        white-space: nowrap;
    }

    .sidebar-item-value {
        font-family: var(--ff-body);
        font-size: 0.88rem;
        color: rgba(26,14,5,0.7);
        text-align: right;
    }

    .isbn-badge {
        font-family: 'Courier New', monospace;
        font-size: 0.82rem;
        letter-spacing: 0.05em;
        background: rgba(44,14,14,0.06);
        padding: 2px 8px;
        border-radius: 2px;
        color: var(--mahogany);
    }

    /* ── RELATED BOOKS ───────────────────────── */
    .related-section {
        border-top: 2px solid rgba(139,67,20,0.12);
        background: #F5EDD6;
        padding: 48px 0;
    }

    .related-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
        gap: 20px;
        margin-top: 28px;
    }

    /* ── RESPONSIVE ──────────────────────────── */
    @media (max-width: 900px) {
        .book-detail-inner { grid-template-columns: 1fr; }
        .cover-col { flex-direction: row; align-items: flex-start; padding-bottom: 0; }
        .book-cover-frame { max-width: 140px; }
        .cover-action-btns { flex-direction: row; max-width: none; }
        .detail-container { grid-template-columns: 1fr; }
        .detail-sidebar { order: -1; }
    }

    @media (max-width: 600px) {
        .detail-meta-grid { grid-template-columns: 1fr 1fr; }
        .related-grid { grid-template-columns: repeat(3, 1fr); }
    }
</style>
@endpush

@section('content')

{{-- ──────────────── HERO SECTION ──────────────── --}}
<div class="book-detail-hero">
    <div class="book-detail-inner">

        {{-- Cover column --}}
        <div class="cover-col">
            <div class="book-cover-frame">
                @if (!empty($book['thumbnail']))
                    <img class="detail-cover-img" src="{{ $book['thumbnail'] }}" alt="{{ $book['title'] }}">
                @else
                    <div class="cover-placeholder-large">
                        <div class="pl-icon">📖</div>
                        <div class="pl-title">{{ Str::limit($book['title'], 50) }}</div>
                    </div>
                @endif
            </div>

            <div class="cover-action-btns">
                @if (!empty($book['previewLink']))
                    <a href="{{ $book['previewLink'] }}" target="_blank" rel="noopener" class="action-btn action-btn-primary">
                        Preview Book
                    </a>
                @endif
                @if (!empty($book['infoLink']))
                    <a href="{{ $book['infoLink'] }}" target="_blank" rel="noopener" class="action-btn action-btn-outline">
                        View on Google Books
                    </a>
                @endif
                @if (!empty($workDetails['key']))
                    <a href="https://openlibrary.org{{ $workDetails['key'] }}" target="_blank" rel="noopener" class="action-btn action-btn-outline">
                        Open Library Entry
                    </a>
                @endif
                @if (!empty($book['buyLink']))
                    <a href="{{ $book['buyLink'] }}" target="_blank" rel="noopener" class="action-btn action-btn-outline">
                        Purchase Volume
                    </a>
                @endif
            </div>
        </div>

        {{-- Info column --}}
        <div class="info-col">
            <div class="book-breadcrumb">
                <a href="{{ route('home') }}">Grand Hall</a>
                <span style="opacity:.4;margin:0 8px;">›</span>
                <a href="{{ route('books.search', ['q' => implode(', ', array_slice($book['authors'] ?? [], 0, 1))]) }}">{{ implode(', ', array_slice($book['authors'] ?? ['Unknown Author'], 0, 1)) }}</a>
                <span style="opacity:.4;margin:0 8px;">›</span>
                {{ Str::limit($book['title'], 40) }}
            </div>

            @if (!empty($book['categories']))
                <div class="book-categories">
                    @foreach (array_slice($book['categories'], 0, 3) as $cat)
                        <span class="badge">{{ $cat }}</span>
                    @endforeach
                </div>
            @endif

            <h1 class="detail-title">{{ $book['title'] }}</h1>

            @if (!empty($book['subtitle']))
                <p class="detail-subtitle">{{ $book['subtitle'] }}</p>
            @endif

            <p class="detail-authors">
                By
                @foreach (($book['authors'] ?? ['Unknown Author']) as $i => $author)
                    @if ($i > 0), @endif
                    <a href="{{ route('books.search', ['q' => "inauthor:\"{$author}\""]) }}">{{ $author }}</a>
                @endforeach
            </p>

            @if (!empty($book['averageRating']))
                <div class="detail-rating">
                    <span class="rating-stars">
                        @for ($s = 1; $s <= 5; $s++)
                            {{ $s <= round($book['averageRating']) ? '★' : '☆' }}
                        @endfor
                    </span>
                    <span class="rating-count">
                        {{ number_format($book['averageRating'], 1) }} / 5
                        @if (!empty($book['ratingsCount']))
                            ({{ number_format($book['ratingsCount']) }} ratings)
                        @endif
                    </span>
                </div>
            @endif

            <div class="detail-meta-grid">
                @if (!empty($book['publisher']))
                    <div class="meta-item">
                        <span class="meta-label">Publisher</span>
                        <span class="meta-value">{{ $book['publisher'] }}</span>
                    </div>
                @endif
                @if (!empty($book['publishedDate']))
                    <div class="meta-item">
                        <span class="meta-label">Published</span>
                        <span class="meta-value">{{ $book['publishedDate'] }}</span>
                    </div>
                @endif
                @if (!empty($book['pageCount']))
                    <div class="meta-item">
                        <span class="meta-label">Pages</span>
                        <span class="meta-value">{{ number_format($book['pageCount']) }}</span>
                    </div>
                @endif
                @if (!empty($book['language']))
                    <div class="meta-item">
                        <span class="meta-label">Language</span>
                        <span class="meta-value">{{ strtoupper($book['language']) }}</span>
                    </div>
                @endif
                @if (!empty($book['isbn13']))
                    <div class="meta-item">
                        <span class="meta-label">ISBN-13</span>
                        <span class="meta-value">{{ $book['isbn13'] }}</span>
                    </div>
                @endif
                @if (!empty($book['maturityRating']))
                    <div class="meta-item">
                        <span class="meta-label">Rating</span>
                        <span class="meta-value">{{ str_replace('_', ' ', $book['maturityRating']) }}</span>
                    </div>
                @endif
                @if (!empty($book['isEbook']))
                    <div class="meta-item">
                        <span class="meta-label">Format</span>
                        <span class="meta-value">e-Book Available</span>
                    </div>
                @endif
                @if (!empty($workDetails['firstPublishDate']))
                    <div class="meta-item">
                        <span class="meta-label">First Published</span>
                        <span class="meta-value">{{ $workDetails['firstPublishDate'] }}</span>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- ──────────────── BODY ──────────────── --}}
<div class="book-body">
    <div class="detail-container container">

        {{-- Main body --}}
        <div class="description-section">

            @if (!empty($book['description']))
                <h3>Synopsis</h3>
                <div class="book-description">
                    @php
                        $desc = strip_tags($book['description']);
                        $paragraphs = explode("\n", $desc);
                    @endphp
                    @foreach (array_filter($paragraphs) as $para)
                        <p>{{ $para }}</p>
                    @endforeach
                </div>
            @endif

            @if (!empty($workDetails['description']))
                <h3>Open Library Synopsis</h3>
                <div class="book-description">
                    <p>{{ $workDetails['description'] }}</p>
                </div>
            @endif

            @if (!empty($workDetails['subjects']))
                <div class="enrichment-block">
                    <h4>Subjects & Themes</h4>
                    <div class="subject-cloud">
                        @foreach (array_slice($workDetails['subjects'], 0, 20) as $subj)
                            <a href="{{ route('books.search', ['q' => $subj]) }}" class="subject-pill">{{ $subj }}</a>
                        @endforeach
                    </div>
                </div>
            @endif

            @if (!empty($workDetails['subjectPeople']) || !empty($workDetails['subjectPlaces']) || !empty($workDetails['subjectTimes']))
                <div class="enrichment-block" style="margin-top:16px;">
                    @if (!empty($workDetails['subjectPeople']))
                        <h4>Characters & People</h4>
                        <div class="subject-cloud" style="margin-bottom:14px;">
                            @foreach (array_slice($workDetails['subjectPeople'], 0, 10) as $person)
                                <span class="subject-pill" style="cursor:default;">{{ $person }}</span>
                            @endforeach
                        </div>
                    @endif

                    @if (!empty($workDetails['subjectPlaces']))
                        <h4>Locations</h4>
                        <div class="subject-cloud" style="margin-bottom:14px;">
                            @foreach (array_slice($workDetails['subjectPlaces'], 0, 10) as $place)
                                <span class="subject-pill" style="cursor:default;">{{ $place }}</span>
                            @endforeach
                        </div>
                    @endif

                    @if (!empty($workDetails['subjectTimes']))
                        <h4>Time Periods</h4>
                        <div class="subject-cloud">
                            @foreach ($workDetails['subjectTimes'] as $time)
                                <span class="subject-pill" style="cursor:default;">{{ $time }}</span>
                            @endforeach
                        </div>
                    @endif
                </div>
            @endif

            @if (!empty($workDetails['links']))
                <div class="enrichment-block" style="margin-top:16px;">
                    <h4>External References</h4>
                    @foreach ($workDetails['links'] as $link)
                        @if (!empty($link['url']))
                            <a href="{{ $link['url'] }}" target="_blank" rel="noopener"
                               style="display:block;font-family:var(--ff-body);font-size:0.88rem;color:var(--leather);text-decoration:none;padding:5px 0;border-bottom:1px dashed rgba(139,67,20,0.15);">
                                {{ $link['title'] ?: $link['url'] }} →
                            </a>
                        @endif
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Sidebar --}}
        <div class="detail-sidebar">

            {{-- Bibliographic details --}}
            <div class="sidebar-card">
                <h4>Bibliographic Details</h4>

                @if (!empty($book['publisher']))
                    <div class="sidebar-item">
                        <span class="sidebar-item-label">Publisher</span>
                        <span class="sidebar-item-value">{{ $book['publisher'] }}</span>
                    </div>
                @endif
                @if (!empty($book['publishedDate']))
                    <div class="sidebar-item">
                        <span class="sidebar-item-label">Published</span>
                        <span class="sidebar-item-value">{{ $book['publishedDate'] }}</span>
                    </div>
                @endif
                @if (!empty($book['pageCount']))
                    <div class="sidebar-item">
                        <span class="sidebar-item-label">Pages</span>
                        <span class="sidebar-item-value">{{ number_format($book['pageCount']) }}</span>
                    </div>
                @endif
                @if (!empty($book['language']))
                    <div class="sidebar-item">
                        <span class="sidebar-item-label">Language</span>
                        <span class="sidebar-item-value">{{ strtoupper($book['language']) }}</span>
                    </div>
                @endif
                @if (!empty($book['isbn13']))
                    <div class="sidebar-item">
                        <span class="sidebar-item-label">ISBN-13</span>
                        <span class="sidebar-item-value"><span class="isbn-badge">{{ $book['isbn13'] }}</span></span>
                    </div>
                @endif
                @if (!empty($book['isbn10']))
                    <div class="sidebar-item">
                        <span class="sidebar-item-label">ISBN-10</span>
                        <span class="sidebar-item-value"><span class="isbn-badge">{{ $book['isbn10'] }}</span></span>
                    </div>
                @endif
                @if (!empty($openLibData['editions']))
                    <div class="sidebar-item">
                        <span class="sidebar-item-label">Editions</span>
                        <span class="sidebar-item-value">{{ number_format($openLibData['editions']) }}</span>
                    </div>
                @endif
            </div>

            {{-- Availability --}}
            @if (!empty($book['isEbook']) || !empty($book['isPdf']) || !empty($book['previewLink']))
                <div class="sidebar-card">
                    <h4>Digital Availability</h4>

                    @if (!empty($book['previewLink']))
                        <div class="sidebar-item">
                            <span class="sidebar-item-label">Preview</span>
                            <a href="{{ $book['previewLink'] }}" target="_blank" rel="noopener"
                               style="font-family:var(--ff-body);font-size:0.88rem;color:var(--leather);">View Preview →</a>
                        </div>
                    @endif
                    @if (!empty($book['isEbook']))
                        <div class="sidebar-item">
                            <span class="sidebar-item-label">E-Book</span>
                            <span class="sidebar-item-value" style="color:var(--sage-green);">Available</span>
                        </div>
                    @endif
                    @if (!empty($book['isPdf']))
                        <div class="sidebar-item">
                            <span class="sidebar-item-label">PDF</span>
                            <span class="sidebar-item-value" style="color:var(--sage-green);">Available</span>
                        </div>
                    @endif
                    @if (!empty($book['publicDomain']))
                        <div class="sidebar-item">
                            <span class="sidebar-item-label">Domain</span>
                            <span class="sidebar-item-value" style="color:var(--sage-green);">Public Domain</span>
                        </div>
                    @endif
                </div>
            @endif

            {{-- Share --}}
            <div class="sidebar-card">
                <h4>Share This Volume</h4>
                <div style="display:flex;gap:8px;flex-wrap:wrap;">
                    <button onclick="copyToClipboard()" class="action-btn action-btn-outline" style="flex:1;cursor:pointer;">
                        Copy Link
                    </button>
                </div>
                <p id="copyMsg" style="display:none;font-family:var(--ff-body);font-size:0.8rem;color:var(--sage-green);margin-top:8px;text-align:center;">
                    Link copied to clipboard!
                </p>
            </div>

        </div>
    </div>
</div>

{{-- ──────────────── RELATED BOOKS ──────────────── --}}
@if (!empty($relatedBooks))
<div class="related-section">
    <div class="container">
        <div class="ornament" style="max-width:500px;margin:0 auto 8px;color:var(--gold);">✦</div>
        <h2 class="section-title">More Works by the Author</h2>
        <p class="section-subtitle">Explore further volumes from this author in our catalogue</p>

        <div class="related-grid">
            @foreach (array_slice(array_values($relatedBooks), 0, 6) as $related)
                <a href="{{ route('books.show', ['id' => $related['id']]) }}" class="book-card">
                    @if (!empty($related['thumbnail']))
                        <img class="book-cover" src="{{ $related['thumbnail'] }}" alt="{{ $related['title'] }}" loading="lazy">
                    @else
                        <div class="book-cover-placeholder">
                            <span class="placeholder-icon">📖</span>
                            <span class="placeholder-title">{{ Str::limit($related['title'], 30) }}</span>
                        </div>
                    @endif
                    <div class="book-info">
                        <div class="book-title">{{ Str::limit($related['title'], 40) }}</div>
                        @if (!empty($related['publishedDate']))
                            <div class="book-year">{{ substr($related['publishedDate'], 0, 4) }}</div>
                        @endif
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</div>
@endif

@endsection

@push('scripts')
<script>
    function copyToClipboard() {
        navigator.clipboard.writeText(window.location.href).then(() => {
            const msg = document.getElementById('copyMsg');
            msg.style.display = 'block';
            setTimeout(() => msg.style.display = 'none', 2500);
        });
    }
</script>
@endpush
