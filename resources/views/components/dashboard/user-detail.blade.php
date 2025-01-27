<x-slot:breadcrumbs>
    <li class="breadcrumb-item"><i class="fa-solid fa-users"></i>&nbsp;Benutzerverwaltung</li>
    <li class="breadcrumb-item"><a href="{{route('users.list')}}" wire:navigate>Übersicht</a></li>
    <li class="breadcrumb-item">Details zu „{{$user->username}}“</li>
</x-slot:breadcrumbs>

<div>
    <h1 class="mb-4">Benutzer bearbeiten</h1>

    Details zu {{$user->username}}.
</div>
