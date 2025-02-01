<div>
    @if($errors->hasAny('token', 'email'))
        <legend>Passwort zurücksetzen</legend>

        <div class="alert alert-danger">
            Der Link ist nicht mehr gültig.
        </div>
        <a href="{{route('login')}}" class="btn btn-primary w-100" wire:navigate>Zurück zum Login</a>
    @else
        <form wire:submit="resetPassword">
            <legend>Passwort {{$user && !$user->password ? 'festlegen' : 'zurücksetzen'}}</legend>

            <input class="d-none" autocomplete="username">{{-- for accessibility, see https://goo.gl/9p2vKq --}}

            <div class="mb-3">
                <label for="password" class="form-label">Neues Passwort</label>
                <input class="form-control @error('password')is-invalid @enderror" id="password" autocomplete="new-password" wire:model.blur="password" type="password" autofocus required
                       x-on:input="document.getElementById('password_confirmation').value !== '' && $wire.set('passwordConfirmation', document.getElementById('password_confirmation').value)" {{-- force trigger validation for confirmation field --}}>
                @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="password_confirmation" class="form-label">Passwort wiederholen</label>
                <input class="form-control @error('passwordConfirmation')is-invalid @enderror" id="password_confirmation" autocomplete="new-password" wire:model.blur="passwordConfirmation" type="password" required>
                @error('passwordConfirmation')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary w-100 mb-2">Absenden</button>
            <a href="{{route('login')}}" class="btn btn-outline-secondary w-100" wire:navigate>Zurück zum Login</a>
        </form>
    @endif
</div>
