<x-slot:breadcrumbs>
    <li class="breadcrumb-item"><i class="fa-solid fa-cart-shopping"></i>&nbsp;Bestellungen</li>
    <li class="breadcrumb-item"><a href="{{route('dashboard.orders.list')}}" wire:navigate>Übersicht</a></li>
    <li class="breadcrumb-item">Bestellung #{{$order->id}}</li>
</x-slot:breadcrumbs>

<div>
    <h1 class="mb-4">
        Bestellung #{{$order->id}}
        <x-dashboard.orders.status-badge :status="$order->status" class="fs-6" />
    </h1>

    <x-status-message />

    <div class="row">
        <div class="mb-3 col-xl-3 col-sm-6">
            <h5>Besteller</h5>
            <div class="mb-1">
                <p class="my-0">{{$order->customer->name}}</p>
                <p class="my-0">{{$order->customer->legalname}}</p>
                <p class="my-0">{{$order->customer->street}} {{$order->customer->number}}</p>
                <p class="my-0">{{$order->customer->zipcode}} {{$order->customer->city}}</p>
            </div>
            <div>
                <p class="my-0"><a href="mailto:{{htmlentities($order->customer->email)}}">{{$order->customer->email}}</a></p>
                @if($order->customer->mobile)
                    <p class="my-0">Telefon: <a href="tel:{{htmlentities($order->customer->mobile)}}">{{$order->customer->mobile}}</a></p>
                @endif
            </div>
        </div>
        <div class="mb-3 col-xl-6 order-1 order-xl-0">
            <h5>Bestellung</h5>
            <div class="row">
                <div class="col-xl-3 col-sm-3 col-md-2 fw-semibold">Bestelldatum</div>
                <div class="col">{{$order->created_at}}</div>
            </div>
            <div class="row mt-2 mt-sm-1">
                <div class="col-xl-3 col-sm-3 col-md-2 fw-semibold">Veranstaltung</div>
                <div class="col">{{$order->event_name}}</div>
            </div>
            @if($order->orderItems->isNotEmpty())
                <div class="row mt-2 mt-sm-1">
                    <div class="col-xl-3 col-sm-3 col-md-2 fw-semibold">Mietzeitraum</div>
                    <div class="col">
                        {{$order->firstStart}} &ndash; {{$order->lastEnd}}
                        @if(!$order->hasSinglePeriod())
                            <p class="alert alert-warning m-0 p-0 px-1 fw-bold small d-inline-block">Produkte mit abweichenden Zeiträumen gebucht!</p>
                        @endif
                    </div>
                </div>
            @endif
            @if($order->note)
                <div class="row mt-2 mt-sm-1">
                    <div class="col-xl-3 col-sm-3 col-md-2 fw-semibold">Bemerkung</div>
                    <div class="col">{{$order->note}}</div>
                </div>
            @endif
        </div>
        <div class="mb-3 col-xl-3 col-sm">
            <h5>Infos</h5>
            <p class="my-0">
                <span class="fw-semibold">Mietbetrag:</span>
                @money($order->total)
            </p>
            <p class="my-0">
                <span class="fw-semibold">Kaution:</span>
                @money($order->deposit)
            </p>
            @if($order->totalDiscount)
                <p class="my-0">
                    <span class="fw-semibold">Gewährter Rabatt:</span>
                    @money($order->totalDiscount)
                </p>
            @endif
        </div>
    </div>
    @if($order->comments->isNotEmpty())
        <div class="row mb-3">
            <div class="col-auto">
                <h5>Kommentare</h5>
                @foreach($order->comments as $comment)
                    <div class="alert alert-secondary bg-gradient px-2 py-1 mb-2">
                        <p class="small fw-semibold mb-1 text-italic text-right text-muted">
                            {{$comment->user?->name ?? 'Gelöschter Benutzer'}}
                            am&nbsp;{{$comment->created_at}}
                            um&nbsp;{{$comment->created_at->format('H:i:s')}}&nbsp;Uhr
                        </p>
                        <p class="m-0">
                            {{$comment->comment}}
                        </p>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
    <div class="row mb-3">
        <div class="col">
            <h5>Artikel</h5>
            @if($order->orderItems->isNotEmpty())
                <livewire:data-table
                        class="child-responsive"
                        :elements="$order->orderItems"
                        :element-attributes="['data-hide-empty-children' => true]"
                        item-component="dashboard.orders.order-detail-view-item-entry"
                        :item-component-data="['individualPeriod' => !$order->hasSinglePeriod()]"
                />
            @else
                <p>Diese Bestellung enthält noch keine Artikel.</p>
            @endif
        </div>
    </div>
</div>
