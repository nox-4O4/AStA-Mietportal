@props(['element' => null])

@if(!$element)
    <th data-responsive-priority="10" class="all">Name</th>
    <th data-responsive-priority="40">Regulärer Bestand</th>
    <th data-responsive-priority="60">Vorhanden</th>
    <th data-responsive-priority="30">Preis</th>
    <th data-responsive-priority="50">Kaution</th>
    <th data-responsive-priority="70">Sichtbar</th>
    <th data-responsive-priority="45" data-orderable="false" data-searchable="false" data-width="0px">&nbsp;</th>
@else
    <td>{{$element->name}}</td>
    <td>{{$element->amount}}</td>
    <td data-sort="{{(int)!$element->available}}" class="text-left" data-filter="{{$element->available ? 'verfugbar vorhanden' : ''}}">
        @if($element->available)
            <i class="fa-solid fa-check text-success"></i>&nbsp;Ja
        @else
            <i class="fa-solid fa-xmark text-danger"></i>&nbsp;Nein
        @endif
    </td>
    <td data-sort="{{(int)($element->price*100)}}">@money($element->price)</td>
    <td data-sort="{{(int)($element->deposit*100)}}">
        @if($element->deposit)
            @money($element->deposit)
        @else
            <i>keine</i>
        @endif
    </td>
    <td data-sort="{{(int)!$element->visible}}" class="text-left" data-filter="{{$element->visible ? 'sichtbar' : ''}}">
        @if($element->visible)
            <i class="fa-solid fa-check text-success"></i>&nbsp;Ja
        @else
            <i class="fa-solid fa-xmark text-danger"></i>&nbsp;Nein
        @endif
    </td>
    <td>
        <a href="{{route('dashboard.items.edit', $element->id)}}" class="btn btn-outline-primary btn-sm text-nowrap w-100" wire:navigate title="Artikel bearbeiten">
            <i class="fa-solid fa-pen-to-square"></i>&nbsp;Bearbeiten
        </a>
    </td>
@endif
