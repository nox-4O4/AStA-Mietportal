<x-slot:breadcrumbs>
    <li class="breadcrumb-item"><i class="fa-solid fa-cart-shopping"></i>&nbsp;Bestellungen</li>
    <li class="breadcrumb-item"><a href="{{route('dashboard.orders.list')}}" wire:navigate>Übersicht</a></li>
    <li class="breadcrumb-item">Bestellung anlegen</li>
</x-slot:breadcrumbs>

<div x-data="{edit: false}">
    <form wire:submit="createOrder">
        <h1 class="mb-4">Bestellung anlegen</h1>

        <x-status-message />

        <div class="row mb-3">
            @include('components.dashboard.orders.order-detail-view.edit-form')
        </div>
        <div class="row mb-3">
            <div class="col">
                <a href="{{route('dashboard.orders.list')}}" wire:navigate class="btn btn-secondary">Abbrechen</a>
                <button type="submit" class="btn btn-primary">Speichern</button>
            </div>
        </div>
    </form>
</div>
