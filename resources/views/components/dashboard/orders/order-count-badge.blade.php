<div class="d-flex">
    @if($this->count)
        <span class="rounded-3 text-bg-warning px-2 small" title="{{$this->count == 1 ? 'Eine neue Bestellung' : "$this->count neue Bestellungen" }}">{{$this->count}}</span>
    @endif
</div>
