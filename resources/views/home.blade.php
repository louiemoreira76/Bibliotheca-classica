@extends('layouts.app')

@section('title', 'Grand Hall — Bibliotheca Classica')

@push('styles')
<style>
    /* ── HERO ────────────────────────────────── */
    .home-hero {
        background:
            linear-gradient(180deg, rgba(12,4,2,0.82) 0%, rgba(20,6,6,0.65) 100%),
            url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='200' height='200'%3E%3Crect width='200' height='200' fill='%23180808'/%3E%3Cpath d='M0 0 L200 200 M200 0 L0 200 M100 0 L100 200 M0 100 L200 100' stroke='%23B8860B' stroke-width='0.3' opacity='0.06'/%3E%3Ccircle cx='100' cy='100' r='80' fill='none' stroke='%23B8860B' stroke-width='0.3' opacity='0.05'/%3E%3Ccircle cx='100' cy='100' r='50' fill='none' stroke='%23B8860B' stroke-width='0.2' opacity='0.04'/%3E%3C/svg%3E");
        padding: 100px 24px 80px;
        text-align: center;
        border-bottom: 3px solid var(--gold);
        position: relative;
    }

    .home-hero::before {
        content: '';
        position: absolute;
        inset: 0;
        background: repeating-linear-gradient(
            0deg,
            transparent, transparent 4px,
            rgba(0,0,0,0.04) 4px, rgba(0,0,0,0.04) 5px
        );
        pointer-events: none;
    }

    .home-hero .era-label {
        font-family: var(--ff-display);
        font-size: 0.65rem;
        font-weight: 700;
        letter-spacing: 0.35em;
        text-transform: uppercase;
        color: rgba(184,134,11,0.55);
        margin-bottom: 22px;
    }

    .home-hero h2 {
        font-family: var(--ff-heading);
        font-weight: 900;
        font-size: clamp(2.2rem, 5.5vw, 4.2rem);
        color: var(--parchment);
        line-height: 1.08;
        text-shadow: 0 4px 40px rgba(0,0,0,0.9);
        margin-bottom: 18px;
    }

    .home-hero h2 em {
        display: block;
        font-style: italic;
        color: var(--gold-light);
        font-size: 0.75em;
        font-weight: 400;
    }

    .home-hero .lead {
        font-family: var(--ff-accent);
        font-style: italic;
        font-size: clamp(1rem, 1.6vw, 1.18rem);
        color: rgba(242,232,213,0.7);
        max-width: 580px;
        margin: 0 auto 42px;
        line-height: 1.85;
    }

    /* ── MAIN SEARCH ─────────────────────────── */
    .hero-search-form {
        max-width: 640px;
        margin: 0 auto;
    }

    .search-row {
        display: flex;
        border: 2px solid var(--gold);
        border-radius: 4px;
        overflow: hidden;
        box-shadow: 0 6px 40px rgba(0,0,0,0.6), 0 0 0 1px rgba(184,134,11,0.15);
        margin-bottom: 14px;
    }

    .search-row input {
        flex: 1;
        background: rgba(242,232,213,0.06);
        border: none;
        padding: 18px 22px;
        font-family: var(--ff-body);
        font-size: 1.08rem;
        color: var(--parchment);
        outline: none;
    }

    .search-row input::placeholder {
        color: rgba(242,232,213,0.3);
        font-style: italic;
    }

    .search-row button {
        background: var(--gold);
        border: none;
        padding: 18px 32px;
        font-family: var(--ff-display);
        font-size: 0.72rem;
        font-weight: 700;
        letter-spacing: 0.2em;
        text-transform: uppercase;
        color: var(--mahogany);
        cursor: pointer;
        transition: background 0.2s;
    }

    .search-row button:hover {
        background: var(--gold-light);
    }

    .search-options {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }

    .search-options label {
        font-family: var(--ff-display);
        font-size: 0.62rem;
        font-weight: 600;
        letter-spacing: 0.15em;
        text-transform: uppercase;
        color: rgba(242,232,213,0.45);
    }

    .source-pill {
        display: inline-flex;
        align-items: center;
        padding: 5px 14px;
        border: 1px solid rgba(184,134,11,0.35);
        border-radius: 20px;
        background: rgba(184,134,11,0.06);
        color: rgba(242,232,213,0.6);
        font-family: var(--ff-display);
        font-size: 0.6rem;
        font-weight: 600;
        letter-spacing: 0.12em;
        text-transform: uppercase;
        cursor: pointer;
        transition: all 0.2s;
    }

    .source-pill input {
        display: none;
    }

    .source-pill:has(input:checked),
    .source-pill.active {
        background: var(--gold);
        color: var(--mahogany);
        border-color: var(--gold);
    }

    /* ── QUICK SUBJECT TAGS ───────────────────── */
    .quick-tags {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 8px;
        margin-top: 28px;
    }

    .quick-tag {
        padding: 5px 15px;
        border: 1px solid rgba(184,134,11,0.25);
        border-radius: 2px;
        background: rgba(26,8,8,0.6);
        color: rgba(242,232,213,0.55);
        font-family: var(--ff-display);
        font-size: 0.6rem;
        font-weight: 600;
        letter-spacing: 0.15em;
        text-transform: uppercase;
        text-decoration: none;
        transition: all 0.2s;
    }

    .quick-tag:hover {
        background: rgba(184,134,11,0.15);
        color: var(--gold-light);
        border-color: var(--gold);
    }

    /* ── SECTION HEADER ──────────────────────── */
    .collection-header {
        display: flex;
        align-items: center;
        gap: 20px;
        margin-bottom: 28px;
        padding: 0 0 16px;
        border-bottom: 1px solid rgba(139,67,20,0.2);
    }

    .collection-header .col-label {
        flex-shrink: 0;
    }

    .collection-label {
        font-family: var(--ff-display);
        font-size: 0.65rem;
        font-weight: 700;
        letter-spacing: 0.25em;
        text-transform: uppercase;
        color: var(--gold);
        margin-bottom: 3px;
    }

    .collection-title {
        font-family: var(--ff-heading);
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--mahogany);
        line-height: 1.1;
    }

    .collection-header .browse-link {
        margin-left: auto;
        font-family: var(--ff-display);
        font-size: 0.62rem;
        font-weight: 600;
        letter-spacing: 0.15em;
        text-transform: uppercase;
        color: var(--leather);
        text-decoration: none;
        border-bottom: 1px solid rgba(123,63,32,0.3);
        padding-bottom: 2px;
        transition: color 0.2s, border-color 0.2s;
        white-space: nowrap;
    }

    .collection-header .browse-link:hover {
        color: var(--burgundy);
        border-color: var(--burgundy);
    }

    /* ── COLLECTIONS ─────────────────────────── */
    .collection-row {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 22px;
    }

    @media (max-width: 900px) { .collection-row { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 480px) { .collection-row { grid-template-columns: repeat(2, 1fr); gap: 14px; } }

    /* ── INTRO PANEL ─────────────────────────── */
    .intro-panel {
        background: linear-gradient(135deg, var(--mahogany) 0%, #1C0A00 100%);
        border: 1px solid rgba(184,134,11,0.25);
        border-radius: 4px;
        padding: 48px;
        text-align: center;
        position: relative;
        overflow: hidden;
    }

    .intro-panel::before {
        content: '';
        position: absolute;
        inset: 12px;
        border: 1px solid rgba(184,134,11,0.15);
        border-radius: 3px;
        pointer-events: none;
    }

    .intro-panel::after {
        content: '';
        position: absolute;
        inset: 18px;
        border: 1px solid rgba(184,134,11,0.07);
        border-radius: 2px;
        pointer-events: none;
    }

    .intro-panel h3 {
        font-family: var(--ff-heading);
        font-size: 1.6rem;
        font-style: italic;
        color: var(--gold-light);
        margin-bottom: 14px;
        position: relative;
    }

    .intro-panel p {
        font-family: var(--ff-body);
        font-size: 1rem;
        color: rgba(242,232,213,0.65);
        max-width: 560px;
        margin: 0 auto;
        line-height: 1.85;
        position: relative;
    }

    /* ── FAMOUS QUOTES BAND ──────────────────── */
    .quotes-band {
        background: linear-gradient(90deg, var(--mahogany) 0%, #1C0808 50%, var(--mahogany) 100%);
        border-top: 1px solid rgba(184,134,11,0.25);
        border-bottom: 1px solid rgba(184,134,11,0.25);
        padding: 32px 24px;
        text-align: center;
        overflow: hidden;
    }

    .quote-text {
        font-family: var(--ff-heading);
        font-style: italic;
        font-size: clamp(1rem, 2vw, 1.3rem);
        color: var(--parchment);
        opacity: 0.75;
        max-width: 680px;
        margin: 0 auto 10px;
        line-height: 1.6;
    }

    .quote-attr {
        font-family: var(--ff-display);
        font-size: 0.62rem;
        font-weight: 600;
        letter-spacing: 0.2em;
        text-transform: uppercase;
        color: var(--gold);
        opacity: 0.6;
    }
</style>
@endpush

@section('content')

{{-- ──────────────── HERO ──────────────── --}}
<div class="home-hero">
    <p class="era-label">Birmingham · Grand Reading Room · Est. MDCCCXLV</p>

    <h2 class="fade-in">
        A Sanctuary of
        <em>Classical Literature</em>
    </h2>

    <p class="lead fade-in fade-in-2">
        Explore ten thousand years of literary achievement through our curated digital catalogue,
        drawing upon the great collections of Europe's finest libraries.
    </p>

    <div class="hero-search-form fade-in fade-in-3">
        <form action="{{ route('books.search') }}" method="GET">
            <input type="hidden" name="source" value="all" id="selectedSource">
            <div class="search-row">
                <input type="text" name="q" placeholder="Search by title, author, or subject…" autocomplete="off" />
                <button type="submit">Search Catalogue</button>
            </div>
            <div class="search-options">
                <span class="search-options-label" style="font-family:var(--ff-display);font-size:0.6rem;letter-spacing:0.15em;text-transform:uppercase;color:rgba(242,232,213,0.35);">Sources:</span>

                <label class="source-pill active" onclick="setSource('all', this)">
                    All Sources
                </label>
                <label class="source-pill" onclick="setSource('google', this)">
                    Google Books
                </label>
                <label class="source-pill" onclick="setSource('openlibrary', this)">
                    Open Library
                </label>
            </div>
        </form>

        <div class="quick-tags">
            <a href="{{ route('books.search', ['q' => 'shakespeare']) }}" class="quick-tag">Shakespeare</a>
            <a href="{{ route('books.search', ['q' => 'jane austen']) }}" class="quick-tag">Jane Austen</a>
            <a href="{{ route('books.search', ['q' => 'charles dickens']) }}" class="quick-tag">Dickens</a>
            <a href="{{ route('books.search', ['q' => 'homer iliad']) }}" class="quick-tag">Homer</a>
            <a href="{{ route('books.search', ['q' => 'dostoevsky']) }}" class="quick-tag">Dostoevsky</a>
            <a href="{{ route('books.search', ['q' => 'cervantes don quixote']) }}" class="quick-tag">Cervantes</a>
            <a href="{{ route('books.search', ['q' => 'dante divine comedy']) }}" class="quick-tag">Dante</a>
            <a href="{{ route('books.search', ['q' => 'virginia woolf']) }}" class="quick-tag">Virginia Woolf</a>
        </div>
    </div>
</div>

{{-- ──────────────── QUOTES BAND ──────────────── --}}
<div class="quotes-band">
    <p class="quote-text" id="rotatingQuote">"A reader lives a thousand lives before he dies. The man who never reads lives only one."</p>
    <p class="quote-attr" id="rotatingAuthor">— George R.R. Martin</p>
</div>

{{-- ──────────────── FEATURED COLLECTIONS ──────────────── --}}
<div class="container">
    @foreach ($featured as $categoryName => $books)
    <div class="section-block fade-in">
        <div class="collection-header">
            <div class="col-label">
                <div class="collection-label">Featured Collection</div>
                <h2 class="collection-title">{{ $categoryName }}</h2>
            </div>
            <a href="{{ route('books.search', ['q' => strtolower($categoryName)]) }}" class="browse-link">Browse All →</a>
        </div>

        <div class="collection-row">
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
    </div>
    @endforeach

    {{-- Intro panel --}}
    <div class="section-block">
        <div class="intro-panel">
            <div class="divider-ornate">✦ ✦ ✦</div>
            <h3>A Living Library Without Walls</h3>
            <p>
                Our catalogue draws upon Google Books and the Open Library to provide scholars,
                students, and lovers of literature with the most comprehensive access to classical
                works in the European and world traditions. Every volume is presented with the
                reverence it deserves.
            </p>
            <div class="divider-ornate" style="margin-top:24px;">✦ ✦ ✦</div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // Source selector
    function setSource(src, el) {
        document.getElementById('selectedSource').value = src;
        document.querySelectorAll('.source-pill').forEach(p => p.classList.remove('active'));
        el.classList.add('active');
    }

    // Rotating quotes
    const quotes = [
        { text: '"A reader lives a thousand lives before he dies. The man who never reads lives only one."', attr: '— George R.R. Martin' },
        { text: '"Not all those who wander are lost."', attr: '— J.R.R. Tolkien' },
        { text: '"It is a truth universally acknowledged, that a great library is the highest form of civilization."', attr: '— after Jane Austen' },
        { text: '"Books are a uniquely portable magic."', attr: '— Stephen King' },
        { text: '"The world was made for reading, and reading was made for the soul."', attr: '— after Ecclesiastes' },
    ];

    let qi = 0;
    const qEl = document.getElementById('rotatingQuote');
    const aEl = document.getElementById('rotatingAuthor');

    setInterval(() => {
        qEl.style.opacity = '0';
        aEl.style.opacity = '0';
        setTimeout(() => {
            qi = (qi + 1) % quotes.length;
            qEl.textContent = quotes[qi].text;
            aEl.textContent = quotes[qi].attr;
            qEl.style.opacity = '0.75';
            aEl.style.opacity = '0.6';
        }, 500);
    }, 7000);

    qEl.style.transition = 'opacity 0.5s';
    aEl.style.transition = 'opacity 0.5s';
</script>
@endpush
