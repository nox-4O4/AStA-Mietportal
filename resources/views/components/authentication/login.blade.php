<div>
    <form wire:submit="login">
        <legend>Anmeldung im Mietservice-Portal</legend>

        <x-status-message />

        <div class="mb-3">
            <label for="username" class="form-label">Benutzername</label>
            <input class="form-control @error('form.username')is-invalid @enderror" id="username" autocomplete="username" required @if(!$errors->has('form.login'))autofocus @endif wire:model="form.username">
            @error('form.username')
            <div class="invalid-feedback">{{$message}}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control @error('form.password')is-invalid @enderror" id="password" autocomplete="current-password" @error('form.login')autofocus @enderror required wire:model="form.password">

            @error('form.password')
            <div class="invalid-feedback">{{$message}}</div>
            @enderror
        </div>
        <div class="mb-3 form-check">
            <input type="checkbox" class="form-check-input @error('form.rememberme')is-invalid @enderror" id="rememberme" wire:model="form.rememberme">
            <label class="form-check-label" for="rememberme">Eingeloggt bleiben</label>

            @error('form.rememberme')
            <div class="invalid-feedback">{{$message}}</div>
            @enderror
        </div>

        @error('form.login')<p class="text-danger small">{{ $message }}</p>@enderror

        <button type="submit" class="btn btn-primary w-100 mb-2">Login</button>
        <a href="{{route('password.forgot')}}" class="btn btn-outline-danger w-100" wire:navigate>Passwort vergessen?</a>

        <p class="text-center mt-3 mb-0">
            <a class="text-body-secondary" href="{{route('shop')}}" wire:navigate>Zurück zum Shop</a>
        </p>
    </form>
</div>
