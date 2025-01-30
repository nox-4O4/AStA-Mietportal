<x-slot:breadcrumbs>
    <li class="breadcrumb-item"><i class="fa-solid fa-users"></i>&nbsp;Benutzerverwaltung</li>
    <li class="breadcrumb-item">Übersicht</li>
</x-slot:breadcrumbs>

<div>
    <h1 class="mb-4">Benutzerverwaltung</h1>

    @session('status')
    <div class="alert alert-success">{{session('status')}}</div>
    @endsession

    <livewire:data-table :elements="$this->users" item-component="dashboard.user-list-entry" />
</div>
