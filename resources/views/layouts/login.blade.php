@extends('layouts.default')

@section('content')
    <div class="vh-100 d-flex justify-content-center align-items-center">
        <div class="shadow-sm rounded bg-body-tertiary">
            <form class="p-3 login-form">
                <div class="m-2 mb-4">
                    <img src="/img/asta_logo.png" class="w-100" alt="">
                </div>
                <fieldset>
                    @yield('form-content')
                </fieldset>
            </form>
        </div>
    </div>
@endsection
