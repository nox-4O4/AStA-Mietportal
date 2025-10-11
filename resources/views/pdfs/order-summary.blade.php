@php($customer = $order->customer)
@extends('pdfs.order-base')

@section('title', "Bestellübersicht #$order->id")

@section('caption', 'Bestellübersicht')

@section('intro-text')
    <p>hier erhältst du eine Übersicht über deine Bestellung Nummer #{{$order->id}} vom {{$order->created_at}}.</p>

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
    @endif
@endsection
