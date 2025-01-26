@extends('layouts.base')

@section('content')
    <div class="min-vh-100 d-flex justify-content-center align-items-center">
        <div class="shadow-sm rounded bg-body-tertiary login-container p-3">
            <div class="m-2 mb-4">
                <img src="/img/asta_logo.png" class="w-100" alt="">
            </div>
            <fieldset>
                {{$slot}}
            </fieldset>
        </div>
    </div>
@endsection
