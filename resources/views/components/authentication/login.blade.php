<div>
    <form wire:submit="login">
        <legend>Anmeldung im Mietservice-Portal</legend>

        @session('status')
        <div class="alert alert-primary">
            {{session('status')}}
        </div>
        @endsession

        <div class="mb-3">
            <label for="username" class="form-label">Benutzername</label>
            <input class="form-control" id="username" autocomplete="username" required @if(!$errors->has('form.login'))autofocus @endif wire:model="form.username">

            @error('form.username')<p class="text-danger small mt-1">{{ $message }}</p>@enderror
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" autocomplete="current-password" @error('form.login')autofocus @enderror required wire:model="form.password">

            @error('form.password')<p class="text-danger small mt-1">{{ $message }}</p>@enderror
        </div>
        <div class="mb-3 form-check">
            <input type="checkbox" class="form-check-input" id="rememberme" wire:model="form.rememberme">
            <label class="form-check-label" for="rememberme">Eingeloggt bleiben</label>
            @error('form.rememberme')<p class="text-danger small mt-1">{{ $message }}</p>@enderror
        </div>

        @error('form.login')<p class="text-danger small">{{ $message }}</p>@enderror

        <button type="submit" class="btn btn-primary w-100 mb-2">Login</button>
        <a href="{{route('password.forgot')}}" class="btn btn-outline-danger w-100" wire:navigate>Passwort vergessen?</a>
    </form>
</div>
