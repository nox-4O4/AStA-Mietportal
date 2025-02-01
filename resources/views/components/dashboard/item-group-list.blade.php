<x-slot:breadcrumbs>
    <li class="breadcrumb-item"><i class="fa-solid fa-object-group"></i>&nbsp;Artikelgruppen</li>
    <li class="breadcrumb-item">Übersicht</li>
</x-slot:breadcrumbs>

<div>
    <h1 class="mb-4">Artikelgruppen</h1>

    <x-status-message />

    <livewire:data-table :elements="$this->groups" item-component="dashboard.item-group-list-entry" />
</div>
