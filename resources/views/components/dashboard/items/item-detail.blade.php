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
                            Formatierung mit Markdown wird unterstützt, bspw. <code>*<i>kursiv</i>*</code>, <code>**<b>fett</b>**</code> oder <code>[Link-Text](<span class="link">https://url</span>)</code>.
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="itemGroup" class="{{$labelClass}}">Artikel gruppieren</label>
                    <div class="col">
                        <select class="form-control @error('itemGroup')is-invalid @enderror" id="itemGroup" wire:model="itemGroup">
                            <option value="" class="text-italic">Nicht gruppieren</option>
                            @foreach($this->groups as $group)
                                <option value="{{$group->id}}">{{$group->name}}</option>
                            @endforeach
                        </select>
                        @error('itemGroup')
                        <div class="invalid-feedback">{{$message}}</div>
                        @enderror
                        <div class="form-text">Gruppierte Artikel werden auf der Übersichtsseite zusammengefasst.</div>
                    </div>
                </div>

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
                        @if($errors->hasAny('name','description','keepStock','stock','price','deposit','available','visible','itemGroup'))
                            <button type="submit" class="btn btn-primary">Änderungen übernehmen</button>
                        @else
                            <button type="submit"
                                    id="btn_{{rand()}}" {{-- to prevent livewire from reusing button with error condition --}}
                                    class="btn btn-outline-primary"
                                    wire:dirty.class="btn-primary"
                                    wire:dirty.class.remove="btn-outline-primary"
                                    wire:target="name,description,keepStock,stock,price,deposit,available,visible,itemGroup">
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

                            <button x-show="!requested" @click="requested=true" class="btn btn-danger"><i class="fa-solid fa-trash-can"></i>&nbsp;Artikel löschen</button>
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
                    <x-swiper id="dashboard-images" class="item-image-container">
                        @foreach($item->images as $image)
                            <div class="swiper-slide" data-hash="image-{{$image->id}}">
                                <div class="swiper-zoom-container">
                                    <img src="{{\Illuminate\Support\Facades\Storage::url($image->path)}}" alt="Produktbild" class="object-fit-{{config('shop.image_sizing')}}">
                                </div>
                                <div class="image-delete d-flex justify-content-center align-items-center flex-wrap position-absolute bottom-0 w-100 pb-1">
                                    @if($item->itemGroup)
                                        @if($item->itemGroup->image?->id == $image->id)
                                            <div class="badge text-bg-secondary mx-2 mb-1">Dies ist das Gruppenbild</div>
                                        @else
                                            <button class="btn btn-light mx-2 mb-1 btn-sm text-nowrap" wire:click="setGroupImage({{$image->id}})">
                                                <i class="fa-solid fa-object-group"></i> Als Gruppenbild festlegen
                                            </button>
                                        @endif
                                    @endif
                                    <button class="btn btn-danger mx-2 mb-1 btn-sm text-nowrap" wire:click="deleteImage({{$image->id}})">
                                        <i class="fa-solid fa-trash-can"></i> Löschen
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </x-swiper>
                @endif

                <h5 class="mt-3 mb-2"><label for="images">Neue Bilder hinzufügen</label></h5>
                <form wire:loading.remove wire:target="images">
                    <input type="file" wire:model.live="images" class="form-control" id="images" multiple wire:key="{{rand()}}">
                    <div class="form-text mb-2">Maximale Dateigröße: {{$this->maxSize()}} MB. Unterstützte Formate: jpg, png, webp.</div>
                </form>
                <p wire:loading wire:target="images">
                    <i class="fas fa-spinner fa-pulse"></i>&nbsp;Bitte warten...
                </p>
                @error('images.*'){{-- internal upload error, e.g. when POST_MAX_SIZE was reached --}}
                <div class="alert alert-danger small p-2">Mindestens eine Datei konnte nicht erfolgreich hochgeladen werden.</div>
                @enderror
                <x-status-message scope="images" class="small p-2" wire:loading.remove wire:target="images" />
            </div>
        @endisset
    </div>

    @isset($item->itemGroup)
        <h3>Artikelgruppe</h3>

        <div>
            <p>Da dieser Artikel mit anderen Artikeln gruppiert ist, wird auf der Übersichtsseite im Shop nur der Gruppenname dargestellt. Die individuellen Artikel lassen sich dann auf der Artikelseite in einem Auswahlfeld selektieren.</p>
            <p class="mb-2">In der Gruppe „{{$item->itemGroup->name}}“ befinden sich folgende Artikel:</p>
            <div class="row">
                <div class="col-auto">
                    <table class="table table-hover align-middle table-borderless">
                        @foreach($item->itemGroup->items->sortBy('name', SORT_NATURAL) as $item)
                            <tr>
                                <td>
                                    <ul class="my-1 ps-4">
                                        <li>
                                            <span class="d-sm-none">{{$item->raw_name}}</span>
                                            <span class="d-none d-sm-inline">{{$item->name}}</span>
                                        </li>
                                    </ul>
                                </td>
                                <td class="w-0 text-center">
                                    @if($item->id == $this->item->id)
                                        <span class="badge text-bg-primary fw-normal d-sm-none">Dieser Artikel!</span>
                                        <span class="badge text-bg-primary d-none d-sm-inline-block">Dieser Artikel!</span>
                                    @else
                                        <a class="btn btn-outline-primary btn-sm text-nowrap px-3 px-sm-2" href="{{route('dashboard.items.edit', $item->id)}}" wire:navigate>
                                            <i class="fa-solid fa-pen-to-square"></i>
                                            <span class="d-none d-sm-inline">Bearbeiten</span>
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>
            <div class="row">
                <div class="col-auto">
                    <a href="{{route('dashboard.groups.edit', $item->itemGroup->id)}}" wire:navigate class="btn btn-primary">Artikelgruppe bearbeiten</a>
                </div>
            </div>
        </div>
    @endisset
</div>
