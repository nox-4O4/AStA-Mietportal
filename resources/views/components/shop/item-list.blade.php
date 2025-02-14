<div>
    <h1>AStA-Mietportal</h1>

    <div class="row">
        <div class="col">
            @content('shop.top')
        </div>
    </div>

    <div class="row">
        @foreach($this->items as $item)
            <div class="col-8 offset-2 offset-sm-0 col-sm-6 col-md-4 col-lg-3 gutter-even aspect-1">
                <a class="w-100 h-100 position-relative d-block text-body" href="{{route($item->grouped ? 'shop.itemGroup.view' : 'shop.item.view', [$item->id, \Illuminate\Support\Str::slug($item->name)])}}" wire:navigate>
                    @if($item->imagePath)
                        <img src="{{\Illuminate\Support\Facades\Storage::url($item->imagePath)}}" alt="Produktbild {{htmlspecialchars($item->name,encoding: 'UTF-8')}}" class="w-100 h-100 object-fit-contain">
                    @else
                        {!! File::get(resource_path('img/product-placeholder-opt.svg')) !!}
                    @endif

                    <span class="position-absolute bottom-0 start-0 end-0 text-body-emphasis bg-body bg-opacity-50 p-1 fw-bold background-blur text-shadow-body" @if($item->grouped)title="Unterschiedliche Varianten vorhanden"@endif>
                        {{$item->name}}

                        @if($item->grouped)
                            <i class="fa-solid fa-grip"></i>
                        @endif
                    </span>
                </a>
            </div>
        @endforeach
    </div>
</div>
