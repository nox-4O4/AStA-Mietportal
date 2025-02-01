@extends('layouts.base')

@section('content')
    <div class="container-fluid p-0 d-flex h-100">
        <div id="sidebar" class="d-flex flex-column flex-shrink-0 shadow bg-light-subtle offcanvas-lg offcanvas-start sidebar overflow-auto">
            <div class="d-flex mb-3">
                <a href="#" class="text-black d-lg-none p-4 sidebar-toggler" data-bs-dismiss="offcanvas" data-bs-target="#sidebar"><i class="fa-solid fa-angles-left"></i></a>
                <a href="{{route('shop')}}" class="navbar-brand p-4 pb-2 d-none d-lg-inline" wire:navigate><img src="/img/asta_logo.png" class="w-100" alt="AStA-Logo"></a>
                <a href="{{route('shop')}}" class="navbar-brand brand-small flex-fill p-2 d-lg-none" wire:navigate></a>
            </div>
            <ul class="side-nav nav flex-column mb-auto">
                <li><a href="{{route('shop')}}" class="px-4 py-2 d-block" wire:navigate><i class="fa-solid fa-reply"></i>&nbsp;Zum Shop</a></li>
                <li>
                    <a href="{{route('dashboard.orders')}}" class="px-4 py-2 d-flex justify-content-between align-items-baseline" wire:navigate wire:current="active">
                        <span><i class="fa-solid fa-cart-shopping"></i>&nbsp;Bestellungen</span>
                        <livewire:dashboard.order-count-badge />
                    </a>
                </li>
                <li><a href="{{route('dashboard.items.list')}}" class="px-4 py-2 d-block" wire:navigate wire:current="active"><i class="fa-solid fa-box"></i>&nbsp;Artikel</a></li>
                <li><a href="{{route('dashboard.groups.list')}}" class="px-4 py-2 d-block" wire:navigate wire:current="active"><i class="fa-solid fa-object-group"></i>&nbsp;Artikelgruppen</a></li>
                <li>
                    <a href="{{route('dashboard.reports')}}" class="px-4 py-2 d-block" data-bs-toggle="collapse" data-bs-target="#reportsMenu" aria-expanded="false" aria-controls="reportsMenu">
                        <i class="fa-solid fa-chart-simple"></i>&nbsp;Berichte
                    </a>
                    <ul id="reportsMenu" class="nav flex-column collapse" data-bs-parent="#sidebar" wire:current="show" href="{{route('dashboard.reports')}}">{{-- href needed for livewire current styling --}}
                        <li><a href="{{route('dashboard.reports.availability')}}" class="ps-5 p-2 d-block" wire:navigate wire:current="active"><i class="fa-solid fa-list-check"></i>&nbsp;Verfügbarkeiten</a></li>
                        <li><a href="{{route('dashboard.reports.last-bookings')}}" class="ps-5 p-2 d-block" wire:navigate wire:current="active"><i class="fa-solid fa-clock-rotate-left"></i>&nbsp;Letzte Vermietungen</a></li>
                    </ul>
                </li>
                @can('manage-users')
                    <li><a href="{{route('dashboard.users.list')}}" class="px-4 py-2 d-block" wire:navigate wire:current="active"><i class="fa-solid fa-users"></i>&nbsp;Benutzerverwaltung</a></li>
                @endcan
                <li><a href="/settings" class="px-4 py-2 d-block" wire:navigate wire:current="active">
                        <i class="fa-solid fa-gear"></i>&nbsp;Einstellungen
                    </a>
                </li>
            </ul>
            <ul class="side-nav nav flex-column">
                <li><a href="{{route('dashboard.profile')}}" class="px-4 py-2 d-block" wire:navigate wire:current="active"><i class="fa-solid fa-user"></i>&nbsp;Profil</a></li>
                <li><a href="{{route('logout')}}" class="px-4 py-2 d-block" wire:navigate><i class="fa-solid fa-right-from-bracket"></i>&nbsp;Abmelden</a></li>
            </ul>
        </div>

        <div class="flex-fill d-flex flex-column h-100 min-w-0">
            <nav class="ps-0 pe-4 py-0 px-lg-5 py-lg-4 bg-light-subtle shadow topbar @empty($breadcrumbs)d-lg-none @endempty">
                <div class="d-flex align-items-center mx-auto dashboard-content">
                    <a href="#" class="text-black d-lg-none p-4 sidebar-toggler" data-bs-toggle="offcanvas" data-bs-target="#sidebar"><i class="fa-solid fa-bars"></i></a>
                    @isset($breadcrumbs)
                        <ol class="breadcrumb m-0">
                            {{$breadcrumbs}}
                        </ol>
                    @endisset
                </div>
            </nav>

            <div class="p-3 pt-4 p-lg-5 flex-fill overflow-auto flex-grow-0" id="dashboard-content-container">
                <div class="dashboard-content mx-auto">
                    {{$slot}}
                </div>
            </div>
        </div>
    </div>

    <script>
        // quick & dirty workaround for stale (cached) open menu when navigating back
        // manually remove elements and hide menu
        if (document.getElementsByClassName('offcanvas-backdrop').length) {
            for (const item of document.getElementsByClassName('offcanvas-backdrop'))
                item.remove()
            document.body.removeAttribute('style')
            sidebar.classList.remove('show')
            sidebar.removeAttribute('aria-modal')
            sidebar.removeAttribute('role')
        }
    </script>
@endsection
