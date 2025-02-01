@props(['element' => null])

@if(!$element)
    <th data-responsive-priority="10" class="all">Name</th>
    <th data-responsive-priority="30">Zugewiesene Artikel</th>
    <th data-responsive-priority="20" data-orderable="false" data-searchable="false" data-width="0px">&nbsp;</th>
@else
    <td>{{$element->name}}</td>
    <td>{{$element->items()->count()}}</td>
    <td>
        <a href="{{route('dashboard.groups.edit', $element->id)}}" class="btn btn-outline-primary btn-sm text-nowrap w-100" wire:navigate title="Gruppe bearbeiten">
            <i class="fa-solid fa-pen-to-square"></i>&nbsp;Bearbeiten
        </a>
    </td>
@endif
