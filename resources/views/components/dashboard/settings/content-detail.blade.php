<x-slot:breadcrumbs>
    <li class="breadcrumb-item"><i class="fa-solid fa-gear"></i>&nbsp;Einstellungen</li>
    <li class="breadcrumb-item"><i class="fa-regular fa-newspaper"></i>&nbsp;<a href="{{route('dashboard.settings.contents.list')}}" wire:navigate>Inhalte</a></li>
    <li class="breadcrumb-item">Inhalt „{{$content->name}}“ bearbeiten</li>
</x-slot:breadcrumbs>

<div>
    <h1 class="mb-4">Einstellungen</h1>

    <x-status-message />

    <h3>Inhalt „{{$content->name}}“ bearbeiten</h3>

    <form wire:submit="save">
        <div class="row mb-3">
            <label for="contentValue" class="col-form-label">{{$content->description}}</label>
            <div class="col">
                <div class="autogrow-textarea @error('contentValue')is-invalid @enderror" data-replicated-value="{{$contentValue}}">
                    <textarea onInput="this.parentNode.dataset.replicatedValue=this.value" wire:model.live.debounce.150ms="contentValue" rows="5" id="contentValue" class="form-control @error('contentValue')is-invalid @enderror" required></textarea>
                </div>
                @error('contentValue')
                <div class="invalid-feedback">{{$message}}</div>
                @enderror
                <div class="form-text">Formatierung mit Markdown wird unterstützt, bspw. <code>*<i>kursiv</i>*</code>, <code>**<b>fett</b>**</code> oder <code>[Link-Text](<span class="link">https://url</span>)</code>.</div>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col">
                <button type="submit" class="btn btn-primary">Speichern</button>
            </div>
        </div>
    </form>

    <h3>Vorschau</h3>

    <div class="form-control bg-body preview-container">
        {!! $this->getPreview() !!}
    </div>
</div>
