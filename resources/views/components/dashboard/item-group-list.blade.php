<x-slot:breadcrumbs>
    <li class="breadcrumb-item"><i class="fa-solid fa-object-group"></i>&nbsp;Artikelgruppen</li>
    <li class="breadcrumb-item">Übersicht</li>
</x-slot:breadcrumbs>

<div>
    <h1 class="mb-4">Artikelgruppen</h1>

    <x-status-message />

    <a href="{{route('dashboard.groups.create')}}" wire:navigate class="btn btn-primary mb-3"><i class="fa-solid fa-plus"></i>&nbsp;Neue Gruppe anlegen</a>

    <livewire:data-table :elements="$this->groups" item-component="dashboard.item-group-list-entry" />
</div>
