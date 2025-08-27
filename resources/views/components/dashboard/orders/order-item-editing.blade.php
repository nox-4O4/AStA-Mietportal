@use(App\Models\DisabledDate)
@use(App\Models\OrderItem)
@use(App\Enums\OrderStatus)
@use(Carbon\CarbonImmutable)

@script
<script>
    const modal = document.getElementById('editOrderItem')
        , form = document.getElementById('editOrderItemForm')
        , title = document.getElementById('modalAction')

    let lastItemId = null

    // We're using a single modal for all items. Thus, we have to load corresponding item data when modal opens.
    modal.addEventListener('show.bs.modal', async e => {
        const itemId = +e.relatedTarget.dataset.bsOrderItem
            , data = Alpine.$data(form)

        data.initialRequest = lastItemId !== itemId

        if (itemId) {
            title.textContent = 'bearbeiten';
            data.newItem = false
            data.itemFound = await $wire.loadOrderItem(itemId)
        } else {
            title.textContent = 'hinzufügen';
            data.newItem = true
            await $wire.resetOrderItem()
        }

        lastItemId = data.itemFound ? itemId : null
        data.initialRequest = false
    })

    // Reset itemFound flag when closing modal to not have message flash when editing another item lateron.
    modal.addEventListener('hidden.bs.modal', () => Alpine.$data(form).itemFound = true)

    // called from livewire component on successfull item update
    $js('closeModal', () => bootstrap.Modal.getInstance(modal).hide())
</script>
@endscript

<form wire:submit="saveOrderItem" x-data="{itemFound: true, initialRequest: true, newItem: false}" id="editOrderItemForm">
    <div class="modal-body position-relative">
        <div class="position-absolute start-0 top-0 bg-body h-100 w-100 z-3 d-flex" x-cloak x-show.important="initialRequest">
            <span class="d-inline-block m-auto">
                <i class="fas fa-spinner fa-pulse"></i>&nbsp;Bitte warten...
            </span>
        </div>

        <div class="text-center" x-cloak x-show="!itemFound">
            <p>Dieser Artikel wurde in der aktuellen Bestellung nicht gefunden.</p>
            <button type="button" class="btn btn-primary" wire:click="$dispatch('order-items-changed')" data-bs-dismiss="modal">
                <i class="fa-solid fa-arrows-rotate"></i>
                Artikelliste neu laden
            </button>
        </div>

        <div x-show="itemFound">
            @if($order->status == OrderStatus::CANCELLED || $order->status == OrderStatus::COMPLETED)
                <div class="row">
                    <div class="col">
                        <div class="alert alert-warning">
                            Du bearbeitest gerade eine Bestellung, die bereits abgeschlossen oder storniert wurde. Bitte stelle sicher, dass dies deiner Absicht entspricht.
                        </div>
                    </div>
                </div>
            @endif

            <div class="row mb-3">
                <label for="itemId" class="col-sm-4 col-form-label">Artikel</label>
                <div class="col">
                    <select required class="form-control @error('itemId')is-invalid @enderror" id="itemId" wire:model.live="itemId">
                        <option hidden value="">Bitte wählen...</option>

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
                    @error('itemId')
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
                    <div wire:loading.remove wire:target="start,end">
                        <div class="form-text">
                            @if($start && $end && $itemId)
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
                            @else
                                        Bitte einen Artikel und Zeitraum wählen.
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <label for="quantity" class="col-sm-4 col-form-label">Anzahl</label>
                <div class="col">
                    <input class="form-control @error('quantity')is-invalid @enderror" id="quantity" wire:model="quantity" required type="number" :min="newItem ? 1 : 0">
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
                        <input class="form-control @error('price')is-invalid @enderror" id="price" type="number" step="0.01" wire:model="price" required
                               {{-- Automatically apply calculated price for new items. We have to decouple changing of $wire.price from x-effect as otherwise price gets overwritten by calculated value when user manually changes input value. --}}
                               x-data="{targetPrice: ''}" {{-- the automatically calculated target price --}}
                               x-effect="targetPrice = {{ isset($this->singleItemAmount) ? "\$wire.quantity * $this->singleItemAmount" : "''" }}" {{-- Gets executed when quantity or singleItemAmount change. Must not include $wire.price directly. Set to empty string when item is not yet selected. --}}
                               x-init="$watch('targetPrice', target => newItem && ($wire.price = target))" {{-- Adjust $wire.price when targetPrice was changed and we're currently creating a new order item. --}}
                        >
                        <span class="input-group-text">€</span>
                        <button type="button" class="btn btn-outline-secondary"
                                @if($this->singleItemAmount === null)
                                    disabled
                                @else
                                    @click="$wire.price = ($wire.quantity * {{$this->singleItemAmount}})"
                                @endif
                                title="Gewöhnlichen Betrag übernehmen (automatisch berechnet)"
                                wire:loading.attr="disabled">
                            <i class="fa-solid fa-rotate-left"></i>
                        </button>
                        <div class="invalid-feedback">
                            @error('price'){{$message}}@enderror
                        </div>
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
            <div class="row mb-3">
                <div class="col">
                    <div class="form-check">
                        <input class="form-check-input @error('updateDeposit')is-invalid @enderror" type="checkbox" value="1" id="updateDeposit" wire:model="updateDeposit">
                        <label class="form-check-label" for="updateDeposit">Kaution aktualisieren</label>
                        @error('updateDeposit')
                        <div class="invalid-feedback mt-0">{{$message}}</div>
                        @enderror
                    </div>
                </div>
            </div>
            @if($this->singleItemAmount === null)
                {{-- Add some dummy space to prevent dialog height change after choosing an item. Otherwise, buttons will jump around which is bad for UX as modal vertically centered. --}}
                <div class="form-text">
                    &nbsp;
                </div>
            @endif
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
        <button type="button" class="btn btn-danger me-auto" :disabled="!itemFound" x-show="!newItem"
                wire:loading.attr="disabled" wire:target="loadOrderItem,updateOrderItem"
                wire:click="deleteItem" wire:confirm="Möchtest du den Artikel „{{htmlspecialchars(OrderItem::find($orderItemId)?->item->name)}}“ aus dieser Bestellung entfernen?">
            <i class="fa-solid fa-trash-can"></i>&nbsp;Löschen
        </button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Abbrechen</button>
        <button type="submit" class="btn btn-primary" :disabled="!itemFound" wire:loading.attr="disabled">Speichern</button>
    </div>
</form>
