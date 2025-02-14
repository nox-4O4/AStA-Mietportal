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
</head>
<body>
@yield('content')
<div class="position-fixed text-right p-3" style="top:70px;right:0;z-index: 999999">
    <p class="m-0 d-sm-none">[none]</p>
    <p class="m-0 d-none d-sm-block d-md-none">sm</p>
    <p class="m-0 d-none d-md-block d-lg-none">md</p>
    <p class="m-0 d-none d-lg-block d-xl-none">lg</p>
    <p class="m-0 d-none d-xl-block d-xxl-none">xl</p>
    <p class="m-0 d-none d-xxl-block">xxl</p>
    <a href="{{route('dashboard')}}" wire:navigate>Dashboard</a>
</div>
</body>
</html>
