@extends('layouts.base')

@section('content')
    <div class="d-flex flex-column min-vh-100 shop">
        <div class="sticky-top bg-light-subtle shadow">
            <div class="shop-content mx-auto p-3 p-lg-4 d-flex justify-content-between align-items-stretch">
                <a class="brand-small d-none d-sm-block" href="{{route('shop')}}" wire:navigate title="Zur Startseite des AStA-Mietportals"></a>
                <div class="flex-grow-1 px-3 search-bar">
                    <livewire:shop.search-form />
                </div>
                <div>
                    <livewire:shop.cart-badge />
                </div>
            </div>
        </div>
        <div class="shop-content mx-auto w-100 p-3 pt-4 p-lg-4 @empty($breadcrumbs) pt-lg-5 @endempty flex-grow-1">
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
                        @if(auth()->check())
                            <a href="{{route(config('shop.dashboard.defaultRoute'))}}" wire:navigate>Dashboard</a>
                        @else
                            <a href="{{route('login')}}" wire:navigate>Login</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
