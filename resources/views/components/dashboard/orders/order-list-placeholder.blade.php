<x-slot:breadcrumbs>
    <li class="breadcrumb-item"><i class="fa-solid fa-cart-shopping"></i>&nbsp;Bestellungen</li>
    <li class="breadcrumb-item">Übersicht</li>
</x-slot:breadcrumbs>

<div>
    <h1 class="mb-4">Bestellungen</h1>

    <x-status-message />

    <a href="{{route('dashboard.orders.create')}}" wire:navigate class="btn btn-primary mb-3"><i class="fa-solid fa-plus"></i>&nbsp;Neue Bestellung anlegen</a><br>

    <div class="row text-nowrap">
        <div class="loading-indicator text-center mt-3">
            <p><i class="fa-4x fas fa-spinner fa-pulse"></i></p>
            <p>Liste wird geladen...</p>
        </div>
    </div>
</div>
