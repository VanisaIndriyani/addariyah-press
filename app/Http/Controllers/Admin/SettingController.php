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
            'address' => ['nullable', 'string', 'max:255'],
            'instagram' => ['nullable', 'string', 'max:160'],
            'qris' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'password' => ['nullable', 'confirmed', 'min:8'],
        ]);

        foreach (['site_name', 'tagline', 'hero_title', 'hero_text', 'about', 'whatsapp', 'email', 'address', 'instagram'] as $key) {
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
