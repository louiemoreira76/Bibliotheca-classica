@php
    $workId = ltrim($book['key'] ?? '', '/works/');
    $url = $workId
        ? route('books.show', ['id' => $workId, 'source' => 'openlibrary'])
        : '#';
@endphp
<a href="{{ $url }}" class="book-list-item">
    @if (!empty($book['thumbnail']))
        <img class="book-list-cover" src="{{ $book['thumbnail'] }}" alt="{{ $book['title'] }}" loading="lazy">
    @else
        <div class="book-list-cover-placeholder">📚</div>
    @endif

    <div class="book-list-body">
        <div class="book-list-title">{{ $book['title'] }}</div>

        <div class="book-list-meta">
            <span class="book-list-author">{{ implode(', ', array_slice($book['authors'] ?? ['Unknown Author'], 0, 3)) }}</span>

            @if (!empty($book['publishYear']))
                <span class="meta-sep">·</span>
                <span class="book-list-year">{{ $book['publishYear'] }}</span>
            @endif

            @if (!empty($book['pages']))
                <span class="meta-sep">·</span>
                <span class="book-list-year">{{ number_format($book['pages']) }} pp.</span>
            @endif

            @if (!empty($book['editions']))
                <span class="meta-sep">·</span>
                <span class="book-list-year">{{ $book['editions'] }} editions</span>
            @endif
        </div>

        @if (!empty($book['subjects']))
            <div class="book-list-tags">
                @foreach (array_slice($book['subjects'], 0, 4) as $subj)
                    <span class="tag">{{ $subj }}</span>
                @endforeach
                @if (!empty($book['isbn']))
                    <span class="tag">ISBN {{ $book['isbn'] }}</span>
                @endif
            </div>
        @endif
    </div>

    <div class="book-list-arrow">›</div>
</a>
