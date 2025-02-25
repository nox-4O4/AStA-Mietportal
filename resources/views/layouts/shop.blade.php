@extends('layouts.base')

@section('content')
    <div class="d-flex flex-column min-h-100 shop">
        <div class="sticky-top bg-light-subtle shadow">
            <div class="shop-content mx-auto p-3 p-lg-4 d-flex justify-content-between align-items-stretch">
                <a class="brand-small d-none d-sm-block" href="{{route('shop')}}" wire:navigate title="Zur Startseite des AStA-Mietportals"></a>
                <div class="flex-grow-1 px-3 search-bar">
                    <livewire:shop.search-form />
                </div>
                <a class="d-flex align-items-center" href="" title="Warenkorb">
                    <span class="me-1 d-none d-md-inline">Warenkorb</span><span class="fa-stack shopping-cart fa-lg h-100">
                        <i class="fa-solid fa-cloud fa-stack-1x loot"></i>{{-- TODO show cloud only when there is someting in the cart --}}
                        <i class="fa-solid fa-cart-shopping fa-stack-1x fa-xl cart"></i>
                    </span>
                </a>
            </div>
        </div>
        <div class="shop-content mx-auto w-100 p-3 pt-4 p-lg-4 pt-lg-5 flex-grow-1">
            @yield('main', $slot ?? '')
        </div>
        <div class="mt-5 bg-body-tertiary shadow-sm border-top footer">
            <div class="shop-content mx-auto p-3 p-lg-4 text-body-secondary">
                <div class="row text-center mx-auto max-w-sm">
                    <div class="col-sm">
                        <a href="https://asta-hka.de/kontakt/" target="_blank">Kontakt</a>
                    </div>
                    <div class="col-sm">
                        <a href="https://asta-hka.de/datenschutzrichtlinien" target="_blank">Datenschutzerklärung</a>
                    </div>
                    <div class="col-sm">
                        <a href="https://asta-hka.de/impressum" target="_blank">Impressum</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
