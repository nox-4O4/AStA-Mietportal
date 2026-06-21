@use(App\Models\DisabledDate)

@extends('layouts.base')

@section('content')
    <div class="d-flex flex-column min-vh-100 shop">
        <div class="sticky-top bg-light-subtle shadow">
            <div class="shop-content shop-topbar mx-auto p-3 p-lg-4 d-flex justify-content-between align-items-stretch">
                <a class="logo-small text-body" href="{{route('shop')}}" wire:navigate title="Zur Startseite des AStA-Mietportals">
                    {!! File::get(resource_path('img/logo-asta.svg')) !!}
                </a>
                <div class="flex-grow-1 px-3 search-bar">
                    <livewire:shop.search-form />
                </div>
                <div class="d-flex gap-md-3 gap-1">
                    @auth
                        <a class="h-100 d-flex align-items-center dashboard-button" href="{{route(config('shop.dashboard.defaultRoute'))}}" title="Dashboard" wire:navigate>
                            <span class="me-1 d-none d-md-inline">Dashboard</span>
                            <span class="fa-stack fa-lg h-100 text-secondary-emphasis">{{-- fa-stack is only used to ensure same look and sizing as cart badge icon --}}
                                <i class="fa-solid fa-table-list fa-stack-1x fa-xl"></i>
                            </span>
                        </a>
                    @endauth
                    {{-- Persisting the cart-badge component between navigation prevents a race condition during checkout.
                         If the success page loads prior to the (old) card bage being updated, the local storage cart items are not yet cleared as this happens only when the old cart bage is updated.
                         The success page then renders it's own (new) cart bage component, which leads to $wire.persist updating the cart repository with the (old) local storage data.
                         By having the cart badge component persisted between page loads, the success page won't render a new cart bage component. It will instead use the existing one,
                         where the update will lead to the local storage cart items being cleared. --}}
                    @persist('cart-badge')
                    <livewire:shop.cart-badge />
                    @endpersist
                </div>
            </div>
        </div>
        @php($disabledDate = DisabledDate::getOverlappingRanges(\Carbon\CarbonImmutable::now()) ->first(fn(DisabledDate $d) => trim($d->site_notice) != ''))
        <div class="shop-content mx-auto w-100 p-3 pt-4 p-lg-4 @if(empty($breadcrumbs) && !$disabledDate) pt-lg-5 @endif flex-grow-1">
            @if($disabledDate)
                <div class="alert alert-danger">
                    {{$disabledDate->site_notice}}
                </div>
            @endif
            @isset($breadcrumbs)
                <ol class="breadcrumb justify-content-center flex-nowrap">
                    {{$breadcrumbs}}
                </ol>
            @endisset
            @yield('main', $slot ?? '')
        </div>
        <div class="mt-3 mt-md-5 bg-body-tertiary shadow-sm border-top footer">
            <div class="shop-content mx-auto p-3 p-lg-4 text-body-secondary">
                <div class="row text-center mx-auto max-w-md">
                    <div class="col-md my-1 my-md-0">
                        <a href="https://asta-hka.de/" target="_blank">Homepage</a>
                    </div>
                    <div class="col-md my-1 my-md-0">
                        <a href="https://asta-hka.de/kontakt/" target="_blank">Kontakt</a>
                    </div>
                    <div class="col-md my-1 my-md-0">
                        <a href="https://asta-hka.de/datenschutzrichtlinien/" target="_blank">Datenschutzerklärung</a>
                    </div>
                    <div class="col-md my-1 my-md-0">
                        <a href="https://asta-hka.de/impressum/" target="_blank">Impressum</a>
                    </div>
                    <div class="col-md my-1 my-md-0">
                        @auth
                            <a href="{{route(config('shop.dashboard.defaultRoute'))}}" wire:navigate>Dashboard</a>
                        @else
                            <a href="{{route('login')}}" wire:navigate>Login</a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
