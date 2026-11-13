<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\ContactMessage;
use App\Models\Setting;
use App\Support\Site;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(): View
    {
        return view('admin.profile.edit', [
            'about' => Site::get('about', ''),
            'visi' => Site::get('visi', 'Menjadi penerbit yang mendampingi penulis sampai naskahnya menjadi buku yang rapi, terbaca, dan siap beredar.'),
            'misi' => Site::get('misi', "Menyunting dan menata naskah dengan teliti.\nMenyiapkan buku dengan ISBN dan QRCBN.\nMenyediakan katalog e-book yang bisa dibeli secara online."),
            'mapEmbed' => Site::get('map_embed', ''),
            'hours' => Site::get('office_hours', ''),
            'people' => Contact::query()->orderBy('sort_order')->orderBy('id')->get(),
            'messages' => ContactMessage::query()->latest()->limit(40)->get(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'about' => ['nullable', 'string', 'max:5000'],
            'visi' => ['required', 'string', 'max:1000'],
            'misi' => ['required', 'string', 'max:2000'],
            'map_embed' => ['nullable', 'string', 'max:2000'],
            'office_hours' => ['nullable', 'string', 'max:500'],
        ]);

        $map = trim((string) ($data['map_embed'] ?? ''));
        if ($map !== '' && Site::mapEmbed($map) === null) {
            return back()
                ->withErrors(['map_embed' => 'Tautan peta harus dari Google Maps atau OpenStreetMap.'])
                ->withInput();
        }

        Setting::setValue('about', $data['about'] ?? '');
        Setting::setValue('visi', $data['visi']);
        Setting::setValue('misi', $data['misi']);
        Setting::setValue('map_embed', $map);
        Setting::setValue('office_hours', $data['office_hours'] ?? '');

        return back()->with('success', 'Profil dan kontak disimpan.');
    }

    public function storeContact(Request $request): RedirectResponse
    {
        Contact::query()->create($this->contactData($request) + [
            'sort_order' => (int) Contact::query()->max('sort_order') + 1,
        ]);

        return back()->with('success', 'Kontak person ditambahkan.');
    }

    public function updateContact(Request $request, Contact $contact): RedirectResponse
    {
        $contact->update($this->contactData($request, true));

        return back()->with('success', 'Kontak person diperbarui.');
    }

    public function destroyContact(Contact $contact): RedirectResponse
    {
        $contact->delete();

        return back()->with('success', 'Kontak person dihapus.');
    }

    public function destroyMessage(ContactMessage $message): RedirectResponse
    {
        $message->delete();

        return back()->with('success', 'Pesan dihapus.');
    }

    private function contactData(Request $request, bool $withOrder = false): array
    {
        $rules = [
            'name' => ['required', 'string', 'max:120'],
            'role' => ['nullable', 'string', 'max:120'],
            'phone' => ['nullable', 'string', 'max:40'],
            'email' => ['nullable', 'email', 'max:150'],
        ];

        if ($withOrder) {
            $rules['sort_order'] = ['nullable', 'integer', 'min:0', 'max:9999'];
        }

        $data = $request->validate($rules);
        $data['role'] = trim((string) ($data['role'] ?? '')) ?: null;
        $data['phone'] = trim((string) ($data['phone'] ?? '')) ?: null;
        $data['email'] = trim((string) ($data['email'] ?? '')) ?: null;

        if ($withOrder) {
            $data['sort_order'] = (int) ($data['sort_order'] ?? 0);
        }

        return $data;
    }
}
