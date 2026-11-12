<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Support\Site;
use App\Support\Uploader;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class BookController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim(str_replace(['%', '_'], '', $request->string('q')->toString()));
        $books = Book::query()
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('title', 'like', "%{$search}%")
                        ->orWhere('author', 'like', "%{$search}%")
                        ->orWhere('category', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('admin.books.index', compact('books', 'search'));
    }

    public function create(): View
    {
        return view('admin.books.form', [
            'book' => new Book(['publisher' => 'Addariyah Press', 'is_active' => true]),
            'categories' => $this->categories(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $book = Book::query()->create($this->payload($request, new Book));

        return redirect()->route('admin.buku.edit', $book)->with('success', 'Buku berhasil disimpan.');
    }

    public function edit(Book $book): View
    {
        return view('admin.books.form', [
            'book' => $book,
            'categories' => $this->categories(),
        ]);
    }

    public function update(Request $request, Book $book): RedirectResponse
    {
        $book->update($this->payload($request, $book));

        return redirect()->route('admin.buku.edit', $book)->with('success', 'Buku berhasil diperbarui.');
    }

    public function destroy(Book $book): RedirectResponse
    {
        Uploader::deletePublic($book->cover_path);
        Uploader::deletePrivate($book->ebook_path);
        $book->delete();

        return redirect()->route('admin.buku.index')->with('success', 'Buku dihapus.');
    }

    public function ebook(Book $book): BinaryFileResponse
    {
        $path = Uploader::privatePath($book->ebook_path);
        abort_unless($path, 404);

        $extension = pathinfo($path, PATHINFO_EXTENSION);

        return response()->download($path, $book->slug.'.'.$extension);
    }

    private function payload(Request $request, Book $book): array
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:180'],
            'author' => ['required', 'string', 'max:180'],
            'publisher' => ['nullable', 'string', 'max:180'],
            'category' => ['nullable', 'string', 'max:100'],
            'description' => ['nullable', 'string'],
            'pages' => ['nullable', 'integer', 'min:1', 'max:9999'],
            'isbn' => ['nullable', 'string', 'max:40'],
            'qrcbn' => ['nullable', 'string', 'max:60'],
            'editor' => ['nullable', 'string', 'max:180'],
            'book_layout' => ['nullable', 'string', 'max:180'],
            'cover_designer' => ['nullable', 'string', 'max:180'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'expected_label' => ['nullable', 'string', 'max:80'],
            'cover' => [$book->exists ? 'nullable' : 'required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'ebook' => ['nullable', 'file', 'mimes:pdf,epub', 'max:20480'],
        ]);

        if ($request->boolean('for_sale') && ($data['price'] === null || $data['price'] === '')) {
            throw ValidationException::withMessages([
                'price' => 'Harga wajib diisi untuk buku yang dijual di katalog.',
            ]);
        }

        $payload = [
            'title' => $data['title'],
            'slug' => Site::uniqueSlug(Book::class, $data['title'], $book->id),
            'author' => $data['author'],
            'publisher' => $data['publisher'] ?: 'Addariyah Press',
            'category' => $data['category'] ?? null,
            'description' => $data['description'] ?? null,
            'pages' => $data['pages'] ?? null,
            'isbn' => $data['isbn'] ?? null,
            'qrcbn' => $data['qrcbn'] ?? null,
            'editor' => $data['editor'] ?? null,
            'book_layout' => $data['book_layout'] ?? null,
            'cover_designer' => $data['cover_designer'] ?? null,
            'price' => ($data['price'] === null || $data['price'] === '') ? null : $data['price'],
            'for_sale' => $request->boolean('for_sale'),
            'upcoming' => $request->boolean('upcoming'),
            'expected_label' => $data['expected_label'] ?? null,
            'is_active' => $request->boolean('is_active'),
        ];

        if ($request->hasFile('cover')) {
            Uploader::deletePublic($book->cover_path);
            $payload['cover_path'] = Uploader::publicFile($request->file('cover'), 'covers');
        }

        if ($request->boolean('remove_ebook')) {
            Uploader::deletePrivate($book->ebook_path);
            $payload['ebook_path'] = null;
        }

        if ($request->hasFile('ebook')) {
            Uploader::deletePrivate($book->ebook_path);
            $payload['ebook_path'] = Uploader::privateFile($request->file('ebook'), 'ebooks');
        }

        return $payload;
    }

    private function categories()
    {
        return Book::query()
            ->whereNotNull('category')
            ->where('category', '!=', '')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');
    }
}
