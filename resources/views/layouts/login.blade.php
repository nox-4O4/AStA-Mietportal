@extends('layouts.base')

@section('content')
    <div class="min-vh-100 d-flex justify-content-center align-items-center">
        <div class="shadow-sm rounded bg-body-tertiary login-container p-3 w-100">
            <div class="m-2 mb-4">
                {!! File::get(resource_path('img/logo-asta.svg')) !!}
            </div>
            <fieldset>
                {{$slot}}
            </fieldset>
        </div>
    </div>
@endsection
