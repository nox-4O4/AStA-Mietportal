<html>
<head>
    @yield('head-start')
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
            max-width: 8.5cm;
            word-break: break-word;
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
            max-height: 10cm;
            overflow: hidden;
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
    <title>@yield('title')</title>
    <meta name="author" content="AStA HKA">

    @yield('head-end')
</head>
<body>
@yield('body-start')
<div id="header">
    <img id="logo" src="data:image/svg+xml;base64,{{base64_encode(File::get(resource_path('img/logo-asta.svg')))}}" alt="AStA-Logo">
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
                <p>{{$customer->name}}</p>
                <p>{{$customer->legalname}}</p>
                <p>{{$customer->street}} {{$customer->number}}</p>
                <p>{{$customer->zipcode}} {{$customer->city}}</p>
            </div>
        </td>
        <td id="meta-information">
            @yield('meta-information')
        </td>
    </tr>
</table>

<p id="caption">@yield('caption')</p>

@yield('content')

@yield('body-end')
</body>
</html>
