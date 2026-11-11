@if ($files->isEmpty())
    <p class="muted">{{ $empty }}</p>
@else
    <div class="file-list">
        @foreach ($files as $file)
            <article class="file-row">
                <div>
                    <strong>{{ $file->title }}</strong>
                    @if ($file->description)
                        <p class="muted">{{ $file->description }}</p>
                    @endif
                </div>
                <div class="actions">
                    <a class="btn btn-line dark btn-small" href="{{ asset($file->file_path) }}" target="_blank" rel="noopener">Lihat</a>
                    <button class="btn btn-line dark btn-small" type="button" data-open="resource-edit-{{ $file->id }}">Ubah</button>
                    <button class="btn btn-danger btn-small" type="button" data-open="resource-delete-{{ $file->id }}">Hapus</button>
                </div>
            </article>
        @endforeach
    </div>
@endif
