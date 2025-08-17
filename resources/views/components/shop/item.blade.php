<div>
    <div class="d-flex justify-content-between align-items-baseline column-gap-3 flex-wrap flex-sm-nowrap">
        <h1 class="d-flex align-items-center gap-2">
            <span class="d-flex">
                <a href="{{route('shop')}}" title="Zurück zur Artikelliste" wire:navigate class="btn btn-outline-secondary"><i class="fa-solid fa-chevron-left"></i></a>
            </span>

            <span>
            {{$element->name}}
                @if($item && !$item->visible)
                    <span class="badge text-secondary bg-secondary-subtle fs-6 d-inline-flex align-middle" title="Artikel nicht sichtbar">
                        <i class="fa-regular fa-eye-slash"></i>
                    </span>
                @endif
            </span>
        </h1>
        @can('manage-items')
            @if($item)
                <a href="{{route('dashboard.items.edit', $item->id)}}" class="btn btn-sm btn-outline-primary text-nowrap mb-2" wire:navigate title="Artikel bearbeiten">
                    <i class="fa-solid me-1 fa-pen-to-square"></i>Artikel bearbeiten
                </a>
            @elseif($group)
                <a href="{{route('dashboard.groups.edit', $group->id)}}" class="btn btn-sm btn-outline-primary text-nowrap mb-2" wire:navigate title="Artikelgruppe bearbeiten">
                    <i class="fa-solid me-1 fa-pen-to-square"></i>Artikelgruppe bearbeiten
                </a>
            @endif
        @endcan
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            @if($group?->image || $item?->images->isNotEmpty())
                <x-swiper id="shop-item-images" class="item-image-container">
                    @foreach($item->images ?? [$group->image] as $image)
                        <div class="swiper-slide" data-hash="image-{{$image->id}}">
                            <div class="swiper-zoom-container">
                                <img src="{{\Illuminate\Support\Facades\Storage::url($image->path)}}" alt="Produktbild {{htmlspecialchars($element->name)}}" class="object-fit-{{config('shop.image_sizing')}}">
                            </div>
                        </div>
                    @endforeach
                </x-swiper>
            @else
                <div class="item-image-container d-flex justify-content-center">
                    {!! File::get(resource_path('img/product-placeholder.svg')) !!}
                </div>
            @endif
        </div>
        <div class="col mb-3">
            @if($group || $item?->itemGroup)
                <form class="row mb-3">
                    <label class="col-form-label col-lg-2 col-3" for="item">Variante</label>
                    <div class="col">
                        <select class="form-control" id="item" x-on:change="Livewire.navigate($event.target.value)">
                            @if(!$item)
                                <option hidden value="">Bitte eine Option wählen...</option>
                            @endif
                            @foreach(($group ?? $item->itemGroup)->items->sortBy('name', SORT_NATURAL) as $groupItem)
                                @can('view', $groupItem)
                                    <option value="{{route('shop.item.view', [$groupItem->id, $groupItem->slug], false)}}" @if($item?->id == $groupItem->id) selected @endif>{{$groupItem->raw_name}}</option>
                                @endcan
                            @endforeach
                        </select>
                    </div>
                </form>
            @endif

            @if($group?->items->where('available', true)->isNotEmpty() || $item?->available)
                <p class="text-success fw-semibold">
                    <i class="fa-solid fa-check"></i>
                    @if($item?->amount)
                        {{$item->amount}} Stück vorhanden
                    @else
                                          Verfügbar
                    @endif
                </p>
            @else
                <p class="text-danger fw-semibold">
                    <i class="fa-solid fa-xmark"></i> Derzeit nicht verfügbar
                </p>
            @endif

            @if($item)
                {!! $this->priceCalculator->displayPriceInformation($item) !!}
                @if($item->available)
                    <livewire:shop.item-add-to-cart :$item />
                @endif
            @endif
        </div>
    </div>
    @if($element->description || $item?->itemGroup?->description || $item?->deposit)
        <div class="row mb-3">
            <div class="col">
                @if(
                    isset($item->itemGroup->description) && !$item->itemGroup->description->isEmpty() ||
                    isset($item->description) && !$item->description->isEmpty() ||
                    $item?->deposit
                )
                    <h3>Beschreibung</h3>
                @endif

                @if($item?->itemGroup)
                    <p>{!! $item->itemGroup->description !!}</p>
                @endif
                <p>{!! $element->description !!}</p>
                @if($item?->deposit)
                    <p>Kaution: @money($item->deposit)</p>
                @endif
            </div>
        </div>
    @endif
    <div class="row">
        <div class="col">
            <a href="{{route('shop')}}" wire:navigate class="btn btn-outline-primary"><i class="fa-solid fa-arrow-left"></i> Zurück zu den Artikeln</a>
        </div>
    </div>
</div>
