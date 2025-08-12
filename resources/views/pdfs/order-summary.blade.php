<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <style>
        @page {
            margin: 4.5cm 2cm 3.7cm 2.5cm; /* DIN 5008 Form B + extra space for footer */
        }

        /*
         * default elements overrides
         */
        body {
            font-family: sans-serif;
            font-size: 10pt;
        }

        p {
            margin: 0 0 2pt 0;
        }

        th {
            text-align: left;
        }

        td, th {
            margin: 0;
            padding: 0;
        }

        table {
            border-collapse: collapse;
        }

        /*
         * Specific element styles
         */
        .page-number:after {
            content: counter(page);
        }

        #header, #footer {
            position: fixed;
            width: 100%;
        }

        #header {
            top: -4.5cm; /* reverses margin */
        }

        #footer {
            bottom: -2.7cm; /* reverses margin while still keeping 1cm space at bottom */
            font-size: 7.5pt;
            color: #404040;
            border-top: 0.3pt solid #808080;
            padding-top: 5pt;
        }

        #footer p {
            margin: 0;
        }

        #footer a {
            text-decoration: none;
            color: inherit;
        }

        #footer-table {
            table-layout: fixed;
            width: 100%;
        }

        #footer-table td {
            vertical-align: top;
            text-align: center;
        }

        #logo {
            position: absolute;
            top: 2cm;
            right: 0;
            width: 6cm;
        }

        #address-table {
            width: 100%;
            margin-bottom: 2em;
        }

        #address-block {
            width: 8.5cm; /* DIN 5008 */
            height: 4.5cm; /* DIN 5008 */
        }

        #meta-information {
            text-align: right;
            vertical-align: bottom;
        }

        #address-block p, #meta-information p {
            margin: 0;
        }

        #caption {
            font-weight: bold;
            margin-bottom: 1em;
            font-size: 12pt;
        }

        #own-address {
            font-size: 7.5pt; /* so that complete address fits into one line */
        }

        #customer-address {
            min-height: 2cm;
        }

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


        /*
         * Utilities
         */
        .italic {
            font-style: italic;
        }

        .right {
            text-align: right;
        }

        .no-wrap {
            white-space: nowrap;
        }

        .ws-pre-wrap {
            white-space: pre-wrap;
        }

        .same-page {
            page-break-inside: avoid;
        }
    </style>
    <title></title>
</head>
<body>
<div id="header">
    <img id="logo" src="{{public_path('/img/asta_logo.png')}}" alt="AStA-Logo">
</div>
<div id="footer">
    <table id="footer-table">
        <tr>
            <td>
                <p>Studierendenschaft HsKA (KöR)</p>
                <p>Moltkestraße 30</p>
                <p>76133 Karlsruhe</p>
                <p>USt-IdNr.: DE298552575</p>
            </td>
            <td>
                <p>IBAN: DE41&#x202f;6605&#x202f;0101&#x202f;0108&#x202f;2110&#x202f;79</p>
                <p>BIC: KARSDE66XXX</p>
                <p>Sparkasse Karlsruhe</p>
            </td>
            <td>
                <p>Web: <a href="https://www.asta-hka.de">www.asta-hka.de</a></p>
                <p>E-Mail: <a href="mailto:asta@asta-hka.de">asta@asta-hka.de</a></p>
                <p>Tel.: <a href="tel:+497219252868">+49&#x202f;(721)&#x202f;925&#x202f;2868</a></p>
            </td>
        </tr>
    </table>
    <p class="right"><span class="page-number">Seite </span></p>
</div>

<table id="address-table">
    <tr>
        <td id="address-block">
            <p id="own-address">Studierendenschaft HsKA, Moltkestraße 30, 76133 Karlsruhe</p>
            <div id="customer-address">
                <p>{{$order->customer->name}}</p>
                <p>{{$order->customer->legalname}}</p>
                <p>{{$order->customer->street}} {{$order->customer->number}}</p>
                <p>{{$order->customer->zipcode}} {{$order->customer->city}}</p>
            </div>
        </td>
        <td id="meta-information">
            <p>Bestellnummer: #{{$order->id}}</p>
            <p>Datum: {{$order->created_at}}</p>
        </td>
    </tr>
</table>

<p id="caption">Bestellübersicht</p>

<p>Hallo {{$order->customer->name}},</p>
<p>hier erhältst Du eine Übersicht über deine Bestellung Nummer #{{$order->id}} vom {{$order->created_at}}.</p>

@if($order->orderItems->isNotEmpty())
    @if($order->hasSinglePeriod)
        <p>Mietzeitraum: {{$order->common_start}} &ndash; {{$order->common_end}}.</p>
    @endif

    <p>
        @if($order->deposit)
            Kaution: @money($order->deposit)
        @else
            Ohne Kaution.
        @endif
    </p>

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
</body>
</html>
