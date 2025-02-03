<x-slot:breadcrumbs>
    <li class="breadcrumb-item"><i class="fa-solid fa-box"></i>&nbsp;Artikel</li>
    <li class="breadcrumb-item"><a href="{{route('dashboard.items.list')}}" wire:navigate>Übersicht</a></li>
    <li class="breadcrumb-item">
        @isset($item)
            Artikel „{{$item->name}}“ bearbeiten
        @else
            Artikel anlegen
        @endisset
    </li>
</x-slot:breadcrumbs>

<div>
    <h1 class="mb-4">
        @isset($item)
            Artikel „{{$item->name}}“ bearbeiten
        @else
            Artikel anlegen
        @endisset
    </h1>

    <x-status-message />

    @php($labelClass = 'col-sm-3 col-xl-2 col-form-label' . (isset($item) ? ' col-xxl-3' : ''))

    <div class="row">
        <div class="{{isset($item) ? 'col-xxl-6' : 'col'}} mb-4">
            <form wire:submit="saveItem">
                <div class="row mb-3">
                    <label for="name" class="{{$labelClass}}">Artikelname</label>
                    <div class="col">
                        <input class="form-control @error('name')is-invalid @enderror" wire:model="name" id="name" required>
                        @error('name')
                        <div class="invalid-feedback">{{$message}}</div>
                        @enderror
                        <div class="form-text">
                            @isset($item->itemGroup)
                                Da der Artikel gruppiert ist, wird dem Artikelnamen der Gruppenname vorangestellt.
                            @else
                                Falls der Artikel gruppiert wird, wird dem Artikelnamen der Gruppenname vorangestellt.
                            @endif
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="description" class="{{$labelClass}}">Beschreibung</label>
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

                @empty($item)
                    <div class="row mb-3">
                        <label for="initialItemGroup" class="{{$labelClass}}">Artikelgruppe</label>
                        <div class="col">
                            <select class="form-control @error('initialItemGroup')is-invalid @enderror" id="initialItemGroup" wire:model="initialItemGroup">
                                <option value="" class="text-italic">Nicht gruppieren</option>
                                @foreach($this->groups() as $group)
                                    <option value="{{$group->id}}">{{$group->name}}</option>
                                @endforeach
                            </select>
                            @error('initialItemGroup')
                            <div class="invalid-feedback">{{$message}}</div>
                            @enderror
                        </div>
                    </div>
                @endempty

                <div class="row mb-1 mb-sm-3">
                    <label for="stock" class="col-auto {{$labelClass}}">Regulärer Bestand</label>
                    <div class="col">
                        <div class="row">
                            <div class="col-sm-4">
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
                    <label for="price" class="{{$labelClass}}">Preis</label>
                    <div class="col">
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="input-group">
                                    <input type="number" required min="0" max="9999" step="0.01" class="form-control @error('price')is-invalid @enderror" id="price" wire:model="price">
                                    <span class="input-group-text">€</span>
                                    @error('price')
                                    <div class="invalid-feedback">{{$message}}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="deposit" class="{{$labelClass}}">Kaution</label>
                    <div class="col">
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="input-group">
                                    <input type="number" required min="0" max="9999" class="form-control @error('deposit')is-invalid @enderror" id="deposit" wire:model="deposit">
                                    <span class="input-group-text">€</span>
                                    @error('deposit')
                                    <div class="invalid-feedback">{{$message}}</div>
                                    @enderror
                                </div>
                            </div>
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
                        @if($errors->hasAny('name','description','keepStock','stock','price','deposit','available','visible','initialItemGroup'))
                            <button type="submit" class="btn btn-primary">Änderungen übernehmen</button>
                        @else
                            <button type="submit"
                                    id="btn_{{rand()}}" {{-- to prevent livewire from reusing button with error condition --}}
                                    class="btn btn-outline-primary"
                                    wire:dirty.class="btn-primary"
                                    wire:dirty.class.remove="btn-outline-primary"
                                    wire:target="name,description,keepStock,stock,price,deposit,available,visible,initialItemGroup">
                                @isset($item)
                                    Änderungen übernehmen
                                @else
                                    Artikel anlegen
                                @endisset
                            </button>
                            @empty($item)
                                <div class="form-text">Artikelbilder können nach dem Speichern hinzugefügt werden.</div>
                            @endempty
                        @endif
                    </div>
                </div>
            </form>
            @isset($item)
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
            @endisset
        </div>

        @isset($item)
            <div class="col-xxl-6 mb-4">
                <h3>Artikelbilder</h3>

                @if($item->images->isEmpty())
                    <p>Zu diesem Artikel wurden noch keine Bilder hinterlegt.</p>
                @else
                    <x-swiper id="dashboard-images" class="dashboard-swiper-container">
                        @foreach($item->images as $image)
                            <div class="swiper-slide">
                                <div class="swiper-zoom-container">
                                    <img src="{{\Illuminate\Support\Facades\Storage::url($image->path)}}" alt="Produktbild">
                                </div>
                                <div class="image-delete d-flex position-absolute bottom-0 w-100">
                                    <button class="btn btn-danger mx-auto my-2" wire:click="deleteImage({{$image->id}})">
                                        <i class="fa-solid fa-trash-can"></i>&nbsp;Löschen
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </x-swiper>
                @endif

                <h5 class="mt-3 mb-2"><label for="images">Neue Bilder hinzufügen</label></h5>
                <form class="spinner-when-loading">
                    <input type="file" wire:model.live="images" class="form-control" id="images" multiple wire:key="{{rand()}}">
                    <div wire:loading.flex wire:target="images">
                        <div>
                            <i class="fas fa-spinner fa-pulse"></i>
                            Bitte warten...
                        </div>
                    </div>
                </form>
                <div class="form-text mb-2">Maximale Dateigröße: {{$this->maxSize()}} MB. Unterstützte Formate: jpg, png, webp.</div>
                @error('images.*'){{-- internal upload error, e.g. when POST_MAX_SIZE was reached --}}
                <div class="alert alert-danger small p-2">Mindestens eine Datei konnte nicht erfolgreich hochgeladen werden.</div>
                @enderror
                <x-status-message scope="images" class="small p-2" />
            </div>
        @endisset
    </div>

    @isset($item)
        <h3>Artikel gruppieren</h3>

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
                            @foreach($this->groups() as $group)
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
    @endisset
</div>
