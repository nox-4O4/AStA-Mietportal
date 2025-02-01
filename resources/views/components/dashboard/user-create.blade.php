<x-slot:breadcrumbs>
    <li class="breadcrumb-item"><i class="fa-solid fa-users"></i>&nbsp;Benutzerverwaltung</li>
    <li class="breadcrumb-item">Account erstellen</li>
</x-slot:breadcrumbs>

<div>
    <h1 class="mb-4">Account erstellen</h1>

    <form wire:submit="createUser" x-data="{'active': true}">
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
                <div class="form-text" x-show="active">An diese Adresse wird nach dem Speichern eine E-Mail zum Festlegen des Passworts gesendet.</div>
                <div class="form-text" x-show="!active" x-cloak>Da der Benutzer deaktiviert ist, wird keine E-Mail zum Festlegen des Passworts gesendet.</div>
            </div>
        </div>
        <div class="row mb-3">
            <label for="username" class="col-sm-3 col-xl-2 col-form-label">Benutzername</label>
            <div class="col">
                <input class="form-control @error('username')is-invalid @enderror" id="username" wire:model="username" placeholder="Leer lassen, um Benutzernamen automatisch festzulegen">
                @error('username')
                <div class="invalid-feedback">{{$message}}</div>
                @enderror
            </div>
        </div>
        <fieldset class="row mb-3">
            <legend class="col-sm-3 col-xl-2 col-form-label pt-0">Rolle</legend>
            <div class="col">
                @foreach(\App\Enums\UserRole::cases() as $role)
                    <div class="form-check">
                        <input class="form-check-input @error('role')is-invalid @enderror" type="radio" name="role" value="{{$role->value}}" id="role_{{$role->name}}" wire:model="role">
                        <label class="form-check-label" for="role_{{$role->name}}">
                            {{$role->getDescription()}}
                        </label>
                        <div class="form-text mt-0 mb-1">{{$role->getExplanation()}}</div>
                    </div>
                @endforeach
                @error('role')
                <div class="invalid-feedback d-block">{{$message}}</div>
                @enderror
            </div>
        </fieldset>
        <div class="row mb-3">
            <div class="col offset-sm-3 offset-xl-2">
                <div class="form-check form-switch">
                    <input class="form-check-input @error('enabled')is-invalid @enderror" type="checkbox" role="switch" id="enabled" wire:model="enabled" @@change="active=$el.checked">
                    <label class="form-check-label" for="enabled">Benutzer aktiv</label>
                    @error('enabled')
                    <div class="invalid-feedback mt-0 mb-1">{{$message}}</div>
                    @enderror
                    <div class="form-text mt-0 mb-1">Ein deaktivierter Benutzer kann sich nicht am Portal anmelden und wird beim Anlegen keine Passwort-E-Mail erhalten.</div>
                </div>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col">
                <button type="submit" class="btn btn-primary">Account anlegen</button>
            </div>
        </div>
    </form>

</div>
