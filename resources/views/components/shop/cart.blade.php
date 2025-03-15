<div x-data="{items: $persist($wire.entangle('items').live).as('cart-items')}"
     @@storage.window="$event.key == 'cart-items' && $wire.$refresh()"
>
    <h1>Warenkorb</h1>

    @if($items)
        <p>
            Artikel im Warenkorb werden nicht reserviert.
            Die folgenden Artikel befinden sich im Warenkorb:
        </p>

        <table class="table table-striped table-hover table-sm mb-1">
            @foreach($this->cartItemsSorted as $id => $cartItem)
                <tr>
                    <td>
                        <div class="row flex-nowrap" wire:key="{{$id}}">
                            <div class="col-auto">
                                <a href="{{route('shop.item.view', [$cartItem->item->id, \App\Util\Helper::GetItemSlug($cartItem->item->name)])}}" class="text-body" wire:navigate>
                                    <div class="aspect-1 cart-image img-small position-relative">
                                        @if($cartItem->item->images->isNotEmpty())
                                            <img src="{{\Illuminate\Support\Facades\Storage::url($cartItem->item->images->first()->path)}}" alt="Produktbild {{htmlspecialchars($cartItem->item->name)}}" class="w-100 h-100 object-fit-{{config('shop.image_sizing')}}">
                                        @else
                                            {!! File::get(resource_path('img/product-placeholder.svg')) !!}
                                        @endif
                                    </div>
                                </a>
                            </div>
                            <div class="col me-2 pb-1">
                                <div class="row align-items-baseline">
                                    <div class="col-md col-12">
                                        <div class="row align-items-baseline">
                                            <div class="col">
                                                <a href="{{route('shop.item.view', [$cartItem->item->id, \App\Util\Helper::GetItemSlug($cartItem->item->name)])}}" wire:navigate class="link-body-emphasis text-decoration-none">
                                                    <strong>{{$cartItem->item->name}}</strong>
                                                </a><br>
                                                <em class="text-muted">
                                                    @if($cartItem->start->ne($cartItem->end))
                                                        {{$cartItem->start}} &ndash; {{$cartItem->end}}
                                                    @else
                                                        {{$cartItem->start}}
                                                    @endif
                                                </em>
                                                @error("items.$id.range")
                                                <div class="text-danger small">{{$message}}</div>
                                                @enderror
                                            </div>
                                            <div class="col-auto d-md-none pt-2">
                                                <button type="button" class="btn btn-sm btn-danger baseline-alignment" wire:click="removeItem('{{$id}}')" title="Artikel aus dem Warenkorb entfernen">
                                                    <i class="fa-solid fa-xmark"></i>
                                                    <span class="d-none d-lg-inline">Entfernen</span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-8 col-sm-5 col-md-2 pt-1">
                                        @php($maximum = $cartItem->item->getMaximumAvailabilityInRange($cartItem->start, $cartItem->end))
                                        <input wire:model.live.debounce.500ms="items.{{$id}}.amount"
                                               wire:loading.class="is-loading"
                                               wire:loading.attr="readonly"
                                               wire:loading.class.remove="is-invalid is-valid"
                                               type="number"
                                               step="1"
                                               min="0"
                                               @if($maximum !== true) max="{{$maximum}}" @endif
                                               class="form-control @error("items.$id.amount")is-invalid @else is-valid @enderror"
                                               title="@error("items.$id.amount")Gewählte Anzahl nicht verfügbar @else Gewählte Anzahl verfügbar @enderror"
                                               id="amount-{{$id}}">
                                        @error("items.$id.amount")
                                        <div class="invalid-feedback">{{$message}}</div>
                                        @enderror
                                    </div>
                                    <div class="col col-md-2 text-right fw-semibold">
                                        @money($this->priceCalculator->calculatePrice($cartItem->item, $cartItem->start, $cartItem->end) * $cartItem->amount)
                                    </div>
                                    <div class="col-auto d-none d-md-block">
                                        <button type="button" class="btn btn-sm btn-danger baseline-alignment" wire:click="removeItem('{{$id}}')" title="Artikel aus dem Warenkorb entfernen">
                                            <i class="fa-solid fa-xmark"></i>
                                            <span class="d-none d-lg-inline">Entfernen</span>
                                        </button>
                                    </div>
                                </div>
                                @if($cartItem->comment)
                                    <div class="row">
                                        <p class="mb-0">
                                            <em>Kommentar: </em>
                                            {{$cartItem->comment}}
                                        </p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </td>
                </tr>
            @endforeach
        </table>
        <div class="ps-0 mb-3">
            <legend class="mb-0 float-none">Summe: @money($this->totalAmount)</legend>
            @if($this->totalAmount > 0)
                <p class="small text-muted mb-0">Ein etwaiger Studi-Rabatt kann im nächsten Schritt berechnet werden.</p>
            @endif
        </div>
        <div class="row justify-content-between">
            <div class="col mb-3">
                <div class="row">
                    <div class="col-sm-auto">
                        @if($errors->hasAny('items.*'))
                            <button class="btn btn-outline-primary w-100" disabled><i class="fa-solid fa-arrow-right"></i>&nbsp;Zum Checkout</button>
                        @else
                            <a href="{{route('shop.checkout')}}" class="btn btn-primary w-100" wire:navigate><i class="fa-solid fa-arrow-right"></i>&nbsp;Zum Checkout</a>
                        @endif
                    </div>
                </div>
                @if($errors->hasAny('items.*'))
                    <p class="text-danger small m-0">
                        Der Warenkorb enthält ungültige Elemente. Korrigiere oder entferne diese, um mit dem Checkout fortzufahren.
                    </p>
                @endif
            </div>
            <div class="col-sm-auto mb-3">
                <button class="btn btn-danger w-100" wire:click="resetCart" wire:confirm="Sollen alle Artikel aus dem Warenkorb entfernt werden?"><i class="fa-solid fa-trash-can"></i>&nbsp;Warenkorb zurücksetzen</button>
            </div>
        </div>
    @else
        <div x-show="Object.keys(items).length" x-cloak>
            <i class="fas fa-spinner fa-pulse fa-lg"></i>&nbsp;Wird geladen...
        </div>
        <div x-show="!Object.keys(items).length" x-cloak>
            <p>Es befinden sich noch keine Artikel im Warenkorb.</p>
            <p><a href="{{route('shop')}}" class="btn btn-success btn-lg" wire:navigate><i class="fa-solid fa-right-long"></i> Zu den Artikeln</a></p>
        </div>
    @endif
</div>
