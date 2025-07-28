@use(App\Models\DisabledDate)
@use(Carbon\CarbonImmutable)

@script
<script>
    const modal = document.getElementById('editOrderItem')
        , form = document.getElementById('editOrderItemForm')

    let lastItemId = null

    // We're using a single modal for all items. Thus, we have to load corresponding item data when modal opens.
    modal.addEventListener('show.bs.modal', async e => {
        const itemId = +e.relatedTarget.dataset.bsOrderItem

        Alpine.$data(form).initialRequest = lastItemId !== itemId
        const itemFound = await $wire.loadOrderItem(itemId)
        Alpine.$data(form).itemFound = itemFound
        Alpine.$data(form).initialRequest = false

        lastItemId = itemFound ? itemId : null
    })

    // Reset itemFound flag when closing modal to not have message flash when editing another item lateron.
    modal.addEventListener('hidden.bs.modal', () => Alpine.$data(form).itemFound = true)

    // called from livewire component on successfull item update
    $js('closeModal', () => bootstrap.Modal.getInstance(modal).hide())
</script>
@endscript

<form wire:submit="updateOrderItem" x-data="{itemFound: true, initialRequest: true}" id="editOrderItemForm">
    <div class="modal-body position-relative">
        <div class="position-absolute start-0 top-0 bg-body h-100 w-100 z-3 d-flex" x-cloak x-show.important="initialRequest">
            <span class="d-inline-block m-auto">
                <i class="fas fa-spinner fa-pulse"></i>&nbsp;Bitte warten...
            </span>
        </div>

        <div class="text-center" x-cloak x-show="!itemFound">
            <p>Dieser Artikel wurde in der aktuellen Bestellung nicht gefunden.</p>
            <button type="button" class="btn btn-primary" wire:click="$dispatch('refresh-data-table')" data-bs-dismiss="modal">
                <i class="fa-solid fa-arrows-rotate"></i>
                Artikelliste neu laden
            </button>
        </div>

        <div x-show="itemFound">
            <div class="row mb-3">
                <label for="item" class="col-sm-4 col-form-label">Artikel</label>
                <div class="col">
                    <select required class="form-control @error('item')is-invalid @enderror" id="item" wire:model.live="item">
                        @php($lastGroupId = null)
                        {{--@formatter:off--}}
                        @foreach($this->items as $item)
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
                    @error('item')
                    <div class="invalid-feedback">{{$message}}</div>
                    @enderror
                </div>
            </div>
            <div class="row mb-3">
                <label for="start_orderitem" class="col-sm-4 col-form-label">Zeitraum</label>
                <div class="col">
                    <x-linked-date-range id-suffix="_orderitem" :live="true" :required="true" />
                    <div class="form-text" wire:loading wire:target="start,end">
                        <i class="fas fa-spinner fa-pulse"></i>&nbsp;Bitte warten...
                    </div>
                    @if($start && $end)
                        <div wire:loading.remove wire:target="start,end">
                            <div class="form-text">
                                @if($this->available !== null)
                                    @if($this->available === true)
                                        Der Artikel ist
                                    @elseif($this->available == 0)
                                        Artikel
                                    @else
                                        {{$this->available}} Artikel
                                    @endif

                                    @if($start == $end)
                                        an diesem Tag
                                    @else
                                        in diesem Zeitraum
                                    @endif

                                    @if(!$this->available)
                                        nicht
                                    @endif
                                        verfügbar.
                                @else
                                        Artikelverfügbarkeit kann nicht ermittelt werden.
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            <div class="row mb-3">
                <label for="quantity" class="col-sm-4 col-form-label">Anzahl</label>
                <div class="col">
                    <input class="form-control @error('quantity')is-invalid @enderror" id="quantity" wire:model="quantity" required type="number" min="0">
                    @error('quantity')
                    <div class="invalid-feedback">{{$message}}</div>
                    @enderror
                    <div class="form-text" wire:show="quantity === '0'">Dadurch wird dieser Artikel aus der Bestellung entfernt.</div>
                </div>
            </div>
            <div class="row mb-3">
                <label for="price" class="col-sm-4 col-form-label">Betrag</label>
                <div class="col">
                    <div class="input-group has-validation">
                        <input class="form-control @error('price')is-invalid @enderror" id="price" type="number" step="0.01" wire:model="price" required>
                        <span class="input-group-text">€</span>
                        <button type="button" class="btn btn-outline-secondary"
                                @if($this->singleItemAmount === null)
                                    disabled
                                @else
                                    @click="$wire.price = ($wire.quantity * {{$this->singleItemAmount}})"
                                @endif
                                title="Berechneten Betrag übernehmen"
                                wire:loading.attr="disabled">
                            <i class="fa-solid fa-rotate-left"></i>
                        </button>
                        @error('price')
                        <div class="invalid-feedback">{{$message}}</div>
                        @enderror
                    </div>
                    @if($this->singleItemAmount !== null)
                        <div class="form-text">
                            Gewöhnlicher Betrag: <span x-text="($wire.quantity * {{$this->singleItemAmount}}).toFixed(2).replace('.',',')"></span>&#x202f;€
                        </div>
                    @endif
                </div>
            </div>
            <div class="row mb-3">
                <label for="comment" class="col-sm-4 col-form-label">Kommentar</label>
                <div class="col">
                    <div class="autogrow-textarea @error('comment')is-invalid @enderror" data-replicated-value="{{$comment}}">
                        <textarea onInput="this.parentNode.dataset.replicatedValue=this.value" wire:model="comment" rows="2" id="comment" class="form-control @error('comment')is-invalid @enderror"></textarea>
                    </div>
                    @error('comment')
                    <div class="invalid-feedback">{{$message}}</div>
                    @enderror
                </div>
            </div>
            @if($this->disabledDates->isNotEmpty())
                <div class="row">
                    <div class="col">
                        <div class="alert alert-warning my-1 py-1 px-2 w-100 fw-semibold small">
                            <p class="mb-0">Der Mietservice steht in diesem Zeitraum nicht zur Verfügung!</p>
                            <ul class="mb-0">
                                @foreach($this->disabledDates as $disabledDate)
                                    <li>{{$disabledDate->start}} &ndash; {{$disabledDate->end}}: {{$disabledDate->comment}}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif
            @if(is_int($this->available))
                <div class="row" wire:show="quantity > {{$this->available}}" wire:key="{{$this->available}}">
                    <div class="col">
                        <div class="alert alert-warning my-1 py-1 px-2 w-100 fw-semibold small">
                            Die Anzahl der verfügbaren Artikel wird überschritten.
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-danger me-auto" :disabled="!itemFound" wire:loading.attr="disabled" wire:target="loadOrderItem,updateOrderItem"
                wire:click="deleteItem" wire:confirm="Möchtest du den Artikel „{{$orderItem?->item->name}}“ aus dieser Bestellung entfernen?">
            <i class="fa-solid fa-trash-can"></i>&nbsp;Löschen
        </button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Abbrechen</button>
        <button type="submit" class="btn btn-primary" :disabled="!itemFound" wire:loading.attr="disabled">Speichern</button>
    </div>
</form>
