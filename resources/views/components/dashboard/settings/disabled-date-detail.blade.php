<x-slot:breadcrumbs>
    <li class="breadcrumb-item"><i class="fa-solid fa-gear"></i>&nbsp;Einstellungen</li>
    <li class="breadcrumb-item"><i class="fa-regular fa-calendar-xmark"></i>&nbsp;<a href="{{route('dashboard.settings.disabledDates.list')}}" wire:navigate>Deaktivierte Zeiträume</a></li>
    <li class="breadcrumb-item">Zeitraum {{$disabledDate ? 'bearbeiten' : 'anlegen'}}</li>
</x-slot:breadcrumbs>

<div>
    <h1 class="mb-4">Deaktivierten Zeitraum {{$disabledDate ? 'bearbeiten' : 'anlegen'}}</h1>

    <x-status-message />

    <form wire:submit="save">
        <div class="row mb-3">
            <label for="start" class="col-sm-3 col-md-2 col-form-label">Beginn</label>
            <div class="col-sm-auto">
                <input type="date" class="form-control @error('start')is-invalid @enderror" wire:model="start" id="start" required>
                @error('start')
                <div class="invalid-feedback">{{$message}}</div>
                @enderror
            </div>
        </div>
        <div class="row mb-3">
            <label for="end" class="col-sm-3 col-md-2 col-form-label">Ende</label>
            <div class="col-sm-auto">
                <input type="date" class="form-control @error('end')is-invalid @enderror" wire:model="end" id="end" required>
                @error('end')
                <div class="invalid-feedback">{{$message}}</div>
                @enderror
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-sm-auto offset-sm-3 offset-md-2">
                <div class="form-check form-switch">
                    <input class="form-check-input @error('active')is-invalid @enderror" type="checkbox" role="switch" id="active" wire:model="active">
                    <label class="form-check-label" for="active">Aktiv</label>
                    @error('active')
                    <div class="invalid-feedback mt-0 mb-1">{{$message}}</div>
                    @enderror
                </div>
            </div>
        </div>
        <div class="row mb-3">
            <label for="comment" class="col-sm-3 col-md-2 col-form-label">Kommentar</label>
            <div class="col">
                <input class="form-control @error('comment')is-invalid @enderror" wire:model="comment" id="comment">
                @error('comment')
                <div class="invalid-feedback">{{$message}}</div>
                @enderror
                <div class="form-text">Der Kommentar ist nur über die Verwaltungsoberfläche einsehbar.</div>
            </div>
        </div>
        <div class="row mb-3">
            <label for="site_notice" class="col-sm-3 col-md-2 col-form-label">Buchungs&shy;hinweis</label>
            <div class="col">
                <div class="autogrow-textarea @error('site_notice')is-invalid @enderror" data-replicated-value="{{$site_notice}}">
                    <textarea onInput="this.parentNode.dataset.replicatedValue=this.value" wire:model="site_notice" rows="3" id="site_notice" class="form-control @error('site_notice')is-invalid @enderror"></textarea>
                </div>
                @error('site_notice')
                <div class="invalid-feedback">{{$message}}</div>
                @enderror
                <div class="form-text">Falls während eines gesperrten Zeitraums ein Hinweis oben im Shop angezeigt werden soll, kann dieser hier hinterlegt werden.</div>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col">
                @if($errors->any())
                    <button type="submit" class="btn btn-primary">Speichern</button>
                @else
                    <button type="submit"
                            id="btn_{{rand()}}" {{-- to prevent livewire from reusing button with error condition --}}
                            class="btn btn-outline-primary"
                            wire:dirty.class="btn-primary"
                            wire:dirty.class.remove="btn-outline-primary">
                        Speichern
                    </button>
                @endif
            </div>
        </div>
    </form>
    @if($disabledDate)
        <button class="btn btn-outline-danger"
                wire:click="delete({{$disabledDate->id}})"
                wire:confirm="Soll dieser deaktivierte Zeitraum wirklich gelöscht werden?">
            <i class="fa-solid fa-trash-can"></i>&nbsp;Löschen
        </button>
    @endif
</div>
