@extends('layouts.base')

@section('content')
    <div class="container-fluid p-0 d-flex h-100">
        <div id="sidebar" class="d-flex flex-column flex-shrink-0 p-3 shadow bg-light-subtle offcanvas-lg offcanvas-start" style="z-index: var(--bs-offcanvas-zindex);">
            <a href="/" class="navbar-brand p-2 mb-3">
                <img src="/img/asta_logo.png" class="w-100" alt="AStA-Logo">
            </a>
            <ul class="side-nav nav flex-column mb-auto">
                <li><a href="/" class="p-2 d-block" wire:navigate><i class="fa-solid fa-reply"></i>&nbsp;Zum Shop</a></li>
                <li>
                    <a href="/orders" class="p-2 d-flex justify-content-between align-items-baseline" wire:navigate wire:current="active">
                        <span><i class="fa-solid fa-cart-shopping"></i>&nbsp;Bestellungen</span>
                        <livewire:order-count-badge />
                    </a>
                </li>
                <li><a href="/items" class="p-2 d-block" wire:navigate wire:current="active"><i class="fa-solid fa-box"></i>&nbsp;Artikel</a></li>
                <li>
                    <a href="#" class="p-2 d-block" data-bs-toggle="collapse" data-bs-target="#reports" aria-expanded="false" aria-controls="reports">
                        <i class="fa-solid fa-chart-simple"></i>&nbsp;Berichte
                    </a>
                    <ul id="reports" class="nav flex-column collapse ps-4" data-bs-parent="#sidebar" wire:current="show">
                        <li><a href="/reports/availability" class="p-2 d-block" wire:navigate wire:current="active"><i class="fa-solid fa-list-check"></i>&nbsp;Verfügbarkeiten</a></li>
                        <li><a href="/reports/last-bookings" class="p-2 d-block" wire:navigate wire:current="active"><i class="fa-solid fa-clock-rotate-left"></i>&nbsp;Letzte Vermietungen</a></li>
                    </ul>
                </li>
                @can('manage-users')
                    <li><a href="/users" class="p-2 d-block" wire:navigate wire:current="active"><i class="fa-solid fa-users"></i>&nbsp;Benutzerverwaltung</a></li>
                @endcan
                <li><a href="/settings" class="p-2 d-block" wire:navigate wire:current="active">
                        <i class="fa-solid fa-gear"></i>&nbsp;Einstellungen
                    </a>
                </li>
                <li><a href="/profile" class="p-2 d-block" wire:navigate wire:current="active"><i class="fa-solid fa-user"></i>&nbsp;Profil</a></li>
                <li><a href="{{route('logout')}}" class="p-2 d-block" wire:navigate><i class="fa-solid fa-right-from-bracket"></i>&nbsp;Abmelden</a></li>
            </ul>
            <div class="d-lg-none">
                <hr>
                <ul class="side-nav nav flex-column">
                    <li><a href="#" class="p-2 d-block" data-bs-toggle="offcanvas" data-bs-target="#sidebar"><i class="fa-solid fa-angles-left"></i>&nbsp;Menü schließen</a></li>
                </ul>
            </div>
        </div>

        <div class="flex-fill d-flex flex-column h-100">
            <nav class="ps-0 pe-4 py-0 ps-lg-5 py-lg-4 bg-light-subtle shadow @empty($breadcrumbs)d-lg-none @endempty">
                <div class="d-flex align-items-center mx-auto dashboard-content">
                    <a href="#" class="text-black d-lg-none p-4 sidebar-toggler" data-bs-toggle="offcanvas" data-bs-target="#sidebar"><i class="fa-solid fa-bars"></i></a>
                    @isset($breadcrumbs)
                        <ol class="breadcrumb m-0">
                            {{$breadcrumbs}}
                        </ol>
                    @endisset
                </div>
            </nav>

            <div class="p-3 pt-4 p-lg-5 flex-fill overflow-auto">
                <div class="dashboard-content mx-auto">
                    {{$slot}}
                </div>
            </div>
        </div>
    </div>
@endsection
