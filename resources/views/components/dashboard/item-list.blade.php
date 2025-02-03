<x-slot:breadcrumbs>
    <li class="breadcrumb-item"><i class="fa-solid fa-box"></i>&nbsp;Artikel</li>
    <li class="breadcrumb-item">Übersicht</li>
</x-slot:breadcrumbs>

<div>
    <h1 class="mb-4">Artikel</h1>

    <x-status-message />

    <a href="{{route('dashboard.items.create')}}" wire:navigate class="btn btn-primary mb-3"><i class="fa-solid fa-plus"></i>&nbsp;Neuen Artikel anlegen</a><br>

    <livewire:data-table :elements="$this->items" item-component="dashboard.item-list-entry" />{{-- to override default sorting, use :element-attributes='["data-order" => "[[ 1, \"asc\" ]]"]' --}}
</div>
