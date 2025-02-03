<x-slot:breadcrumbs>
    <li class="breadcrumb-item"><i class="fa-solid fa-object-group"></i>&nbsp;Artikelgruppen</li>
    <li class="breadcrumb-item"><a href="{{route('dashboard.groups.list')}}" wire:navigate>Übersicht</a></li>
    <li class="breadcrumb-item">Artikelgruppe anlegen</li>
</x-slot:breadcrumbs>

<div>
    <h1 class="mb-4">Gruppe anlegen</h1>

    <form wire:submit="createGroup">
        <div class="row mb-3">
            <label for="name" class="col-sm-3 col-xl-2 col-form-label">Name</label>
            <div class="col">
                <input class="form-control @error('name')is-invalid @enderror" wire:model="name" id="name" required>
                @error('name')
                <div class="invalid-feedback">{{$message}}</div>
                @enderror
                <div class="form-text">Der Gruppenname wird den Artikelnamen vorangestellt.</div>
            </div>
        </div>

        <div class="row mb-3">
            <label for="description" class="col-sm-3 col-xl-2 col-form-label">Beschreibung</label>
            <div class="col">
                <div class="autogrow-textarea @error('description')is-invalid @enderror" data-replicated-value="{{$description}}">
                    <textarea onInput="this.parentNode.dataset.replicatedValue=this.value" wire:model="description" rows="3" id="description" class="form-control @error('description')is-invalid @enderror"></textarea>
                </div>
                @error('description')
                <div class="invalid-feedback">{{$message}}</div>
                @enderror
                <div class="form-text">
                    Formatierung mit Markdown wird unterstützt, bspw. <code>*<i>kursiv</i>*</code>, <code>**<b>fett</b>**</code> oder <code>(Link-Text)[<span class="link">https://url</span>]</code>.
                </div>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col">
                <button type="submit" class="btn btn-primary">Gruppe anlegen</button>
            </div>
        </div>
    </form>
</div>
