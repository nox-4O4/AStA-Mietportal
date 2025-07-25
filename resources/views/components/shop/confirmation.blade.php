<x-slot:breadcrumbs>
    <li class="breadcrumb-item text-center">
        <a href="{{route('shop.cart')}}" wire:navigate class="link-secondary">
            <i class="fa-solid fa-cart-shopping me-1"></i>Warenkorb
        </a>
    </li>
    <li class="breadcrumb-item text-center">
        <a href="{{route('shop.checkout')}}" wire:navigate class="link-secondary">
            <i class="fa-solid fa-table-list me-1"></i><span class="text-nowrap">Daten angeben</span>
        </a>
    </li>
    <li class="breadcrumb-item text-center fw-semibold">
        <i class="fa-regular fa-square-check me-1"></i>Bestätigen
    </li>
</x-slot:breadcrumbs>

<div>
    <h1 class="mb-3">
        <span class="d-none d-sm-inline">Check-out:</span>
        Bestätigung
    </h1>

    <x-status-message />

    <div class="row">
        <div class="mb-3 col-sm-6">
            <h6 class="mb-1">
                Adresse
                <a href="{{route('shop.checkout')}}" wire:navigate class="small fw-normal text-secondary" title="Daten bearbeiten"><i class="fa-solid fa-pen-to-square"></i></a>
            </h6>
            <div class="mb-1">
                <p class="my-0">{{$this->checkoutData->forename}} {{$this->checkoutData->surname}}</p>
                <p class="my-0">{{$this->checkoutData->legalname}}</p>
                <p class="my-0">{{$this->checkoutData->street}} {{$this->checkoutData->number}}</p>
                <p class="my-0">{{$this->checkoutData->zipcode}} {{$this->checkoutData->city}}</p>
            </div>
            <div>
                <p class="my-0"><a href="mailto:{{htmlspecialchars($this->checkoutData->email)}}">{{$this->checkoutData->email}}</a></p>
                @if($this->checkoutData->mobile)
                    <p class="my-0">Telefon: <a href="tel:{{htmlspecialchars($this->checkoutData->mobile)}}">{{$this->checkoutData->mobile}}</a></p>
                @endif
            </div>
        </div>
        <div class="mb-3 col-sm-6">
            <h6 class="mb-0">
                Veranstaltungs&shy;name / Verwendungs&shy;zweck
                <a href="{{route('shop.checkout')}}" wire:navigate class="small fw-normal text-secondary" title="Daten bearbeiten"><i class="fa-solid fa-pen-to-square"></i></a>
            </h6>
            <p class="mb-0">{{$this->checkoutData->eventName}}</p>

            @if($this->checkoutData->note)
                <h6 class="mb-0 mt-3">Bemerkung</h6>
                <p class="mb-0">{{$this->checkoutData->note}}</p>
            @endif
        </div>
    </div>
    <div class="row mb-3">
        <div class="col">
            <h5>
                <span>Warenkorb</span>
                <a href="{{route('shop.cart')}}" wire:navigate class="fs-6 fw-normal text-secondary" title="Warenkorb bearbeiten"><i class="fa-solid fa-pen-to-square"></i></a>
            </h5>
            <table class="table table-striped table-hover table-sm mb-1">
                @foreach($this->cartItemsSorted as $id => $cartItem)
                    <tr>
                        <td>
                            <div class="row flex-nowrap g-2 g-sm-3 g-md-4" wire:key="{{$id}}">
                                <div class="col-auto">
                                    <div class="aspect-1 cart-image img-small position-relative">
                                        @if($cartItem->item->images->isNotEmpty())
                                            <img src="{{\Illuminate\Support\Facades\Storage::url($cartItem->item->images->first()->path)}}" alt="Produktbild {{htmlspecialchars($cartItem->item->name)}}" class="w-100 h-100 object-fit-{{config('shop.image_sizing')}}">
                                        @else
                                            {!! File::get(resource_path('img/product-placeholder.svg')) !!}
                                        @endif
                                    </div>
                                </div>
                                <div class="col me-2 pb-1">
                                    <div class="row align-items-baseline">
                                        <div class="col-md col-12">
                                            <strong>{{$cartItem->item->name}}</strong><br>
                                            <em class="text-muted">
                                                @if($cartItem->start->ne($cartItem->end))
                                                    {{$cartItem->start}} &ndash; {{$cartItem->end}}
                                                @else
                                                    {{$cartItem->start}}
                                                @endif
                                            </em>
                                        </div>
                                        <div class="col-8 col-sm-5 col-md-2 pt-1">
                                            Anzahl: {{$cartItem->amount}}
                                        </div>
                                        <div class="col col-md-2 text-right fw-semibold">
                                            @money($this->priceCalculator->calculatePrice($cartItem->item, $cartItem->start, $cartItem->end) * $cartItem->amount)
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
        </div>
    </div>

    <div class="text-sm-end">
        @if($this->checkoutData->rate != 1)
            Summe: @money($this->totalAmount)<br>
            Rabatt: {{ round((1 - $this->checkoutData->rate) * 100) }}&#x202f;% (vorbehaltlich Prüfung)
            <legend class="mb-0 float-none">Gesamtbetrag: @money($this->totalAmount * $this->checkoutData->rate)</legend>
        @else
            <legend class="mb-0 float-none">Gesamtbetrag: @money($this->totalAmount)</legend>
        @endif
        @if($this->deposit)
            zzgl. Kaution: @money($this->deposit)
        @endif
    </div>

    <form wire:submit="checkout">
        <div class="mb-3">
            @if(\App\Models\Content::fromName('checkout.tos')?->isNotEmpty())
                <div class="form-check mt-3">
                    <input type="checkbox" class="form-check-input @error('tos')is-invalid @enderror" id="tos" wire:model="tos" required>
                    <label class="form-check-label" for="tos">@content('checkout.tos')</label>
                </div>
            @endif
            @error('tos')
            <div class="invalid-feedback d-block">{{$message}}</div>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary">Bestellung abschicken</button>
    </form>
    <small>Dadurch kommt noch kein Mietvertrag zustande.</small>
</div>
