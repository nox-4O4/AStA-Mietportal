<div>
    @if($errors->hasAny('token', 'email'))
        <legend>Passwort zurücksetzen</legend>

        <div class="alert alert-danger">
            Der Link ist nicht mehr gültig.
        </div>
        <a href="/login" class="btn btn-primary w-100" wire:navigate>Zurück zum Login</a>
    @else
        <form wire:submit="resetPassword">
            <legend>Passwort zurücksetzen</legend>

            <input class="d-none" autocomplete="username">{{-- for accessibility, see https://goo.gl/9p2vKq --}}

            <div class="mb-3">
                <label for="password" class="form-label">Neues Passwort</label>
                <input class="form-control" id="password" autocomplete="new-password" wire:model.blur="password" type="password" autofocus required
                       x-on:input="document.getElementById('password_confirmation').value !== '' && $wire.set('password_confirmation', document.getElementById('password_confirmation').value)" {{-- force trigger validation for confirmation field --}}>
                @error('password')<p class="text-danger small mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="mb-3">
                <label for="password_confirmation" class="form-label">Passwort wiederholen</label>
                <input class="form-control" id="password_confirmation" autocomplete="new-password" wire:model.blur="password_confirmation" type="password" autofocus required>
                @error('password_confirmation')<p class="text-danger small mt-1">{{ $message }}</p>@enderror
            </div>

            @session('error')
            <div class="alert alert-danger">
                {{session('error')}}
            </div>
            @endsession

            <button type="submit" class="btn btn-primary w-100 mb-2">Absenden</button>
            <a href="/login" class="btn btn-outline-secondary w-100" wire:navigate>Zurück zum Login</a>
        </form>
    @endif
</div>
