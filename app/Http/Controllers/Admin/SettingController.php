<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Support\Site;
use App\Support\Uploader;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function edit(): View
    {
        return view('admin.settings.edit', [
            'settings' => [
                'site_name' => Site::get('site_name', 'Addariyah Press'),
                'tagline' => Site::get('tagline', ''),
                'hero_title' => Site::get('hero_title', ''),
                'hero_text' => Site::get('hero_text', ''),
                'about' => Site::get('about', ''),
                'whatsapp' => Site::get('whatsapp', ''),
                'email' => Site::get('email', ''),
                'address' => Site::get('address', ''),
                'instagram' => Site::get('instagram', ''),
                'qris_image' => Site::get('qris_image', ''),
                'band_1_title' => Site::get('band_1_title', 'Penerbitan'),
                'band_1_text' => Site::get('band_1_text', 'Naskah disunting, ditata, lalu disiapkan dengan ISBN dan QRCBN.'),
                'band_2_title' => Site::get('band_2_title', 'Katalog digital'),
                'band_2_text' => Site::get('band_2_text', 'E-book yang sudah terbit bisa dibeli. Bayar dengan QRIS, konfirmasi lewat WhatsApp.'),
                'band_3_title' => Site::get('band_3_title', 'Naskah masuk'),
                'band_3_text' => Site::get('band_3_text', 'Ada pedoman, template, dan formulir kirim naskah untuk penulis.'),
            ],
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'site_name' => ['required', 'string', 'max:120'],
            'tagline' => ['nullable', 'string', 'max:200'],
            'hero_title' => ['required', 'string', 'max:200'],
            'hero_text' => ['required', 'string', 'max:600'],
            'about' => ['nullable', 'string'],
            'whatsapp' => ['required', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:150'],
            'address' => ['nullable', 'string', 'max:500'],
            'instagram' => ['nullable', 'string', 'max:160'],
            'band_1_title' => ['required', 'string', 'max:80'],
            'band_1_text' => ['required', 'string', 'max:280'],
            'band_2_title' => ['required', 'string', 'max:80'],
            'band_2_text' => ['required', 'string', 'max:280'],
            'band_3_title' => ['required', 'string', 'max:80'],
            'band_3_text' => ['required', 'string', 'max:280'],
            'qris' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'password' => ['nullable', 'confirmed', 'min:8'],
        ]);

        foreach (['site_name', 'tagline', 'hero_title', 'hero_text', 'about', 'whatsapp', 'email', 'address', 'instagram', 'band_1_title', 'band_1_text', 'band_2_title', 'band_2_text', 'band_3_title', 'band_3_text'] as $key) {
            Setting::setValue($key, $data[$key] ?? '');
        }

        if ($request->hasFile('qris')) {
            $old = (string) Setting::getValue('qris_image', '');
            if ($old !== '') {
                Uploader::deletePublic($old);
            }
            Setting::setValue('qris_image', Uploader::publicFile($request->file('qris'), 'qris'));
        }

        if ($request->filled('password')) {
            $request->user()->update(['password' => $request->string('password')->toString()]);
        }

        return back()->with('success', 'Pengaturan disimpan.');
    }
}
