<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Download;
use App\Models\Ecatalog;
use App\Models\Post;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::query()->where('email', 'test@example.com')->delete();
        User::query()->updateOrCreate(
            ['email' => 'admin@addariyahpress.com'],
            ['name' => 'Admin Addariyah', 'password' => 'Addariyah2026']
        );

        $settings = [
            'site_name' => 'Addariyah Press',
            'tagline' => 'Menerbitkan naskah menjadi buku yang rapi, terbaca, dan siap beredar.',
            'hero_title' => 'Penerbit yang menemani naskah sampai menjadi buku.',
            'hero_text' => 'Katalog e-book bisa dibeli online. Pembayaran lewat QRIS, lalu konfirmasi bukti ke WhatsApp.',
            'whatsapp' => '6282371114136',
            'email' => 'admin@addariyahpress.com',
            'address' => 'Indonesia',
            'instagram' => '',
            'about' => "Addariyah Press menerima naskah, menyunting, menata halaman, lalu menerbitkannya sebagai buku.\n\nBuku yang sudah terbit ditampilkan di katalog dan bisa dibeli sebagai e-book. Pembeli membayar lewat QRIS, kemudian mengirim bukti pembayaran ke WhatsApp penerbit.\n\nPenulis dapat membaca pedoman, mengunduh template, dan mengirim naskah langsung dari website.",
            'pedoman' => "Naskah dikirim dalam bahasa yang sudah dirapikan. Gunakan susunan bab yang jelas dan data yang bisa ditelusuri.\n\nHalaman\nGunakan kertas A4. Margin atas 4 cm, kiri 4 cm, kanan 3 cm, dan bawah 3 cm. Huruf isi 12 pt dengan spasi 1,5. Nomor halaman diletakkan di kaki halaman.\n\nSusunan berkas\nHalaman judul memuat judul, nama penulis, dan keterangan bahwa naskah diajukan ke Addariyah Press. Setelah itu daftar isi, isi bab, daftar pustaka, lalu identitas singkat penulis.\n\nKutipan\nKutipan pendek menyatu dalam paragraf dan diberi tanda kutip. Kutipan yang lebih panjang ditulis dalam paragraf tersendiri, menjorok ke dalam, tanpa tanda kutip.\n\nDaftar pustaka\nCantumkan nama penulis, tahun, judul, kota, dan penerbit. Urutkan berdasarkan nama belakang penulis.\n\nPengiriman\nKirim berkas PDF atau DOCX lewat formulir Kirim Naskah. Tuliskan sinopsis singkat di kolom pesan. Tim penerbit meninjau kiriman, lalu membalas lewat kontak yang dicantumkan.",
            'qris_image' => '',
            'visi' => 'Menjadi penerbit yang mendampingi penulis sampai naskahnya menjadi buku yang rapi, terbaca, dan siap beredar.',
            'misi' => "Menyunting dan menata naskah dengan teliti.\nMenyiapkan buku dengan ISBN dan QRCBN.\nMenyediakan katalog e-book yang bisa dibeli secara online.",
            'map_embed' => '',
            'office_hours' => '',
            'band_1_title' => 'Penerbitan',
            'band_1_text' => 'Naskah disunting, ditata, lalu disiapkan dengan ISBN dan QRCBN.',
            'band_2_title' => 'Katalog digital',
            'band_2_text' => 'E-book yang sudah terbit bisa dibeli. Bayar dengan QRIS, konfirmasi lewat WhatsApp.',
            'band_3_title' => 'Naskah masuk',
            'band_3_text' => 'Ada pedoman, template, dan formulir kirim naskah untuk penulis.',
        ];

        foreach ($settings as $key => $value) {
            Setting::setValue($key, $value);
        }

        $this->books();
        $this->posts();
        $this->downloads();
        $this->ecatalog();
        $this->call(PublishPackageSeeder::class);
    }

    private function books(): void
    {
        $items = [
            [
                'title' => 'Menulis yang Jernih',
                'slug' => 'menulis-yang-jernih',
                'author' => 'Nadia Pramesti',
                'category' => 'Kepenulisan',
                'pages' => 216,
                'isbn' => '978-602-9001-01-1',
                'qrcbn' => 'QRCBN-2026-0001',
                'editor' => 'Raka Widodo',
                'book_layout' => 'Sinta Lestari',
                'cover_designer' => 'Nia Kartika',
                'price' => 85000,
                'for_sale' => true,
                'description' => "Buku ini menuntun penulis merapikan kalimat, memilih kata, dan menyusun bab agar naskah enak diikuti.\n\nSetiap bagian disertai contoh sebelum dan sesudah disunting, plus latihan singkat yang bisa dikerjakan sebelum naskah dikirim ke penerbit.",
                'cover' => ['KEPENULISAN', 'Menulis', 'yang Jernih', 'Nadia Pramesti', '#0B3D2E'],
            ],
            [
                'title' => 'Seni Menyunting Naskah',
                'slug' => 'seni-menyunting-naskah',
                'author' => 'Bagus Hartono',
                'category' => 'Penyuntingan',
                'pages' => 184,
                'isbn' => '978-602-9001-02-8',
                'qrcbn' => 'QRCBN-2026-0002',
                'editor' => 'Laras Ayu',
                'book_layout' => 'Dimas Prakoso',
                'cover_designer' => 'Galih Saputra',
                'price' => 79000,
                'for_sale' => true,
                'description' => "Panduan kerja editor: membaca naskah, menandai masalah, dan berdiskusi dengan penulis tanpa mengubah suaranya.\n\nCocok untuk calon editor, penulis yang menyunting karyanya sendiri, dan tim penerbit yang baru menyusun alur kerja.",
                'cover' => ['PENYUNTINGAN', 'Seni', 'Menyunting', 'Bagus Hartono', '#145C45'],
            ],
            [
                'title' => 'Halaman yang Kosong',
                'slug' => 'halaman-yang-kosong',
                'author' => 'Intan Maheswari',
                'category' => 'Sastra',
                'pages' => 240,
                'isbn' => '978-602-9001-03-5',
                'qrcbn' => 'QRCBN-2026-0003',
                'editor' => 'Raka Widodo',
                'book_layout' => 'Sinta Lestari',
                'cover_designer' => 'Nia Kartika',
                'price' => 92000,
                'for_sale' => true,
                'description' => "Kumpulan cerita tentang orang-orang yang menunda menulis, lalu menemukan alasan untuk mulai lagi.\n\nNaskah ini diterbitkan sebagai contoh buku sastra di katalog Addariyah Press dan bisa dipesan sebagai e-book.",
                'cover' => ['SASTRA', 'Halaman', 'yang Kosong', 'Intan Maheswari', '#0F4C3A'],
            ],
            [
                'title' => 'Tata Letak untuk Buku',
                'slug' => 'tata-letak-untuk-buku',
                'author' => 'Yoga Pratama',
                'category' => 'Desain',
                'pages' => 160,
                'isbn' => '978-602-9001-04-2',
                'qrcbn' => 'QRCBN-2026-0004',
                'editor' => 'Laras Ayu',
                'book_layout' => 'Yoga Pratama',
                'cover_designer' => 'Dimas Prakoso',
                'price' => 75000,
                'for_sale' => true,
                'description' => "Dasar menata halaman buku: margin, huruf, hierarki judul, dan sampul yang tetap terbaca di layar maupun cetak.\n\nDitulis untuk penata letak pemula yang bekerja bersama penerbit.",
                'cover' => ['DESAIN', 'Tata Letak', 'untuk Buku', 'Yoga Pratama', '#1B7A56'],
            ],
            [
                'title' => 'Jejak di Perpustakaan',
                'slug' => 'jejak-di-perpustakaan',
                'author' => 'Salsa Kamila',
                'category' => 'Sastra',
                'pages' => 198,
                'editor' => 'Raka Widodo',
                'book_layout' => 'Sinta Lestari',
                'cover_designer' => 'Nia Kartika',
                'upcoming' => true,
                'expected_label' => 'Maret 2027',
                'description' => "Novel tentang seorang petugas perpustakaan yang menemukan catatan lama di antara buku sumbangan.\n\nBuku ini sedang disiapkan dan akan masuk katalog setelah terbit.",
                'cover' => ['SEGERA TERBIT', 'Jejak di', 'Perpustakaan', 'Salsa Kamila', '#06281E'],
            ],
            [
                'title' => 'Panduan ISBN dan QRCBN',
                'slug' => 'panduan-isbn-dan-qrcbn',
                'author' => 'Tim Addariyah Press',
                'category' => 'Penerbitan',
                'pages' => 96,
                'editor' => 'Laras Ayu',
                'book_layout' => 'Dimas Prakoso',
                'cover_designer' => 'Galih Saputra',
                'upcoming' => true,
                'expected_label' => 'Januari 2027',
                'description' => "Penjelasan praktis tentang ISBN, QRCBN, dan data yang perlu disiapkan sebelum buku beredar.\n\nDitulis oleh tim penerbit sebagai pegangan penulis dan staf produksi.",
                'cover' => ['SEGERA TERBIT', 'ISBN', 'dan QRCBN', 'Tim Penerbit', '#0B3D2E'],
            ],
        ];

        foreach ($items as $item) {
            $cover = $item['cover'];
            unset($item['cover']);
            $path = 'uploads/covers/'.$item['slug'].'.svg';
            $this->cover($path, $cover[0], $cover[1], $cover[2], $cover[3], $cover[4]);
            Book::query()->updateOrCreate(['slug' => $item['slug']], array_merge($item, [
                'publisher' => 'Addariyah Press',
                'cover_path' => $path,
                'is_active' => true,
                'for_sale' => $item['for_sale'] ?? false,
                'upcoming' => $item['upcoming'] ?? false,
            ]));
        }
    }

    private function posts(): void
    {
        $posts = [
            [
                'title' => 'Addariyah Press membuka penerimaan naskah',
                'slug' => 'penerimaan-naskah',
                'excerpt' => 'Penulis dapat mengirim naskah lewat formulir, setelah membaca pedoman dan mengunduh template.',
                'body' => "Addariyah Press membuka penerimaan naskah untuk buku yang siap disunting.\n\nSebelum mengirim, baca pedoman penulisan dan gunakan template yang tersedia. Berkas diterima dalam format PDF atau DOCX. Tim penerbit akan membalas melalui email atau WhatsApp yang dicantumkan pada formulir.",
                'days' => 1,
            ],
            [
                'title' => 'Yang perlu disiapkan sebelum buku masuk katalog',
                'slug' => 'siap-masuk-katalog',
                'excerpt' => 'Sampul, identitas buku, dan file e-book disiapkan sebelum judul tampil di katalog.',
                'body' => "Buku yang dijual di katalog memuat sampul, nama penulis, deskripsi, kategori, jumlah halaman, ISBN, QRCBN, editor, dan penata letak.\n\nHarga dicantumkan di halaman buku. Pembeli membayar lewat QRIS, lalu mengirim bukti ke WhatsApp. File e-book dikirim setelah konfirmasi diterima.",
                'days' => 6,
            ],
            [
                'title' => 'E-catalog biaya penerbitan sudah bisa dilihat',
                'slug' => 'e-catalog-biaya',
                'excerpt' => 'Informasi biaya penerbitan ditampilkan sebagai gambar di halaman E-Catalog.',
                'body' => "Halaman E-Catalog memuat gambar tentang biaya dan paket penerbitan. Gambar dapat diperbesar langsung di browser.\n\nJika membutuhkan penjelasan tambahan, penulis dapat menghubungi penerbit lewat WhatsApp yang tertera di bagian bawah website.",
                'days' => 12,
            ],
        ];

        foreach ($posts as $post) {
            $days = $post['days'];
            unset($post['days']);
            $path = 'uploads/news/'.$post['slug'].'.svg';
            $this->banner($path, $post['title']);
            Post::query()->updateOrCreate(['slug' => $post['slug']], array_merge($post, [
                'image_path' => $path,
                'is_published' => true,
                'published_at' => now()->subDays($days),
            ]));
        }
    }

    private function downloads(): void
    {
        $guide = "{\\rtf1\\ansi\\deff0{\\fonttbl{\\f0 Georgia;}}\\f0\\fs28 Pedoman ringkas Addariyah Press\\par\\par Gunakan A4, huruf 12 pt, spasi 1,5.\\par Susun halaman judul, daftar isi, isi bab, dan daftar pustaka.\\par}";
        $template = "{\\rtf1\\ansi\\deff0{\\fonttbl{\\f0 Georgia;}}\\f0\\fs32 Template Naskah Addariyah Press\\par\\par Judul:\\par Penulis:\\par\\par Sinopsis\\par\\par Bab 1\\par}";
        $this->file('uploads/resources/pedoman-ringkas.rtf', $guide);
        $this->file('uploads/resources/template-naskah.rtf', $template);

        Download::query()->updateOrCreate(['file_path' => 'uploads/resources/pedoman-ringkas.rtf'], [
            'type' => 'pedoman',
            'title' => 'Pedoman ringkas',
            'description' => 'Ringkasan ketentuan naskah dalam berkas yang bisa diunduh.',
        ]);
        Download::query()->updateOrCreate(['file_path' => 'uploads/resources/template-naskah.rtf'], [
            'type' => 'template',
            'title' => 'Template naskah',
            'description' => 'Kerangka judul, sinopsis, dan bab pertama.',
        ]);
    }

    private function ecatalog(): void
    {
        $cards = [
            ['biaya-naskah', 'Biaya naskah', 'Contoh visual. Ganti dengan gambar biaya penerbitan yang sebenarnya.', 'Naskah', 'Penyuntingan dan penyiapan berkas'],
            ['biaya-isbn', 'ISBN dan QRCBN', 'Contoh visual. Ganti dengan brosur resmi penerbit.', 'Identitas buku', 'ISBN, QRCBN, dan data terbit'],
            ['biaya-cetak', 'Cetak dan e-book', 'Contoh visual. Unggah beberapa gambar sekaligus dari admin.', 'Produksi', 'E-book katalog dan opsi cetak'],
        ];

        foreach ($cards as $index => [$slug, $title, $caption, $kicker, $line]) {
            $path = 'uploads/ecatalog/'.$slug.'.svg';
            $this->poster($path, $kicker, $title, $line);
            Ecatalog::query()->updateOrCreate(['image_path' => $path], [
                'title' => $title,
                'caption' => $caption,
                'sort_order' => $index + 1,
            ]);
        }
    }

    private function cover(string $path, string $kicker, string $line1, string $line2, string $author, string $color): void
    {
        $e = fn (string $value) => htmlspecialchars($value, ENT_XML1 | ENT_QUOTES, 'UTF-8');
        $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="800" height="1200" viewBox="0 0 800 1200">'
            .'<rect width="800" height="1200" fill="'.$color.'"/>'
            .'<rect x="46" y="46" width="708" height="1108" fill="none" stroke="#ffffff" stroke-opacity="0.45" stroke-width="3"/>'
            .'<text x="78" y="170" fill="#ffffff" font-family="Georgia, serif" font-size="24" letter-spacing="5">'.$e(strtoupper($kicker)).'</text>'
            .'<text x="78" y="820" fill="#ffffff" font-family="Georgia, serif" font-size="64">'.$e($line1).'</text>'
            .'<text x="78" y="900" fill="#ffffff" font-family="Georgia, serif" font-size="64">'.$e($line2).'</text>'
            .'<text x="78" y="1020" fill="#ffffff" font-family="Georgia, serif" font-size="28">'.$e($author).'</text>'
            .'<text x="78" y="1100" fill="#ffffff" font-family="Georgia, serif" font-size="18" letter-spacing="4">ADDARIYAH PRESS</text>'
            .'</svg>';
        $this->file($path, $svg);
    }

    private function banner(string $path, string $title): void
    {
        $e = fn (string $value) => htmlspecialchars($value, ENT_XML1 | ENT_QUOTES, 'UTF-8');
        $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="1200" height="700" viewBox="0 0 1200 700">'
            .'<rect width="1200" height="700" fill="#0B3D2E"/>'
            .'<text x="72" y="250" fill="#ffffff" font-family="Georgia, serif" font-size="28" letter-spacing="4">BERITA</text>'
            .'<text x="72" y="360" fill="#ffffff" font-family="Georgia, serif" font-size="48">'.$e($title).'</text>'
            .'</svg>';
        $this->file($path, $svg);
    }

    private function poster(string $path, string $kicker, string $title, string $line): void
    {
        $e = fn (string $value) => htmlspecialchars($value, ENT_XML1 | ENT_QUOTES, 'UTF-8');
        $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="1200" height="900" viewBox="0 0 1200 900">'
            .'<rect width="1200" height="900" fill="#F4FAF6"/>'
            .'<rect x="48" y="48" width="1104" height="804" fill="none" stroke="#0B3D2E" stroke-width="3"/>'
            .'<text x="90" y="180" fill="#146B4A" font-family="Georgia, serif" font-size="24" letter-spacing="4">'.$e(strtoupper($kicker)).'</text>'
            .'<text x="90" y="320" fill="#0B3D2E" font-family="Georgia, serif" font-size="64">'.$e($title).'</text>'
            .'<text x="90" y="420" fill="#14241C" font-family="Georgia, serif" font-size="32">'.$e($line).'</text>'
            .'<text x="90" y="760" fill="#5C6F66" font-family="Georgia, serif" font-size="22">Contoh e-catalog Addariyah Press</text>'
            .'</svg>';
        $this->file($path, $svg);
    }

    private function file(string $path, string $contents): void
    {
        $full = public_path($path);
        $directory = dirname($full);
        if (! is_dir($directory)) {
            mkdir($directory, 0755, true);
        }
        file_put_contents($full, $contents);
    }
}
