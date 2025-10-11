<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <style>
        @page {
            margin: 2.7cm 2cm 2cm 2.5cm; /* DIN 5008 Form A */
        }

        /*
         * default elements overrides
         */
        body {
            font-family: sans-serif;
            font-size: 10pt;
        }

        a[href] {
            color: #444;
        }

        p {
            margin: 0 0 2pt 0;
        }

        td, th {
            text-align: left;
            font-weight: normal;
            margin: 0;
            padding: 0;
            vertical-align: top;
        }

        table {
            border-collapse: collapse;
        }

        h1, h2 {
            font-size: inherit;
        }

        /*
         * Specific element styles
         */
        .hand-input {
            border-bottom: 1px solid black;
            display: inline-block;
            height: 1cm;
        }

        #title {
            margin-bottom: 15pt;
        }

        .checkbox {
            display: inline-block;
            width: 0.8em;
            height: 0.8em;
            border: 1px solid black;
            margin-bottom: -0.1em;
        }

        .contracting-parties {
            margin-bottom: 15pt;
        }

        .contracting-parties td {
            padding-bottom: 5pt;
        }

        .contracting-parties td:first-child {
            padding-right: 15pt;

        }

        .contracting-parties td:last-child {
            padding-left: 15pt;
        }

        .signature {
            margin: 15pt 0;
        }

        .hint {
            font-size: 7pt;
            font-style: italic;
            color: #444;
        }

        .item-comment {
            font-size: 8pt;
            font-style: italic;
        }

        #order-items-table {
            margin-bottom: 2em;
        }

        #order-items-table tbody tr {
            border-top: 1px solid black;
        }

        #order-items-table th {
            vertical-align: bottom;
        }

        #order-items-table th, #order-items-table td {
            padding: 3pt 0;
        }

        #order-items-table td + td, #order-items-table th + th {
            padding-left: 10pt;
        }

        #footnote {
            position: absolute;
            bottom: 0;
            border-top: 1px solid black;
            font-size: 8pt;
            padding-top: 3pt;
        }

        #footnote div:first-child {
            float: left;
        }

        #agb {
            page-break-before: always;
            margin: -1.7cm -1cm -1cm -1.5cm; /* results in 1cm border */
        }

        #header {
            position: fixed;
            top: -1.7cm;
            right: -1cm;
        }

        /*
         * Utilities
         */
        .right {
            text-align: right;
        }

        .center {
            text-align: center;
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

        .new-page {
            page-break-before: always;
        }

        .w-100 {
            width: 100%;
        }

        table.two-columns td {
            width: 50%;
        }
    </style>
    <title>Mietvertrag #{{$order->id}}</title>
    <meta name="author" content="AStA HKA">
</head>
<body>

<div id="header" class="right">
    <b>#{{$order->id}}</b>
</div>

<h1 id="title" class="center">Vertrag über die Miete von Gegenständen der Studierendenschaft HsKA</h1>

<table class="w-100 contracting-parties two-columns">
    <tr>
        <td>Zwischen der</td>
        <td>und dem Mieter</td>
    </tr>
    <tr>
        <td>
            <p>
                Studierendenschaft HsKA<br>
                Moltkestraße 30<br>
                76133 Karlsruhe,
            </p>
            <p>
                vertreten durch<br>
                <span class="hand-input w-100"></span>
                <span class="hand-input w-100"></span>
            </p>
        </td>
        <td>
            <p>
                @trim
                {{$order->customer->name}}
                @if($order->customer->legalname)
                    <br>{{$order->customer->legalname}}
                @endif
                @if($order->customer->street || $order->customer->number)
                    <br>{{$order->customer->street}} {{$order->customer->number}}
                @endif
                @if($order->customer->zipcode || $order->customer->city)
                    <br>{{$order->customer->zipcode}} {{$order->customer->city}}
                @endif
                @endtrim,
            </p>
            <span class="checkbox"></span> vertreten durch
            <span class="hand-input w-100"></span>
            <span class="hand-input w-100"></span>
        </td>
    </tr>
</table>

<p>wird ein Mietvertrag über die Vermietung der auf der Folgeseite aufgeführten Gegenstände geschlossen.</p>

<div style="margin-bottom: 1em">
    Vom Mieter angegebener Veranstaltungsname bzw. Verwendungszweck:
    <div style="margin-left: 1em">
        <span class="ws-pre-wrap">{{$order->event_name}}</span>
    </div>
</div>

<p>
    Der Mietzins beträgt <b>@money($order->total)</b>, die Kaution beträgt <b>@money($order->deposit)</b>.
    @if($order->hasSinglePeriod)
    Der Mietzeitraum beginnt am <b>{{$order->common_start}}</b> und endet am <b>{{$order->common_end}}</b>.
    @else
    Der jeweilige Mietzeitraum ist bei den Gegenständen nachfolgend angegeben.
    @endif
</p>

<p>Ferner gelten die allgemeinen Bestimmungen der Studierendenschaft HsKA zur Vermietung von Gegenständen&sup1;.</p>
<div id="footnote">
    <div>&sup1;:&nbsp;</div>
    <div>
        Die Bestimmungen lassen sich umseitig, im Büro des AStAs oder unter <a href="{{route('tos')}}">{{route('tos')}}</a> einsehen.
    </div>
</div>

<p>Ich habe diese zur Kenntnis genommen und akzeptiere die Vertragsbedingungen:</p>

<table class="signature two-columns w-100">
    <tr>
        <td><span class="hand-input w-100"></span></td>
        <td><span class="hand-input w-100"></span></td>
    </tr>
    <tr>
        <td class="center">(Mieter)</td>
        <td class="center">(Datum)</td>
    </tr>
</table>

<table class="signature two-columns w-100">
    <tr>
        <td><span class="hand-input w-100"></span></td>
        <td><span class="hand-input w-100"></span></td>
    </tr>
    <tr>
        <td class="center">(Vermieter)</td>
        <td class="center">(Datum)</td>
    </tr>
</table>

@include('pdfs.tos-content')

@if($order->orderItems->isNotEmpty())
    <h2 class="new-page">Mietgegenstände</h2>

    <p>Die folgenden Gegenstände sind Bestandteil des Mietvertrags:</p>

    <table id="order-items-table" class="w-100">
        <thead>
        <tr>
            <th>Pos.</th>
            <th>Artikel</th>
            @if(!$order->hasSinglePeriod)
                <th>Mietzeitraum</th>
            @endif
            <th class="right">Anzahl</th>
            <th class="right">
                <span class="hint no-wrap">Bei der Rückgabe auszufüllen</span><br>
                Zurückgegeben
            </th>
        </tr>
        </thead>
        <tbody>
        @foreach($order->orderItems as $orderItem)
            <tr>
                <td>{{$loop->iteration}}</td>
                <td>
                    {{$orderItem->item->name}}
                    @if($orderItem->comment)
                        <p class="item-comment">
                            Kommentar: {{$orderItem->comment}}
                        </p>
                    @endif
                </td>
                @if(!$order->hasSinglePeriod)
                    <td>
                        <span class="no-wrap">{{$orderItem->start}} &ndash;</span><br>
                        <span class="no-wrap">{{$orderItem->end}}</span>
                    </td>
                @endif
                <td class="right">{{$orderItem->quantity}}</td>
                <td></td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <div class="same-page">
        <p class="hint">Falls das Datum der Übergabe vom Vertragsdatum abweicht, Datum mit angeben.</p>
        <table class="w-100 contracting-parties two-columns">
            <tr>
                <td>Ich habe die Gegenstände in ordnungsgemäßem Zustand erhalten:</td>
                <td>Ich habe den Mietzins und die Kaution erhalten:</td>
            </tr>
            <tr>
                <td class="center">
                    <span class="hand-input w-100"></span>
                    (Mieter)
                </td>
                <td class="center">
                    <span class="hand-input w-100"></span>
                    (Vermieter)
                </td>
            </tr>
        </table>
    </div>


    <div class="same-page">
        <p class="hint">Ab hier bei der Rückgabe auszufüllen</p>
        <p>Die Gegenstände wurden zurückgegeben (s. Tabelle).</p>
        <p>
            <span class="checkbox"></span> Der Zustand war ordnungsgemäß<br>
            <span class="checkbox"></span> Der Zustand war zu beanstanden:
            <span class="hint" style="border-top: 1px solid black;margin-bottom:-2em;padding-top:0.1em;display:inline-block;">Grund zur Beanstandung angeben. Bei Bedarf Rückseite verwenden.</span><br>
        </p>

        <p style="margin: 1em 0">
            Einbehaltene Kaution: <span class="hand-input" style="width: 7em"></span>
        </p>

        <table class="w-100 contracting-parties two-columns">
            <tr>
                <td>Ich habe die Gegenstände zurückgegeben und die Kaution bzw. Restkaution erhalten:</td>
                <td>Die Gegenstände wurden zurückgegeben.</td>
            </tr>
            <tr>
                <td class="center">
                    <span class="hand-input w-100"></span>
                    (Mieter, Datum)
                </td>
                <td class="center">
                    <span class="hand-input w-100"></span>
                    (Vermieter, Datum)
                </td>
            </tr>
        </table>
    </div>
@endif
</body>
</html>
