<div class="mb-3 col-xl-3 col-sm-6">
    <h5>Besteller</h5>
    <div class="mb-1">
        <p class="my-0">{{$order->customer->name}}</p>
        <p class="my-0">{{$order->customer->legalname}}</p>
        <p class="my-0">{{$order->customer->street}} {{$order->customer->number}}</p>
        <p class="my-0">{{$order->customer->zipcode}} {{$order->customer->city}}</p>
    </div>
    <div>
        <p class="my-0"><a href="mailto:{{htmlspecialchars($order->customer->email)}}">{{$order->customer->email}}</a></p>
        @if($order->customer->mobile)
            <p class="my-0">Telefon: <a href="tel:{{htmlspecialchars($order->customer->mobile)}}">{{$order->customer->mobile}}</a></p>
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
        <div class="col ws-pre-wrap">{{$order->event_name}}</div>
    </div>
    @if($order->orderItems->isNotEmpty())
        <div class="row mt-2 mt-sm-1">
            <div class="col-xl-3 col-sm-3 col-md-2 fw-semibold">Mietzeitraum</div>
            <div class="col">
                {{$order->firstStart}} &ndash; {{$order->lastEnd}}
                @if(!$order->hasSinglePeriod)
                    <p class="alert alert-warning m-0 p-0 px-1 fw-bold small d-inline-block">Produkte mit abweichenden Zeiträumen gebucht!</p>
                @endif
            </div>
        </div>
    @endif
    @if($order->note)
        <div class="row mt-2 mt-sm-1">
            <div class="col-xl-3 col-sm-3 col-md-2 fw-semibold">Bemerkung</div>
            <div class="col ws-pre-wrap">{{$order->note}}</div>
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
    @if($order->totalDiscount || $order->itemDiscount)
        <p class="my-0">
            <span class="fw-semibold">
                @if($order->totalDiscount < 0)
                    Enthaltener Aufschlag:
                @else
                    Gewährter Rabatt:
                @endif
            </span>
            <span class="text-nowrap">
                @money(abs($order->totalDiscount))

                @if(!$order->itemDiscount)
                    ({{ round((1 - $order->rate) * 100) }}&#x202f;%)
                @endif
            </span>

            @if($order->rate == 1 && $order->totalDiscount > 0)
                (Artikelrabatt)
            @endif
        </p>
        @if($order->itemDiscount && $order->rate != 1)
            <p class="mb-0">Bestehend aus</p>
            <ul>
                <li>
                    @if($order->itemDiscount < 0)
                        Artikelzuschlag
                    @else
                        Artikelrabatt
                    @endif
                    (@money(abs($order->itemDiscount)))
                </li>
                <li>Prozentualer Abzug ({{ round((1 - $order->rate) * 100) }}&#x202f;%)</li>
            </ul>
        @endif
    @endif
</div>
