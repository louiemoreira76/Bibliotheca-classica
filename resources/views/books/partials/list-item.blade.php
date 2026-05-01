<a href="{{ route('books.show', ['id' => $book['id'], 'source' => $source]) }}" class="book-list-item">
    @if (!empty($book['thumbnail']))
        <img class="book-list-cover" src="{{ $book['thumbnail'] }}" alt="{{ $book['title'] }}" loading="lazy">
    @else
        <div class="book-list-cover-placeholder">📖</div>
    @endif

    <div class="book-list-body">
        <div class="book-list-title">{{ $book['title'] }}</div>

        @if (!empty($book['subtitle']))
            <div class="book-list-subtitle">{{ Str::limit($book['subtitle'], 80) }}</div>
        @endif

        <div class="book-list-meta">
            <span class="book-list-author">{{ implode(', ', array_slice($book['authors'] ?? ['Unknown Author'], 0, 3)) }}</span>

            @if (!empty($book['publishedDate']))
                <span class="meta-sep">·</span>
                <span class="book-list-year">{{ substr($book['publishedDate'], 0, 4) }}</span>
            @endif

            @if (!empty($book['pageCount']))
                <span class="meta-sep">·</span>
                <span class="book-list-year">{{ number_format($book['pageCount']) }} pp.</span>
            @endif

            @if (!empty($book['averageRating']))
                <span class="meta-sep">·</span>
                <span class="stars">{{ str_repeat('★', (int)$book['averageRating']) }}{{ str_repeat('☆', 5 - (int)$book['averageRating']) }}</span>
            @endif
        </div>

        @if (!empty($book['description']))
            <p class="book-list-desc">{{ strip_tags($book['description']) }}</p>
        @endif

        <div class="book-list-tags">
            @foreach (array_slice($book['categories'] ?? [], 0, 3) as $cat)
                <span class="tag">{{ $cat }}</span>
            @endforeach
            @if (!empty($book['isbn13']))
                <span class="tag">ISBN {{ $book['isbn13'] }}</span>
            @endif
            @if (!empty($book['isEbook']) && $book['isEbook'])
                <span class="tag" style="color:var(--sage-green);border-color:var(--sage-green);">e-Book</span>
            @endif
        </div>
    </div>

    <div class="book-list-arrow">›</div>
</a>
