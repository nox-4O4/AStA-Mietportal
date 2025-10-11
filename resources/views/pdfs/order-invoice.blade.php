@php($customer = $invoice->customer)
@extends('pdfs.order-base')

@section('title', "Rechnung $invoice->name")

@section('caption', 'Rechnung')

@section('meta-information')
    <p>Rechnungsnummer: {{$invoice->name}}</p>
    @parent
@endsection

@section('intro-text')
    <p>hier erhältst du die Rechnung zu deiner Bestellung Nummer #{{$order->id}} vom {{$order->created_at}}.</p>

    @if($order->orderItems->isNotEmpty() && $order->hasSinglePeriod)
        <p>Mietzeitraum: {{$order->common_start}} &ndash; {{$order->common_end}}.</p>
    @endif
@endsection

@section('end-text')
    @if($invoice->total_amount > 0)
        <div style="margin-bottom: 5pt" class="same-page">
            Der Gesamtbetrag ist bei Abholung der Gegenstände, spätestens jedoch zum {{$order->first_start}}, in bar zu entrichten.

            <p style="font-size: 7.9pt;">
                Wir weisen darauf hin, dass der Rechnungsempfänger gemäß § 286 Abs. 3 BGB 30 Tage nach Fälligkeit und Zugang der Rechnung in Verzug gerät, ohne dass es einer Mahnung bedarf.
                Die Verzugszinsen belaufen sich bei einem Ver&shy;braucher auf 5 Prozentpunkte und bei einem Unternehmer auf 9 Prozentpunkte über dem jeweiligen Basiszinssatz.
            </p>
        </div>
    @endif
@endsection

@section('body-start')
    @if($preview)
        <style>
            #watermark {
                position: fixed;
                width: 100%;
                top: 50%;
                margin-top: -3cm;
                font-size: 115pt;
                font-weight: bold;
                text-align: center;
                opacity: 0.2;
                transform: rotate(-55deg);
            }
        </style>
        <div id="watermark">VORSCHAU</div>
    @endif
@endsection
