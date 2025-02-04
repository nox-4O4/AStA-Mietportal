@props(['element' => null])

@use(App\Enums\OrderStatus)
@php(/** @var \App\Models\Order $element */'')

@if(!$element)
    <th data-responsive-priority="10" class="all">Bestell&shy;nummer</th>
    <th data-responsive-priority="90">Bestelldatum</th>
    <th data-responsive-priority="20">Besteller</th>
    <th data-responsive-priority="80">Veranstaltungs&shy;name</th>
    <th data-responsive-priority="50">Frühester Beginn</th>
    <th data-responsive-priority="60">Spätestes Ende</th>
    <th data-responsive-priority="70">Betrag</th>
    <th data-responsive-priority="30" data-type="text" class="status">Status</th>
    <th data-responsive-priority="40" data-orderable="false" data-searchable="false" data-width="0px">&nbsp;</th>
@else
    <td>#{{$element->id}}</td>
    <td data-sort="{{$element->created_at}}">{{$element->created_at?->format('d.m.Y')}}</td>
    <td>{{$element->customer->name}}</td>
    <td>{{$element->event_name}}</td>
    <td data-sort="{{$firstDate = $element->orderItems()->orderBy('start')->first()?->start}}">{{$firstDate?->format('d.m.Y')}}</td>
    <td data-sort="{{$lastDate = $element->orderItems()->orderBy('end', 'desc')->first()?->end}}">{{$lastDate?->format('d.m.Y')}}</td>
    <td data-sort="{{(int)(($total = $element->orderItems->sum('price'))*100)}}">@money($total)</td>
    <td data-sort="{{array_search($element->status, OrderStatus::cases())}}" data-search="{{$element->status->value}} {{$element->status->getShortName()}}">
        <span class="badge {{[
                        'pending'    => 'text-bg-warning',
                        'waiting'    => 'text-bg-purple',
                        'processing' => 'text-body bg-info-subtle',
                        'completed'  => 'text-bg-success',
                        'cancelled'  => 'text-bg-secondary',
                    ][$element->status->value] ?? ''}}">
            {{$element->status->getShortName()}}
        </span>
    </td>
    <td>
        <a href="{{route('dashboard.orders.view', $element->id)}}" class="btn btn-outline-primary btn-sm text-nowrap w-100" wire:navigate title="Bestellung einsehen">
            <i class="fa-solid fa-eye"></i>
            <span class="d-none d-sm-inline detail-hide">Betrachten</span>
            <span class="detail-only">Betrachten</span>
        </a>
    </td>
@endif
