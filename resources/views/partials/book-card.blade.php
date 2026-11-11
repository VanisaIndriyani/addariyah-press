<article class="book-card">
    <a class="cover" href="{{ route('books.show', $book) }}">
        @if ($book->cover_path)
            <img src="{{ asset($book->cover_path) }}" alt="Sampul {{ $book->title }}">
        @else
            <span class="cover-fallback">{{ $book->title }}</span>
        @endif
        @if ($book->upcoming)
            <span class="badge">Segera terbit</span>
        @endif
    </a>
    <div class="book-card-body">
        <p class="meta">{{ $book->category ?: 'Umum' }}</p>
        <h3><a href="{{ route('books.show', $book) }}">{{ $book->title }}</a></h3>
        <p class="author">{{ $book->author }}</p>
        @if ($book->for_sale && $book->price !== null)
            <p class="price">@rupiah($book->price)</p>
        @elseif ($book->expected_label)
            <p class="muted">{{ $book->expected_label }}</p>
        @endif
    </div>
</article>
