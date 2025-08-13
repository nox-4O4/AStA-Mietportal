{{-- @formatter:off --}}
<x-mail::layout>
{{-- Header --}}
<x-slot:header>
<x-mail::header :url="config('app.url')">
<span style="display: none">{{config('app.name')}}</span><img src="{{ empty($message->getHtmlBody()) ? $message->embed(public_path('/img/asta_logo.png')) : '' }}" {{-- Only embed image during rendering of HTML part. Plain text message part gets rendered afterwards. --}}
     style="height: 75px; max-height: 75px;"
     alt="{{config('app.name')}}">
</x-mail::header>
</x-slot:header>

{{-- Greeting --}}
<h1>{{ $greeting ?? 'Hallo!' }}</h1>

{{-- Intro Lines --}}
@foreach ($introLines as $line)
{{ $line }}

@endforeach

{{-- Action Button --}}
@isset($actionText)
<?php
    $color = match ($level) {
        'success', 'error' => $level,
        default => 'primary',
    };
?>
<x-mail::button :url="$actionUrl" :color="$color">
{{ $actionText }}
</x-mail::button>
@endisset

@section('outro-lines')
{{-- Outro Lines --}}
@foreach ($outroLines as $line)
{{ $line }}

@endforeach
@show

{{-- Salutation --}}
@if (! empty($salutation))
{{ $salutation }}
@else
Viele Grüße<br>
{{ config('app.name') }}
@endif

{{-- Subcopy --}}
@isset($actionText)
<x-slot:subcopy>
<x-mail::subcopy>
Falls du die „{{$actionText}}“-Schaltfläche nicht anklicken kannst, kopiere folgende URL in deinen Browser um sie manuell aufzurufen:
<span class="break-all"><a href="{{ $displayableActionUrl }}">{{ $actionUrl }}</a></span>
</x-mail::subcopy>
</x-slot:subcopy>
@endisset

{{-- Footer --}}
<x-slot:footer>
<x-mail::footer>
{{ $footer ?? '' }}
</x-mail::footer>
</x-slot:footer>
</x-mail::layout>
