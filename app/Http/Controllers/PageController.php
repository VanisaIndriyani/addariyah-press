<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\ContactMessage;
use App\Support\Site;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PageController extends Controller
{
    public function profile(): View
    {
        return view('pages.profile', [
            'about' => Site::get('about', ''),
            'visi' => Site::get('visi', 'Menjadi penerbit yang mendampingi penulis sampai naskahnya menjadi buku yang rapi, terbaca, dan siap beredar.'),
            'misi' => Site::get('misi', "Menyunting dan menata naskah dengan teliti.\nMenyiapkan buku dengan ISBN dan QRCBN.\nMenyediakan katalog e-book yang bisa dibeli secara online."),
        ]);
    }

    public function contact(): View
    {
        return view('pages.contact', [
            'people' => Contact::query()->orderBy('sort_order')->orderBy('id')->get(),
            'map' => Site::mapEmbed((string) Site::get('map_embed', '')),
            'hours' => Site::get('office_hours', ''),
        ]);
    }

    public function sendContact(Request $request): RedirectResponse
    {
        if ($request->filled('website')) {
            return redirect()->route('contact')->with('success', 'Pesan terkirim. Tim Addariyah Press akan membalas lewat kontak yang dicantumkan.');
        }

        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:150'],
            'subject' => ['nullable', 'string', 'max:160'],
            'message' => ['required', 'string', 'max:3000'],
        ]);

        ContactMessage::query()->create($data);

        return redirect()->route('contact')->with('success', 'Pesan terkirim. Tim Addariyah Press akan membalas lewat kontak yang dicantumkan.');
    }
}
