<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $this->replace(
            'hero_title',
            'Penerbit yang menemani naskah sampai menjadi buku.',
            'Dari Ide Menjadi Karya, Dari Naskah Menjadi Buku.'
        );
        $this->replace(
            'hero_text',
            'Katalog e-book bisa dibeli online. Pembayaran lewat QRIS, lalu konfirmasi bukti ke WhatsApp.',
            'Setiap Gagasan Punya Cerita, Setiap Karya Punya Makna. Selamat datang di Addariyah Press, ruang bagi lahirnya gagasan, tumbuhnya pengetahuan, dan terciptanya karya yang bermakna.'
        );
    }

    public function down(): void
    {
        $this->replace(
            'hero_title',
            'Dari Ide Menjadi Karya, Dari Naskah Menjadi Buku.',
            'Penerbit yang menemani naskah sampai menjadi buku.'
        );
        $this->replace(
            'hero_text',
            'Setiap Gagasan Punya Cerita, Setiap Karya Punya Makna. Selamat datang di Addariyah Press, ruang bagi lahirnya gagasan, tumbuhnya pengetahuan, dan terciptanya karya yang bermakna.',
            'Katalog e-book bisa dibeli online. Pembayaran lewat QRIS, lalu konfirmasi bukti ke WhatsApp.'
        );
    }

    private function replace(string $key, string $from, string $to): void
    {
        $current = DB::table('settings')->where('key', $key)->value('value');
        if ($current === null) {
            DB::table('settings')->insert([
                'key' => $key,
                'value' => $to,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return;
        }

        if ($current === $from) {
            DB::table('settings')->where('key', $key)->update([
                'value' => $to,
                'updated_at' => now(),
            ]);
        }
    }
};
