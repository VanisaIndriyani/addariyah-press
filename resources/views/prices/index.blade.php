@extends('layouts.public')

@section('title', 'Harga paket penerbitan — Addariyah Press')

@section('content')
    <section class="section">
        <div class="container">
            <div class="price-head">
                <div>
                    <p class="eyebrow">Layanan</p>
                    <h1>Harga paket penerbitan</h1>
                </div>
                @if ($note)
                    <p class="price-note">{{ $note }}</p>
                @endif
            </div>

            @forelse ($packages as $package)
                <details class="price-pack" @if ($loop->first) open @endif>
                    <summary>
                        <span>&gt;&gt; {{ $package->title }}</span>
                        <span class="price-chevron" aria-hidden="true"></span>
                    </summary>
                    <div class="price-pack-body">
                        @forelse ($package->tables ?? [] as $table)
                            @if (! empty($table['paper']))
                                <h2>{{ $table['paper'] }}</h2>
                            @endif
                            <div class="price-scroll">
                                <table class="price-table">
                                    <thead>
                                        <tr>
                                            <th>Oplah</th>
                                            @foreach ($table['columns'] ?? [] as $column)
                                                <th>{{ $column }}<small>Rp</small></th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($table['rows'] ?? [] as $row)
                                            <tr>
                                                <th>{{ $row['oplah'] ?? '' }}</th>
                                                @foreach ($table['columns'] ?? [] as $index => $column)
                                                    <td>
                                                        @if (isset($row['prices'][$index]) && $row['prices'][$index] !== null && $row['prices'][$index] !== '')
                                                            {{ number_format((int) $row['prices'][$index], 0, ',', '.') }}
                                                        @else
                                                            —
                                                        @endif
                                                    </td>
                                                @endforeach
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @empty
                            <p class="muted">Tabel harga paket ini belum diisi.</p>
                        @endforelse
                    </div>
                </details>
            @empty
                <p class="muted">Harga paket belum dipublikasikan.</p>
            @endforelse

            @if ($included || $specs)
                <div class="price-notes">
                    @if ($included)
                        <article>
                            <h2>Harga sudah termasuk</h2>
                            <ul>
                                @foreach ($included as $item)
                                    <li>{{ $item }}</li>
                                @endforeach
                            </ul>
                        </article>
                    @endif
                    @if ($specs)
                        <article>
                            <h2>Spesifikasi umum buku</h2>
                            <ul>
                                @foreach ($specs as $item)
                                    <li>{{ $item }}</li>
                                @endforeach
                            </ul>
                        </article>
                    @endif
                </div>
            @endif
        </div>
    </section>
@endsection
