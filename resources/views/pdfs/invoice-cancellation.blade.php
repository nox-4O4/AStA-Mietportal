@php($customer = $invoice->customer)
@extends('pdfs.din-letter')

@section('title', "Rechnungsstorno $invoice->name")

@section('caption', 'Rechnungsstorno')

@section('meta-information')
    <p>Rechnungsnummer: {{$invoice->name}}-storno</p>
    <p>Bestellnummer: #{{$invoice->number}}</p>
    <p>Datum: {{new \Carbon\CarbonImmutable('now')}}</p>
@endsection

@section('content')
    <p>Hallo {{$customer->name}},</p>

    <p>hiermit wird die Rechnung {{$invoice->name}} vom {{$invoice->created_at}} zur Bestellung #{{$invoice->number}} storniert. Die genauen Artikel und Zeiträume kannst du der Rechnung {{$invoice->name}} entnehmen.</p>

    <div style="margin: 1em 0">
        <p><b>Gesamtbetrag: @money(-$invoice->total_amount)</b></p>
        <p class="italic">Enthaltene USt. (19&#x202f;%): @money(-$invoice->total_amount * (1 - 1 / 1.19))</p>
    </div>

    <p>Sollte durch die Rechnungsstornierung eine Rückerstattung erforderlich werden, setze dich bitte mit uns zur Klärung der Zahlungsmodalitäten in Verbindung.</p>
@endsection
