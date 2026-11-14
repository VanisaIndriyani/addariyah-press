<article class="paper-block" data-paper="{{ $paperIndex }}">
    <div class="paper-block-head">
        <div class="field">
            <label>Jenis kertas</label>
            <input name="papers[{{ $paperIndex }}][paper]" value="{{ $table['paper'] ?? '' }}" placeholder="Book Paper 70 gsm">
        </div>
        <button class="btn btn-danger btn-small" type="button" data-remove-paper>Hapus kertas</button>
    </div>
    <div class="price-scroll">
        <table class="price-edit">
            <thead>
                <tr>
                    <th>Oplah</th>
                    @foreach (($table['columns'] ?? ['', '', '']) as $columnIndex => $column)
                        <th><input name="papers[{{ $paperIndex }}][columns][{{ $columnIndex }}]" value="{{ $column }}" placeholder="150-199 halaman"></th>
                    @endforeach
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach (($table['rows'] ?? []) as $rowIndex => $row)
                    <tr>
                        <td><input name="papers[{{ $paperIndex }}][rows][{{ $rowIndex }}][oplah]" value="{{ $row['oplah'] ?? '' }}"></td>
                        @foreach (($table['columns'] ?? ['', '', '']) as $columnIndex => $column)
                            <td><input name="papers[{{ $paperIndex }}][rows][{{ $rowIndex }}][prices][{{ $columnIndex }}]" value="{{ $row['prices'][$columnIndex] ?? '' }}" inputmode="numeric" placeholder="0"></td>
                        @endforeach
                        <td><button class="btn btn-danger btn-small" type="button" data-remove-row>Hapus</button></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <button class="btn btn-line dark btn-small" type="button" data-add-row>Tambah baris oplah</button>
</article>
