<x-slot:breadcrumbs>
    <li class="breadcrumb-item"><i class="fa-solid fa-box"></i>&nbsp;Artikel</li>
    <li class="breadcrumb-item"><a href="{{route('dashboard.items.list')}}" wire:navigate>Übersicht</a></li>
    <li class="breadcrumb-item">Artikel „{{$item->name}}“ bearbeiten</li>
</x-slot:breadcrumbs>

<div>
    <h1 class="mb-4">Artikel „{{$item->name}}“ bearbeiten</h1>

    <x-status-message />

    <form wire:submit="updateItem">
        <div class="row mb-3">
            <label for="name" class="col-sm-3 col-xl-2 col-form-label">Artikelname</label>
            <div class="col">
                <input class="form-control @error('name')is-invalid @enderror" wire:model="name" id="name" required>
                @error('name')
                <div class="invalid-feedback">{{$message}}</div>
                @enderror
                <div class="form-text">
                    @if($item->itemGroup)
                        Da der Artikel gruppiert ist, wird dem Artikelnamen der Gruppenname vorangestellt.
                    @else
                        Falls der Artikel gruppiert wird, wird dem Artikelnamen der Gruppenname vorangestellt.
                    @endif
                </div>
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

        <div class="row mb-1 mb-sm-3">
            <label for="stock" class="col-auto col-sm-3 col-xl-2 col-form-label">Regulärer Bestand</label>
            <div class="col col-sm-9 col-xl-8">
                <div class="row">
                    <div class="col-12 col-sm-4 col-xl-3">
                        <input type="number" min="1" max="9999" required class="form-control @error('stock')is-invalid @enderror" id="stock" wire:model="stock" data-initial-stock="{{$stock ?: 1}}" @if(!$keepStock)disabled @endif>
                    </div>
                </div>
                @error('stock')
                <div class="row">
                    <div class="col">
                        <input class="is-invalid" type="hidden">
                        <div class="invalid-feedback">{{$message}}</div>
                    </div>
                </div>
                @enderror
                <div class="row mt-1 d-none d-sm-flex">
                    <div class="col">
                        <div class="form-check form-switch">
                            <input class="form-check-input @error('keepStock')is-invalid @enderror" type="checkbox" role="switch" id="keepStock" wire:model="keepStock">
                            <label class="form-check-label" for="keepStock">Bestand verwalten</label>
                            @error('keepStock')
                            <div class="invalid-feedback mt-0 mb-1">{{$message}}</div>
                            @enderror
                        </div>
                        <script>
                            document.getElementById('keepStock').addEventListener('change', function () {
                                const stockInput = document.getElementById('stock')
                                stockInput.toggleAttribute('disabled', !this.checked)
                                stockInput.value = this.checked ? stockInput.dataset.initialStock : ''
                                stockInput.dispatchEvent(new InputEvent('input'))
                            })
                        </script>
                    </div>
                </div>
            </div>
        </div>
        <div class="row d-sm-none mb-3">
            <div class="col">
                <div class="form-check form-switch">
                    <input class="form-check-input @error('keepStock')is-invalid @enderror" type="checkbox" role="switch" wire:model="keepStock">
                    <label class="form-check-label" for="keepStock">Bestand verwalten</label>
                    @error('keepStock')
                    <div class="invalid-feedback mt-0 mb-1">{{$message}}</div>
                    @enderror
                </div>
            </div>
        </div>

        <div class="row mb-3">
            <label for="price" class="col-3 col-xl-2 col-form-label">Preis</label>
            <div class="col col-sm-3 col-xl-2">
                <div class="input-group">
                    <input type="number" required min="0" max="9999" step="0.01" class="form-control @error('price')is-invalid @enderror" id="price" wire:model="price">
                    <span class="input-group-text">€</span>
                    @error('price')
                    <div class="invalid-feedback">{{$message}}</div>
                    @enderror
                </div>
            </div>
        </div>

        <div class="row mb-3">
            <label for="deposit" class="col-3 col-xl-2 col-form-label">Kaution</label>
            <div class="col col-sm-3 col-xl-2">
                <div class="input-group">
                    <input type="number" required min="0" max="9999" class="form-control @error('deposit')is-invalid @enderror" id="deposit" wire:model="deposit">
                    <span class="input-group-text">€</span>
                    @error('deposit')
                    <div class="invalid-feedback">{{$message}}</div>
                    @enderror
                </div>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col">
                <div class="form-check form-switch">
                    <input class="form-check-input @error('available')is-invalid @enderror" type="checkbox" role="switch" id="available" wire:model="available">
                    <label class="form-check-label" for="available">Artikel verfügbar</label>
                    @error('available')
                    <div class="invalid-feedback mt-0 mb-1">{{$message}}</div>
                    @enderror
                </div>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col">
                <div class="form-check form-switch">
                    <input class="form-check-input @error('visible')is-invalid @enderror" type="checkbox" role="switch" id="visible" wire:model="visible">
                    <label class="form-check-label" for="visible">Artikel sichtbar</label>
                    @error('visible')
                    <div class="invalid-feedback mt-0 mb-1">{{$message}}</div>
                    @enderror
                </div>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col">
                @if($errors->hasAny('name','description','keepStock','stock','price','deposit','available','visible'))
                    <button type="submit" class="btn btn-primary">Änderungen übernehmen</button>
                @else
                    <button type="submit"
                            id="btn_{{rand()}}" {{-- to prevent livewire from reusing button with error condition --}}
                            class="btn btn-outline-primary"
                            wire:dirty.class="btn-primary"
                            wire:dirty.class.remove="btn-outline-primary"
                            wire:target="name,description,keepStock,stock,price,deposit,available,visible">
                        Änderungen übernehmen
                    </button>
                @endif
            </div>
        </div>
    </form>
    <div class="row">
        <div class="col" x-data="{'requested': false}">
            @if($this->item->itemGroup?->items()->count() == 1)
                <div x-cloak x-show="requested" class="alert alert-warning small p-2 mb-2">Dies ist der einzige Artikel in der Gruppe „{{$this->item->itemGroup->name}}“. Beim Löschen des Artikels wird auch die Gruppe entfernt.</div>
                <button x-cloak x-show="requested" wire:click="delete" wire:confirm="Soll dieser Artikel wirklich gelöscht werden?" class="btn btn-danger"><i class="fa-solid fa-trash-can"></i>&nbsp;Artikel löschen</button>

                <button x-show="!requested" @@click="requested=true" class="btn btn-danger"><i class="fa-solid fa-trash-can"></i>&nbsp;Artikel löschen</button>
            @else
                <button wire:click="delete" wire:confirm="Soll dieser Artikel wirklich gelöscht werden?" class="btn btn-danger"><i class="fa-solid fa-trash-can"></i>&nbsp;Artikel löschen</button>
            @endif
        </div>
    </div>

    <h3 class="mt-4">Artikel gruppieren</h3>

    <p>
        @if($item->itemGroup)
            Da
        @else
            Wenn
        @endif
            dieser Artikel mit anderen Artikeln gruppiert wird, wird auf der Übersichtsseite im Shop nur der Gruppenname dargestellt. Die individuellen Artikel lassen sich dann auf der Artikelseite in einem Auswahlfeld selektieren.
            Das kann praktisch sein, wenn es mehrere Varianten gibt, bspw. Kabel mit unterschiedlichen Längen.
    </p>

    <form wire:submit="updateGroup">
        <div class="row mb-3">
            <label for="itemGroup" class="col-sm-3 col-xl-2 col-form-label">Artikelgruppe</label>
            <div class="col">
                <select class="form-control @error('itemGroup')is-invalid @enderror" id="itemGroup" wire:model.live="itemGroup" wire:refresh-when-cached>{{-- refresh when cached so that "Gruppe bearbeiten" button will still point to correct link after changing group and navigating back from edit page --}}
                    <option value="none" class="text-italic">Nicht gruppieren</option>
                    <option value="new" class="text-italic">Neue Gruppe erstellen</option>
                    <optgroup label="Vorhandene Gruppen">
                        @foreach($groups as $group)
                            <option value="{{$group->id}}">{{$group->name}}</option>
                        @endforeach
                    </optgroup>
                </select>
                @error('itemGroup')
                <div class="invalid-feedback">{{$message}}</div>
                @enderror
            </div>
            @if($item->itemGroup && $this->itemGroup != 'new' && $this->itemGroup != 'none')
                <div class="col-auto">
                    <a href="{{route('dashboard.groups.edit', $this->itemGroup)}}" class="btn btn-outline-primary text-nowrap" wire:navigate>
                        <i class="fa-solid fa-pen-to-square"></i>
                        <span class="d-none d-sm-inline">Gruppe bearbeiten</span>
                    </a>
                </div>
            @endif
        </div>
        <div class="row">
            <div class="col">
                @if($errors->has('itemGroup') || $item->itemGroup?->id != ($itemGroup == 'none' ? null : $itemGroup))
                    <button type="submit" class="btn btn-primary">Änderung übernehmen</button>
                @else
                    <button type="submit"
                            id="btn_{{rand()}}" {{-- to prevent livewire from reusing button with error condition --}}
                            class="btn btn-outline-primary"
                            wire:dirty.class="btn-primary"
                            wire:dirty.class.remove="btn-outline-primary"
                            wire:target="itemGroup">Änderung übernehmen
                    </button>
                @endif
            </div>
        </div>
    </form>

    <h3 class="mt-4">Artikelbilder</h3>

</div>
