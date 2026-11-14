@extends('layouts.admin')

@section('title', 'Isi harga — '.$package->title)

@section('content')
    <div class="admin-top">
        <div>
            <p class="eyebrow">Harga paket</p>
            <h1>{{ $package->title }}</h1>
        </div>
        <a class="btn btn-line dark" href="{{ route('admin.prices.index') }}">Kembali</a>
    </div>

    <form class="panel" method="POST" action="{{ route('admin.prices.update', $package) }}" id="price-form">
        @csrf
        @method('PUT')
        <div class="form-grid">
            <div class="field">
                <label for="title">Nama paket</label>
                <input id="title" name="title" value="{{ old('title', $package->title) }}" required>
            </div>
            <div class="field">
                <label for="sort_order">Urutan</label>
                <input id="sort_order" type="number" name="sort_order" min="0" max="9999" value="{{ old('sort_order', $package->sort_order) }}">
            </div>
        </div>

        <div id="paper-list">
            @foreach ($tables as $paperIndex => $table)
                @include('admin.prices.paper', ['paperIndex' => $paperIndex, 'table' => $table])
            @endforeach
        </div>

        <div class="form-bar">
            <button class="btn btn-line dark" type="button" id="add-paper">Tambah jenis kertas</button>
            <button class="btn btn-primary" type="submit">Simpan tabel</button>
        </div>
    </form>

    <template id="paper-template">
        @include('admin.prices.paper', ['paperIndex' => '__PAPER__', 'table' => \App\Models\PublishPackage::blankTables()[0]])
    </template>

    <template id="row-template">
        <tr>
            <td><input name="papers[__PAPER__][rows][__ROW__][oplah]" value="20 Eksemplar"></td>
            @foreach (range(0, 2) as $index)
                <td><input name="papers[__PAPER__][rows][__ROW__][prices][{{ $index }}]" inputmode="numeric" placeholder="0"></td>
            @endforeach
            <td><button class="btn btn-danger btn-small" type="button" data-remove-row>Hapus</button></td>
        </tr>
    </template>

    <script>
        (function () {
            var list = document.getElementById('paper-list');
            var paperTemplate = document.getElementById('paper-template').innerHTML;
            var rowTemplate = document.getElementById('row-template').innerHTML;
            var paperCount = list.querySelectorAll('[data-paper]').length;

            function bind(scope) {
                scope.querySelectorAll('[data-add-row]').forEach(function (button) {
                    button.onclick = function () {
                        var paper = button.closest('[data-paper]');
                        var body = paper.querySelector('tbody');
                        var index = paper.getAttribute('data-paper');
                        var row = body.querySelectorAll('tr').length;
                        body.insertAdjacentHTML('beforeend', rowTemplate.replaceAll('__PAPER__', index).replaceAll('__ROW__', row));
                    };
                });
                scope.querySelectorAll('[data-remove-row]').forEach(function (button) {
                    button.onclick = function () {
                        var row = button.closest('tr');
                        if (row.parentElement.querySelectorAll('tr').length > 1) row.remove();
                    };
                });
                scope.querySelectorAll('[data-remove-paper]').forEach(function (button) {
                    button.onclick = function () {
                        if (list.querySelectorAll('[data-paper]').length > 1) button.closest('[data-paper]').remove();
                    };
                });
            }

            document.getElementById('add-paper').addEventListener('click', function () {
                list.insertAdjacentHTML('beforeend', paperTemplate.replaceAll('__PAPER__', paperCount));
                paperCount += 1;
                bind(list.lastElementChild);
            });

            bind(list);
        })();
    </script>
@endsection
