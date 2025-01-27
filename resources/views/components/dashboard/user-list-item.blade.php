@props(['element' => null])

@if(!$element)
    <th data-responsive-priority="10">Benutzername</th>
    <th data-responsive-priority="30">Vorname</th>
    <th data-responsive-priority="40">Nachname</th>
    <th data-responsive-priority="70">E-Mail-Adresse</th>
    <th data-responsive-priority="60">Rolle</th>
    <th data-responsive-priority="50" class="text-left">Aktiv</th>
    <th data-responsive-priority="20" data-orderable="false" data-searchable="false">&nbsp;</th>
@else
    <td>{{$element->username}}</td>
    <td>{{$element->forename}}</td>
    <td>{{$element->surname}}</td>
    <td>{{$element->email}}</td>
    <td>{{$element->role->getDescription()}}</td>
    <td data-sort="{{(int)$element->enabled}}" class="text-left" data-filter="{{$element->enabled ? 'aktiv' : ''}}">
        @if($element->enabled)
            <i class="fa-solid fa-check text-success"></i>&nbsp;Ja
        @else
            <i class="fa-solid fa-xmark text-danger"></i>&nbsp;Nein
        @endif
    </td>
    <td>
        <a href="{{route('users.edit', $element->id)}}" class="btn btn-outline-primary btn-sm" wire:navigate title="Benutzer bearbeiten"><i class="fa-solid fa-user-pen"></i></a>
    </td>
@endif
