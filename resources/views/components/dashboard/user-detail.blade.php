<x-slot:breadcrumbs>
    <li class="breadcrumb-item"><i class="fa-solid fa-users"></i>&nbsp;Benutzerverwaltung</li>
    <li class="breadcrumb-item"><a href="{{route('dashboard.users.list')}}" wire:navigate>Übersicht</a></li>
    <li class="breadcrumb-item">Details zu „{{$username}}“</li> {{-- header is currently not dynamic (livewire won't update $username). To change that, see https://joshhanley.com.au/articles/how-to-structure-your-layout-file-for-livewire#:~:text=But%20what%20about%20the%20header%3F --}}
</x-slot:breadcrumbs>

<div>
    <h1 class="mb-4">Benutzer bearbeiten</h1>

    <h3 class="mb-3 mt-4">Profil von {{$forename}} {{$surname}}</h3>

    <div class="small mb-3">
        @if($user->created_at)
            <p class="m-0">Benutzer erstellt am {{date_format($user->created_at, 'd.m.Y')}}</p>
        @endif
        <p class="m-0">
            @if($user->last_login)
                Letzter Login am {{date_format($user->last_login, 'd.m.Y')}}
            @else
                Bisher noch nicht eingeloggt.
            @endif
        </p>
    </div>

    <form wire:submit="updateUser">
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
                <input class="form-control @error('username')is-invalid @enderror" id="username" wire:model="username" required>
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
                    <input class="form-check-input @error('enabled')is-invalid @enderror" type="checkbox" role="switch" id="flexSwitchCheckDefault" wire:model="enabled">
                    <label class="form-check-label" for="flexSwitchCheckDefault">Benutzer aktiv</label>
                    @error('enabled')
                    <div class="invalid-feedback mt-0 mb-1">{{$message}}</div>
                    @enderror
                    <div class="form-text mt-0 mb-1">Ein deaktivierter Benutzer wird aus dem Portal abgemeldet und kann sich nicht wieder anmelden.</div>
                </div>
            </div>
        </div>
        @error('confirmation')
        <div class="row mb-3">
            <div class="col">
                <p class="form-label text-danger">
                    Wenn du deine eigene Rolle änderst oder deinen Benutzeraccount deaktivierst, wirst du diese Seite nicht mehr aufrufen können.
                </p>
                <div class="form-check">
                    <input class="form-check-input is-invalid" type="checkbox" id="confirmation" wire:model="confirmation">
                    <label class="form-check-label" for="confirmation">Ich bin mir dessen bewusst und möchte fortfahren.</label>
                </div>
            </div>
        </div>
        @enderror

        <div class="row mb-3">
            <div class="col">
                @if($errors->hasAny('forename','surname','email','username','role','enabled','confirmation'))
                    <button type="submit" class="btn btn-primary">Änderungen übernehmen</button>
                @else
                    <button type="submit"
                            id="btn_{{rand()}}" {{-- to prevent livewire from reusing button with error condition --}}
                            class="btn btn-outline-primary"
                            wire:dirty.class="btn-primary"
                            wire:dirty.class.remove="btn-outline-primary"
                            wire:target="forename,surname,email,username,role,enabled,confirmation">
                        Änderungen übernehmen
                    </button>
                @endif
            </div>
        </div>
    </form>

    <h3 class="mb-3 mt-4">Passwort zurücksetzen</h3>
    @if($enabled)
        <p>Sollte {{$forename}} das Passwort zu dem Benutzeraccount nicht mehr kennen, kannst du hier eine Passwort-Reset-E-Mail versenden.</p>
        @session('mailSuccess')
        <div class="alert alert-success">Es wurde eine E-Mail-Adresse zum Zurücksetzen des Passworts an „{{$email}}“ gesendet.</div>
        @endsession
        @session('mailError')
        <div class="alert alert-danger">{{session('mailError')}}</div>
        @endsession
        <form wire:submit="sendPasswordMail">
            <button type="submit" class="btn btn-primary">E-Mail an „{{$email}}“ senden</button>
        </form>
    @else
        <p>Es kann keine Passwort-Reset-E-Mail versendet werden, solange dieser Benutzeraccount deaktiviert ist.</p>
    @endif


    <h3 class="mb-3 mt-4">Benutzer löschen</h3>

    @if($user->id == auth()->user()->id)
        <div class="alert alert-danger">
            <strong>Warnung!</strong>
            Dadurch wird <strong>dein Account</strong> vollständig aus der Datenbank gelöscht. Du kannst dich danach nicht mehr am Portal anmelden.
        </div>

        <form wire:submit="deleteUser" wire:confirm="Soll dein Benutzeraccount ({{$username}}) wirklich vollständig gelöscht werden?\nDanach kannst du dich nicht mehr am Portal anmelden.">
            <button class="btn btn-danger" type="submit">
                Benutzer „{{$username}}“ löschen
            </button>
        </form>
    @else
        <div class="alert alert-danger">
            <strong>Warnung!</strong>
            Dadurch wird der Benutzer vollständig aus der Datenbank gelöscht.
        </div>

        <form wire:submit="deleteUser" wire:confirm="Soll der Benutzer „{{$username}}“ wirklich vollständig gelöscht werden?">
            <button class="btn btn-danger" type="submit">
                Benutzer „{{$username}}“ löschen
            </button>
        </form>
    @endif

</div>
