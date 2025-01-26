<x-slot:breadcrumbs>
    <li><i class="fa-solid fa-user"></i>&nbsp;Profil</li>
</x-slot:breadcrumbs>

<div>
    <h1 class="mb-4">Hallo {{auth()->user()->forename}}!</h1>

    <h3 class="mb-3">Profilinformationen</h3>
    <form wire:submit="updateProfile">
        <div class="row mb-3">
            <label for="forename" class="col-sm-3 col-xl-2 col-form-label">Vorname</label>
            <div class="col">
                <input class="form-control @error('forename')is-invalid @enderror" id="forename" wire:model="forename" required>
                @error('forename')
                <div class="invalid-feedback">{{$message}}</div>
                @enderror
            </div>
        </div>
        <div class="row mb-3">
            <label for="surname" class="col-sm-3 col-xl-2 col-form-label">Nachname</label>
            <div class="col">
                <input class="form-control @error('surname')is-invalid @enderror" id="surname" wire:model="surname" required>
                @error('surname')
                <div class="invalid-feedback">{{$message}}</div>
                @enderror
            </div>
        </div>
        <div class="row mb-3">
            <label for="email" class="col-sm-3 col-xl-2 col-form-label">E-Mail-Adresse</label>
            <div class="col">
                <input type="email" class="form-control @error('email')is-invalid @enderror" id="email" wire:model="email" required>
                @error('email')
                <div class="invalid-feedback">{{$message}}</div>
                @enderror
            </div>
        </div>
        <div class="row mb-3">
            <label for="username" class="col-sm-3 col-xl-2 col-form-label">Benutzername</label>
            <div class="col">
                @can('manage-users')
                    <input class="form-control @error('username')is-invalid @enderror" id="username" wire:model="username" required>
                    @error('username')
                    <div class="invalid-feedback">{{$message}}</div>
                    @enderror
                @else
                    <input class="form-control" id="username" disabled>
                    <div class="form-text">Nur ein Administrator kann deinen Benutzernamen ändern.</div>
                @endcan
            </div>
        </div>
        <div class="d-flex align-items-baseline">
            @if($errors->hasAny('forename','surname','email','username'))
                <button type="submit" class="btn btn-primary">Änderungen übernehmen</button>
            @else
                <button type="submit"
                        id="btn_{{rand()}}" {{-- to prevent livewire from reusing button with error condition --}}
                        class="btn btn-outline-primary"
                        wire:dirty.class="btn-primary"
                        wire:dirty.class.remove="btn-outline-primary"
                        wire:target="forename,surname,email,username">
                    Änderungen übernehmen
                </button>
            @endif
        </div>
    </form>

    <h3 class="mb-3 mt-4">Passwort ändern</h3>
    <form wire:submit="updatePassword">
        <input class="d-none" autocomplete="username" value="{{$username}}">{{-- for accessibility, see https://goo.gl/9p2vKq --}}

        <div class="row mb-3">
            <label for="new_password" class="col-sm-3 col-xl-2 col-form-label">Neues Passwort</label>
            <div class="col">
                <input type="password" class="form-control @error('newPassword')is-invalid @enderror" id="new_password" autocomplete="new-password" wire:model.blur="newPassword" required>
                @error('newPassword')
                <div class="invalid-feedback">{{$message}}</div>
                @enderror
            </div>
        </div>
        <div class="row mb-3">
            <label for="new_password_confirmation" class="col-sm-3 col-xl-2 col-form-label">Neues Passwort wiederholen</label>
            <div class="col">
                <input type="password" class="form-control @error('newPasswordConfirmation')is-invalid @enderror" id="new_password_confirmation" autocomplete="new-password" wire:model.blur="newPasswordConfirmation" required>
                @error('newPasswordConfirmation')
                <div class="invalid-feedback">{{$message}}</div>
                @enderror
            </div>
        </div>
        <div class="row mb-3">
            <label for="current_password" class="col-sm-3 col-xl-2 col-form-label">Aktuelles Passwort bestätigen</label>
            <div class="col">
                <input type="password" class="form-control @error('currentPassword')is-invalid @enderror" id="current_password" autocomplete="current-password" wire:model="currentPassword" required>
                @error('currentPassword')
                <div class="invalid-feedback">{{$message}}</div>
                @enderror
            </div>
        </div>
        @session('message')
            <div class="alert alert-success small mb-2 p-2">{{session('message')}}</div>
        @endsession
        <button type="submit" class="btn btn-outline-primary" wire:target="currentPassword" wire:dirty.class="btn-primary" wire:dirty.class.remove="btn-outline-primary">Passwort ändern</button>
    </form>
</div>
