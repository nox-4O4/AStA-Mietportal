<div>
    <h1>
        {{$element->name}}
        @if($item && !$item->visible)
            <span class="badge text-secondary bg-secondary-subtle fs-6" title="Artikel nicht sichtbar">
                <i class="fa-regular fa-eye-slash"></i>
            </span>
        @endif
    </h1>
    <div class="row">
        <div class="col-md-6 mb-3">
            @if($group?->image || $item?->images->isNotEmpty())
                <x-swiper id="shop-item-images" class="item-image-container">
                    @foreach($item->images ?? [$group->image] as $image)
                        <div class="swiper-slide" data-hash="image-{{$image->id}}">
                            <div class="swiper-zoom-container">
                                <img src="{{\Illuminate\Support\Facades\Storage::url($image->path)}}" alt="Produktbild {{htmlspecialchars($element->name)}}">
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

                <form>
                    <fieldset>
                        <legend>Zeitraum wählen</legend>
                        <div class="row mb-2">
                            <label for="start" class="col-form-label col-lg-2 col-3">Beginn</label>
                            <div class="col">
                                <input type="date" class="form-control" id="start" required>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <label for="end" class="col-form-label col-lg-2 col-3">Ende</label>
                            <div class="col">
                                <input type="date" class="form-control" id="end" required>
                            </div>
                        </div>
                        @if(/* no date selected */0)
                            <div class="row">
                                <div class="col-auto" title="Bitte erst einen Zeitraum auswählen">
                                    <button class="btn btn-secondary btn-lg mt-3" disabled><i class="fa-solid fa-cart-plus"></i> In den Warenkorb</button>
                                </div>
                            </div>
                        @endif
                        <p class="text-success fw-semibold mb-2">In diesem Zeitraum noch 21 Stück verfügbar!</p>
                        <p class="mb-0">Berechnete Tage: 123</p>
                        <p>Preis pro Stück: @money(321)</p>
                    </fieldset>
                    <div class="row mb-3">
                        <label for="amount" class="col-form-label col-lg-2 col-3">Anzahl</label>
                        <div class="col">
                            <input type="number" step="1" min="1" class="form-control" id="amount" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <button class="btn btn-primary btn-lg"><i class="fa-solid fa-cart-plus"></i> In den Warenkorb</button>
                        </div>
                    </div>
                </form>
            @endif
        </div>
    </div>
    @if($element->description || $item?->itemGroup?->description || $item?->deposit)
        <div class="row">
            <div class="col">
                <h3>Beschreibung</h3>
                @if($item?->itemGroup)
                    <p>{{$item->itemGroup->description}}</p>
                @endif
                <p>{{$element->description}}</p>
                @if($item?->deposit)
                    <p>Kaution: @money($item->deposit)</p>
                @endif
            </div>
        </div>
    @endif
</div>
