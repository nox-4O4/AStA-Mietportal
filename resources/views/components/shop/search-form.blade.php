<form wire:submit="performSearch" x-data="{searchInput: $wire.entangle('search')}">
    <div class="input-group">
        @if($search !== '')
            <a class="btn btn-outline-primary" href="{{route('shop')}}" title="Suche Zurücksetzen" wire:navigate>
                <i class="fa-solid fa-arrow-rotate-left"></i>
            </a>
        @endif
        <input class="form-control @if($search === '')initialEmpty @endif" placeholder="Suche..." wire:model="search" list="items">
        <button class="btn btn-outline-primary" type="submit">
            <i class="fa-solid fa-magnifying-glass" title="Suchen"></i>
        </button>
    </div>
    <datalist :id="searchInput.trim().length > 2 && 'items'">
        @foreach($this->items as $item)
            <option value="{{$item->name}}"></option>
        @endforeach
    </datalist>
</form>
