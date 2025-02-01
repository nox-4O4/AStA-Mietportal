<x-slot:breadcrumbs>
    <li class="breadcrumb-item"><i class="fa-solid fa-box"></i>&nbsp;Artikel</li>
    <li class="breadcrumb-item">Übersicht</li>
</x-slot:breadcrumbs>

<div>
    <h1 class="mb-4">Artikel</h1>

    <x-status-message />

    <livewire:data-table :elements="$this->items" item-component="dashboard.item-list-entry" />{{-- to override default sorting, use :element-attributes='["data-order" => "[[ 1, \"asc\" ]]"]' --}}
</div>
