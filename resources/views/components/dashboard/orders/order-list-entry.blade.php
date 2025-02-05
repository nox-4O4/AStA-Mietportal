@props(['element' => null])

@use(App\Enums\OrderStatus)
@php(/** @var \App\Models\Order $element */'')

@if(!$element)
    <th data-responsive-priority="10" class="all text-left">Bestell&shy;nummer</th>
    <th data-responsive-priority="90">Bestelldatum</th>
    <th data-responsive-priority="20">Besteller</th>
    <th data-responsive-priority="80">Veranstaltungs&shy;name</th>
    <th data-responsive-priority="50">Frühester Beginn</th>
    <th data-responsive-priority="60">Spätestes Ende</th>
    <th data-responsive-priority="70">Betrag</th>
    <th data-responsive-priority="30" data-type="text" class="status">Status</th>
    <th data-responsive-priority="40" data-orderable="false" data-searchable="false" data-width="0px">&nbsp;</th>
@else
    <td data-sort="{{$element->id}}" class="text-left">#{{$element->id}}</td>
    <td data-sort="{{$element->created_at?->format('c')}}">{{$element->created_at}}</td>
    <td>{{$element->customer->name}}</td>
    <td>{{$element->event_name}}</td>
    <td data-sort="{{$element->firstStart?->format('c')}}">{{$element->firstStart}}</td>
    <td data-sort="{{$element->lastEnd?->format('c')}}">{{$element->lastEnd}}</td>
    <td data-sort="{{(int)(($total = $element->orderItems->sum('price'))*100)}}">@money($total)</td>
    <td data-sort="{{array_search($element->status, OrderStatus::cases())}}" data-search="{{$element->status->value}} {{$element->status->getShortName()}}">
        <x-dashboard.orders.status-badge :status="$element->status" />
    </td>
    <td>
        <a href="{{route('dashboard.orders.view', $element->id)}}" class="btn btn-outline-primary btn-sm text-nowrap w-100" wire:navigate title="Bestellung einsehen">
            <i class="fa-solid fa-eye"></i>
            <span class="d-none d-sm-inline detail-hide">Betrachten</span>
            <span class="detail-only">Betrachten</span>
        </a>
    </td>
@endif
