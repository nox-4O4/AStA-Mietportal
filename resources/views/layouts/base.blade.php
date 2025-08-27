<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="icon" type="image/png" href="/favicon-96x96.png" sizes="96x96" />
    <link rel="icon" type="image/svg+xml" href="/favicon.svg" />
    <link rel="shortcut icon" href="/favicon.ico" />
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png" />
    <meta name="apple-mobile-web-app-title" content="AStA-Mietportal" />
    <meta name="theme-color" content="#f0f0ef" />
    <link rel="manifest" href="/site.webmanifest" />

    <title>{{ config('app.name') }}{{ isset($title) ? " - $title" : '' }}</title>

    @vite(['resources/css/app.scss', 'resources/js/app.js'])
    @yield('head')

    {{-- We cannot put darkmode switch into app.js as script has to run as soon as possible after body is loaded to prevent flashing of light mode elements.
         See comments in https://github.com/livewire/livewire/pull/9149 and https://github.com/livewire/livewire/pull/9319 --}}
    <script>
        window.UpdateTheme = () => {
            const colorMode = window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light";
            document.querySelector("html").setAttribute("data-bs-theme", colorMode);
        }
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', window.UpdateTheme)
    </script>
</head>
<body>
<script>window.UpdateTheme()</script>
@yield('content')
@if(config('app.debug'))
    <div class="position-fixed text-right p-3" style="top:70px;right:0;z-index: 999999">
        <p class="m-0 d-sm-none">[none]</p>
        <p class="m-0 d-none d-sm-block d-md-none">sm</p>
        <p class="m-0 d-none d-md-block d-lg-none">md</p>
        <p class="m-0 d-none d-lg-block d-xl-none">lg</p>
        <p class="m-0 d-none d-xl-block d-xxl-none">xl</p>
        <p class="m-0 d-none d-xxl-block">xxl</p>
    </div>
    <div class="position-fixed text-right p-3 small" style="bottom:70px;right:0;z-index: 999999">
        <a href="{{route('dashboard')}}" wire:navigate>Dashboard</a><br>
        <button class="btn btn-link p-0 btn-sm" onclick="Livewire.all().forEach(c=>c.$wire.$refresh())">Refresh Components</button>
        <br>
        <livewire:random />
    </div>
@endif
@yield('body_end')
</body>
</html>
