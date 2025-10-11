@extends('pdfs.din-letter')

@section('head-end')
    <style>
        #order-items-table {
            margin-top: 5pt;
            width: 100%;
        }

        #order-items-table tr.item-row {
            border-top: 1px solid black;
        }

        #order-items-table tr.item-info td {
            padding-top: 0;
        }

        #order-items-table th, #order-items-table td {
            padding: 3pt 0;
            vertical-align: top;
        }

        #order-items-table td + td, #order-items-table th + th {
            padding-left: 10pt;
        }

        #order-items-table tfoot tr:first-child {
            border-top: 3px double black;
        }
    </style>
@endsection

@section('meta-information')
    <p>Bestellnummer: #{{$order->id}}</p>
    <p>Datum: {{$order->updated_at}}</p>
@endsection

@section('content')
    <p>Hallo {{$customer->name}},</p>

    @yield('intro-text')

    @if($order->orderItems->isNotEmpty())
        <table id="order-items-table">
            <thead>
            <tr>
                <th>Pos.</th>
                <th>Artikel</th>
                <th class="right">Anzahl</th>
                @if(!$order->hasSinglePeriod)
                    <th>Mietzeitraum</th>
                @endif
                <th class="right">Betrag</th>
            </tr>
            </thead>
            <tbody>
            @foreach($order->orderItems as $orderItem)
                <tr class="item-row">
                    <td>{{$loop->iteration}}</td>
                    <td>{{$orderItem->item->name}}</td>
                    <td class="right">{{$orderItem->quantity}}</td>
                    @if(!$order->hasSinglePeriod)
                        <td class="no-wrap">{{$orderItem->start}} &ndash; {{$orderItem->end}}</td>
                    @endif
                    <td class="right">@money($orderItem->price)</td>
                </tr>
                @if($orderItem->price != $orderItem->original_price)
                    <tr class="item-info">
                        <td></td>
                        <td colspan="{{$order->hasSinglePeriod ? 3 : 4}}" class="right italic">
                            Enthaltener
                            @if($orderItem->price < $orderItem->original_price)
                            Artikelrabatt:
                            @else
                            Artikelzuschlag:
                            @endif
                            @money(abs($orderItem->original_price - $orderItem->price))
                        </td>
                    </tr>
                @endif
                @if($orderItem->comment)
                    <tr class="item-info">
                        <td></td>
                        <td colspan="{{$order->hasSinglePeriod ? 3 : 4}}">
                            <i>Kommentar:</i>
                            {{$orderItem->comment}}
                        </td>
                    </tr>
                @endif
            @endforeach
            </tbody>
            <tfoot>
            <tr>
                <td colspan="5" class="right">
                    @if($order->rate != 1)
                        <p>
                            Summe: @money($order->orderItems()->sum('price'))
                        </p>
                        <p>
                            Rabatt: {{ round((1 - $order->rate) * 100) }}&#x202f;%
                        </p>
                    @endif
                    <p>
                        <b>Gesamtbetrag: @money($order->total)</b>
                    </p>
                    <p class="italic">
                        Enthaltene USt. (19&#x202f;%): @money($order->total * (1 - 1 / 1.19))
                    </p>
                </td>
            </tr>
            </tfoot>
        </table>
    @else
        <p>Die Bestellung enthält keine Artikel.</p>
    @endif

    @yield('end-text')

    <p class="same-page">
        <b>Veranstaltungsname / Verwendungszweck</b>
        <br>
        <span class="ws-pre-wrap">{{$order->event_name}}</span>
    </p>

    @if($order->note)
        <p style="margin-top: 5pt" class="same-page">
            <b>Anmerkung</b>
            <br>
            <span class="ws-pre-wrap">{{$order->note}}</span>
        </p>
    @endif
@endsection
