@props([
    'element' => null,
    'order'   => null,
])

@php(/** @var \App\Models\OrderItem|null $element */'')
@php(/** @var \App\Models\Order $order */'')

@if(!$element)
    <th data-responsive-priority="10" class="all">Artikel</th>
    <th class="none">Anmerkung</th>
    @if(!$order->hasSinglePeriod)
        <th data-responsive-priority="50">Zeitraum</th>
    @endif
    <th data-responsive-priority="20">Anzahl</th>
    <th data-responsive-priority="60">Gewöhnlicher Betrag</th>
    <th data-responsive-priority="40">Betrag</th>
    <th data-responsive-priority="30" data-orderable="false" data-searchable="false" data-width="0px">&nbsp;</th>
@else
    <td class="text-left">
        {{$element->item->name}}
        @if($element->comment)
            <i class="fa-regular fa-comment-dots fa-bounce no-animation-when-expanded" title="Anmerkung vorhanden"></i>
        @endif
    </td>
    <td>{{$element->comment}}</td>
    @if(!$order->hasSinglePeriod)
        <td data-sort="{{$element->start->format('c')}}">
            <span @if($order->commonStart && $element->start != $order->commonStart) class="fw-bold" @endif>
                {{$element->start}}
            </span>
            &ndash;
            <span @if($order->commonEnd && $element->end != $order->commonEnd) class="fw-bold" @endif>
                {{$element->end}}
            </span>
        </td>
    @endif
    <td>{{$element->quantity}}</td>
    <td data-sort="{{(int)($element->original_price*100)}}">@money($element->original_price)</td>
    <td data-sort="{{(int)($element->price*100)}}">@money($element->price)</td>
    <td>
        <div class="d-flex detail-flex-wrap gap-2">
            <a class="btn btn-outline-info btn-sm w-100" title="Artikelseite in neuem Tab öffnen"
               href="{{route('shop.item.view', ['item' => $element->item->id, 'slug' => $element->item->slug])}}" target="_blank">
                <span class="detail-only pe-1">Zum Artikel</span><i class="fa-solid fa-arrow-up-right-from-square fa-fw"></i>
            </a>
            <button type="button" class="btn btn-outline-danger btn-sm w-100" title="Artikel aus der Bestellung entfernen"
                    wire:confirm="Möchtest du den Artikel „{{$element->item->name}}“ aus dieser Bestellung entfernen?" wire:click="$parent.removeItem({{$element->id}})">
                <span class="detail-only pe-1">Entfernen</span><i class="fa-solid fa-trash-can fa-fw"></i>
            </button>
            <button type="button" class="btn btn-outline-primary btn-sm w-100" title="Bestellten Artikel bearbeiten"
                    data-bs-toggle="modal" data-bs-target="#editOrderItem" data-bs-order-item="{{$element->id}}">
                <span class="detail-only pe-1">Bearbeiten</span><i class="fa-solid fa-pen-to-square fa-fw"></i>
            </button>
        </div>
    </td>
@endif
