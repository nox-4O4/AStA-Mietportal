<div>
    <h1>AStA-Mietportal</h1>

    <div class="row">
        <div class="col">
            @if($search)
                <p class="mb-0">
                    @if(count($this->items) == 1)
                        Es wurde <b>ein</b>
                    @elseif(count($this->items) > 1)
                        Es wurden <b>{{count($this->items)}}</b>
                    @else
                        Es wurden <b>keine</b>
                    @endif
                    <b>Treffer</b> bei der Suche nach „{{$search}}“ gefunden.
                </p>

                @if(!$this->items && !$this->searchDescription)
                    <p>
                        <span class="my-1 d-inline-block">Eventuell hilft es, die Suche unter Berücksichtigung der Artikel&shy;beschreibung erneut vorzunehmen.</span>
                        <a class="btn btn-outline-primary align-baseline" href="{{route('shop', ['suche' => $search, 'searchDescription' => true])}}" wire:navigate>Mit Artikelbeschreibung suchen</a>
                    </p>
                @endif

                <p class="my-3">
                    <a class="btn btn-outline-primary" href="{{route('shop')}}" wire:navigate>
                        <i class="fa-solid fa-arrow-rotate-left" title="Suche Zurücksetzen"></i>
                        Suche zurücksetzen
                    </a>
                </p>
            @else
                @content('shop.top')
            @endif
        </div>
    </div>

    <div class="row">
        @foreach($this->items as $item)
            <div class="col-8 offset-2 offset-sm-0 col-sm-6 col-md-4 col-lg-3 gutter-even aspect-1">
                <a class="w-100 h-100 position-relative d-block text-body" href="{{route($item->grouped ? 'shop.itemGroup.view' : 'shop.item.view', [$item->id, \Illuminate\Support\Str::slug($item->name)])}}" wire:navigate>
                    @if($item->imagePath)
                        <img src="{{\Illuminate\Support\Facades\Storage::url($item->imagePath)}}" alt="Produktbild {{htmlspecialchars($item->name,encoding: 'UTF-8')}}" class="w-100 h-100 object-fit-contain">
                    @else
                        {!! File::get(resource_path('img/product-placeholder.svg')) !!}
                    @endif

                    <span @if($item->grouped && !$item->visible)
                              title="Unterschiedliche Varianten vorhanden; Artikel nicht sichtbar"
                          @elseif($item->grouped)
                              title="Unterschiedliche Varianten vorhanden"
                          @elseif(!$item->visible)
                              title="Artikel nicht sichtbar"
                          @endif
                          class="position-absolute bottom-0 start-0 end-0 text-body-emphasis bg-body bg-opacity-50 p-1 fw-bold background-blur text-shadow-body">

                        {{$item->name}}

                        @if($item->grouped)
                            <i class="fa-solid fa-grip" title="Unterschiedliche Varianten vorhanden"></i>
                        @endif
                        @if(!$item->visible)
                            <i class="fa-regular fa-eye-slash" title="Artikel nicht sichtbar"></i>
                        @endif
                    </span>
                </a>
            </div>
        @endforeach
    </div>
</div>
