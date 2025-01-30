<x-slot:breadcrumbs>
    <li class="breadcrumb-item"><i class="fa-solid fa-box"></i>&nbsp;Artikel</li>
    <li class="breadcrumb-item">Übersicht</li>
</x-slot:breadcrumbs>

<div>
    <h1 class="mb-4">Artikel</h1>

    @session('status')
    <div class="alert alert-success">{{session('status')}}</div>
    @endsession

    <livewire:data-table :elements="$this->items" item-component="dashboard.item-list-entry" :element-attributes='["data-order" => "[[ 1, \"asc\" ]]"]' />
</div>
