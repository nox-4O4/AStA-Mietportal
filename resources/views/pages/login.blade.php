@extends('layouts.login')

@section('form-content')
    <legend>Anmeldung im Mietservice-Portal</legend>

    <div class="mb-3">
        <label for="username" class="form-label">Benutzername</label>
        <input class="form-control" id="username" autocomplete="username">
    </div>
    <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input type="password" class="form-control" id="password" autocomplete="current-password">
    </div>
    <div class="mb-3 form-check">
        <input type="checkbox" class="form-check-input" id="rememberme">
        <label class="form-check-label" for="rememberme">Eingeloggt bleiben</label>
    </div>

    <button type="submit" class="btn btn-primary w-100 mb-2">Login</button>
    <a href="/reset-password" class="btn btn-outline-danger w-100" wire:navigate>Passwort vergessen?</a>
@endsection
