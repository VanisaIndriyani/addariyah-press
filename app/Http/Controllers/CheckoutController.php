<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Support\Cart as CartBag;
use App\Support\Site;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function create(): View|RedirectResponse
    {
        $lines = CartBag::lines();
        if ($lines->isEmpty()) {
            return redirect()->route('cart')->with('error', 'Keranjang masih kosong. Pilih buku dari katalog terlebih dahulu.');
        }

        return view('checkout.create', [
            'lines' => $lines,
            'total' => CartBag::total(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'phone' => ['required', 'regex:/^[0-9+\-\s]{8,20}$/'],
            'email' => ['nullable', 'email', 'max:150'],
            'note' => ['nullable', 'string', 'max:1000'],
        ]);

        $lines = CartBag::lines();
        if ($lines->isEmpty()) {
            return redirect()->route('cart')->with('error', 'Keranjang masih kosong.');
        }

        $order = DB::transaction(function () use ($data, $lines) {
            $order = Order::query()->create([
                'code' => $this->code(),
                'name' => $data['name'],
                'phone' => $data['phone'],
                'email' => $data['email'] ?? null,
                'note' => $data['note'] ?? null,
                'total' => CartBag::total(),
            ]);

            foreach ($lines as $line) {
                $order->items()->create([
                    'book_id' => $line['book']->id,
                    'book_title' => $line['book']->title,
                    'price' => $line['book']->price,
                    'qty' => $line['qty'],
                    'subtotal' => $line['subtotal'],
                ]);
            }

            return $order;
        });

        CartBag::clear();

        return redirect()->route('checkout.pay', $order->code);
    }

    public function show(string $code): View
    {
        $order = Order::query()->with('items')->where('code', $code)->firstOrFail();
        $text = $this->message($order);

        return view('checkout.pay', [
            'order' => $order,
            'waUrl' => Site::waNumber() !== '' ? Site::waUrl($text) : null,
        ]);
    }

    private function code(): string
    {
        do {
            $code = 'AP-'.Str::upper(Str::random(8));
        } while (Order::query()->where('code', $code)->exists());

        return $code;
    }

    private function message(Order $order): string
    {
        $lines = $order->items->map(function ($item) {
            return '- '.$item->book_title.' x'.$item->qty.' = '.Site::rupiah($item->subtotal);
        })->implode("\n");

        $note = $order->note ? "\nCatatan: {$order->note}\n" : '';

        return "Halo Addariyah Press, saya sudah melakukan pembayaran.\n\n"
            ."Kode pesanan: {$order->code}\n"
            ."Nama: {$order->name}\n"
            ."WhatsApp: {$order->phone}\n"
            .$note
            ."\nBuku:\n{$lines}\n\n"
            .'Total: '.Site::rupiah($order->total)."\n\n"
            .'Mohon dikonfirmasi. Bukti pembayaran saya lampirkan pada chat ini.';
    }
}
