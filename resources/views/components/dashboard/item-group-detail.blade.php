<x-slot:breadcrumbs>
    <li class="breadcrumb-item"><i class="fa-solid fa-object-group"></i>&nbsp;Artikelgruppen</li>
    <li class="breadcrumb-item"><a href="{{route('dashboard.groups.list')}}" wire:navigate>Übersicht</a></li>
    <li class="breadcrumb-item">Artikelgruppe „{{$group->name}}“ bearbeiten</li>
</x-slot:breadcrumbs>

<div>
    <h1 class="mb-4">Gruppe „{{$group->name}}“ bearbeiten</h1>

    <x-status-message />

    <form wire:submit="updateGroup">
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
                @if($errors->hasAny('name','description'))
                    <button type="submit" class="btn btn-primary">Änderungen übernehmen</button>
                @else
                    <button type="submit"
                            id="btn_{{rand()}}" {{-- to prevent livewire from reusing button with error condition --}}
                            class="btn btn-outline-primary"
                            wire:dirty.class="btn-primary"
                            wire:dirty.class.remove="btn-outline-primary"
                            wire:target="name,description">
                        Änderungen übernehmen
                    </button>
                @endif
            </div>
        </div>
    </form>

    <div class="row">
        <div class="col">
            <button wire:click="deleteGroup" wire:confirm="Soll diese Artikelgruppe wirklich gelöscht werden?" class="btn btn-danger"><i class="fa-solid fa-trash-can"></i>&nbsp;Gruppe löschen</button>
            @if($this->hasItems())
                <div class="form-text">In der Gruppe vorhandene Artikel werden nicht gelöscht. Es wird lediglich ihre Gruppierung aufgehoben. Der Artikelname ändert sich entsprechend.</div>
            @endif
        </div>
    </div>

    <h3 class="mb-3 mt-4">Artikel verwalten</h3>

    <form wire:submit="addItem">
        <div class="row mb-3">
            <label for="newItem" class="col-sm-3 col-xl-2 col-form-label pe-xl-2">Neuen Artikel hinzufügen</label>
            <div class="col-sm">
                <select required class="form-control @error('newItem')is-invalid @enderror" id="newItem" wire:model="newItem" wire:replace>
                    <option hidden value="">Bitte wählen...</option>
                    @php($lastGroupId = null)
                    {{--@formatter:off--}}
                    @foreach($this->addableItems() as $item)
                        @if($lastGroupId != $item->itemGroup?->id)
                            {!! $lastGroupId !== null ? '</optgroup>' : '' !!}
                            <optgroup label="{{$item->itemGroup->name}}">
                            @php($lastGroupId = $item->itemGroup->id)
                        @endif

                        <option value="{{$item->id}}">{{$item->name}}</option>
                    @endforeach

                    @if($lastGroupId !== null)
                        </optgroup>
                    @endif
                    {{--@formatter:on--}}
                </select>
                @error('newItem')
                <div class="invalid-feedback">{{$message}}</div>
                @enderror
                <div class="form-text">Ein Artikel kann nur in einer Gruppe sein. Falls der Artikel bereits in einer anderen Gruppe ist, wird er aus dieser entfernt.</div>
            </div>
            <div class="mt-2 mt-sm-0 col-auto">
                <button type="submit" class="btn btn-outline-primary"
                        wire:dirty.class="btn-primary"
                        wire:dirty.class.remove="btn-outline-primary"
                        wire:target="newItem">
                    Hinzufügen
                </button>
            </div>
        </div>
    </form>
    @if($this->hasItems())
        <div class="row mb-3">
            <label class="col-sm-3 col-xl-2 col-form-label pe-xl-2 text-nowrap">Enthaltene Artikel</label>
            <div class="col-sm-auto col">
                <table class="table table-hover align-middle table-borderless">
                    @foreach($group->items->sortBy('name', SORT_NATURAL) as $item)
                        <tr>
                            <td>
                                <ul class="m-0 ps-4">
                                    <li>
                                        <span class="d-sm-none">{{$item->rawName()}}</span>
                                        <span class="d-none d-sm-inline">{{$item->name}}</span>
                                    </li>
                                </ul>
                            </td>
                            <td class="w-0">
                                <a class="btn btn-outline-primary btn-sm text-nowrap px-3 px-sm-2" href="{{route('dashboard.items.edit', $item->id)}}" wire:navigate>
                                    <i class="fa-solid fa-pen-to-square"></i>
                                    <span class="d-none d-sm-inline">Bearbeiten</span>
                                </a>
                            </td>
                            <td>
                                <button class="btn btn-outline-danger btn-sm text-nowrap px-3 px-sm-2" x-on:click="$wire.removeItem({{$item->id}}) && $wire.$refresh()">
                                    <i class="fa-solid fa-xmark"></i>
                                    <span class="d-none d-sm-inline d-md-none">Entfernen</span>
                                    <span class="d-none d-md-inline">Aus Gruppe entfernen</span>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>
    @endif
</div>
