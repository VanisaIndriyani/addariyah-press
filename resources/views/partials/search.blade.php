<form class="{{ $searchClass ?? 'filters' }}" method="GET" action="{{ $action }}">
    <input type="search" name="q" value="{{ $search }}" placeholder="{{ $placeholder }}" aria-label="{{ $placeholder }}">
    <button class="btn btn-primary btn-small" type="submit">Cari</button>
    @if ($search !== '')
        <a class="btn btn-line dark btn-small" href="{{ $action }}">Reset</a>
    @endif
</form>
