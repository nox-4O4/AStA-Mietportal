<x-slot:breadcrumbs>
    <li class="breadcrumb-item"><i class="fa-solid fa-users"></i>&nbsp;Benutzerverwaltung</li>
    <li class="breadcrumb-item">Übersicht</li>
</x-slot:breadcrumbs>

<div>
    <h1 class="mb-4">Benutzerverwaltung</h1>

    <x-status-message />

    <a href="{{route('dashboard.users.create')}}" wire:navigate class="btn btn-primary mb-3"><i class="fa-solid fa-user-plus"></i>&nbsp;Neuen Benutzer anlegen</a>

    <livewire:data-table :elements="$this->users" item-component="dashboard.user-list-entry" />
</div>
