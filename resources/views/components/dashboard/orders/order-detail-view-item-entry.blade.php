@props([
    'element'          => null,
    'individualPeriod' => false,
])

@php(/** @var \App\Models\OrderItem $element */'')

@if(!$element)
    <th data-responsive-priority="10" class="all">Artikel</th>
    <th class="none">Anmerkung</th>
    @if($individualPeriod)
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
    @if($individualPeriod)
        <td data-sort="{{$element->start->format('c')}}">
            <span @if($element->start != $element->order->commonStart) class="fw-bold" @endif>
                {{$element->start}}
            </span>
            &ndash;
            <span @if($element->end != $element->order->commonEnd) class="fw-bold" @endif>
                {{$element->end}}
            </span>
        </td>
    @endif
    <td>{{$element->quantity}}</td>
    <td data-sort="{{(int)($element->original_price*100)}}">@money($element->original_price)</td>
    <td data-sort="{{(int)($element->price*100)}}">@money($element->price)</td>
    <td data-sort="{{(int)($element->price*100)}}">
        <a href="{{route('shop.article.view', ['item' => $element->item->id, 'slug' => $element->item->slug])}}" target="_blank" title="Artikelseite in neuem Tab öffnen" class="text-nowrap w-100 d-inline-block text-center">
            <span class="detail-only">Zum Artikel</span><span class="d-none d-md-inline d-lg-none d-xl-inline">Zum Artikel</span><i class="fa-solid fa-arrow-up-right-from-square ps-1"></i>
        </a>
    </td>
@endif
