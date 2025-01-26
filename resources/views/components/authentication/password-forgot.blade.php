<div>
    <form wire:submit="sendPasswordResetLink">
        <legend>Passwort zurücksetzen</legend>
        <p>Falls du dein Passwort vergessen hast, kannst du dir hier einen Link zum Zurücksetzen deines Passworts zuschicken lassen.</p>

        <div class="mb-3">
            <label for="email" class="form-label">E-Mail-Adresse</label>
            <input class="form-control" id="email" autocomplete="email" wire:model="email" type="email" autofocus>

            @error('email')<p class="text-danger small mt-1">{{ $message }}</p>@enderror
        </div>

        <button type="submit" class="btn btn-primary w-100 mb-2">Absenden</button>
        <a href="{{route('login')}}" class="btn btn-outline-secondary w-100" wire:navigate>Zurück zum Login</a>
    </form>
</div>
