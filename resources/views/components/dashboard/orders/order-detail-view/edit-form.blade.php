@use(App\Enums\OrderStatus)

@script
<script>
    const field = {
            start: document.getElementById('start'),
            end: document.getElementById('end')
        }
        , lastUserValue = {start: null, end: null}

        /**
         * Makes start and end fields of date range follow each other, so that `start <= end` always holds true.
         *
         * @param {Element} element The input field that was changed.
         */
        , changeHandler = element => {
            // get the field index  for the field that was changed by user (primary)
            // and the index for the one that should be adjusted (secondary)
            const primary = element.id
                , secondary = primary === 'start' ? 'end' : 'start'

            // update / initialize user-provided values
            lastUserValue[primary] = field[primary].value
            if (!lastUserValue[secondary])
                lastUserValue[secondary] = field[secondary].value

            // only adjust secondary field when the current field got a value
            if (field[primary].value) {
                // The range is incomplete or invalid, set the secondary field to the primary field value.
                if (!lastUserValue[secondary] || lastUserValue['end'] < lastUserValue['start'])
                    $wire.editOrderForm[secondary] = field[primary].value

                // The range is not invalid (any more). If the secondary field has previously been adjusted, reset it to the last user-provided value.
                else if (field[secondary].value !== lastUserValue[secondary])
                    $wire.editOrderForm[secondary] = lastUserValue[secondary]
            }
        }

    field['start'].addEventListener('change', () => changeHandler(field['start']))
    field['end'].addEventListener('change', () => changeHandler(field['end']))

    // Store submitted values / reset values on form reset.
    $wire.$hook('commit', ({succeed}) => {
        // succeed is called when user cancels or submits form
        succeed(() => {
            lastUserValue['start'] = $wire.editOrderForm['start']
            lastUserValue['end'] = $wire.editOrderForm['end']
        })
    })

    // initialize bootstrap tooltips
    document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(e => new bootstrap.Tooltip(e))
</script>
@endscript

<div class="col-md-6 col-lg-12 col-xl-6">
    <h5>Besteller</h5>
    <div class="row mb-3">
        <label for="forename" class="col-sm-4 col-form-label">Vorname</label>
        <div class="col">
            <input class="form-control @error('editOrderForm.forename')is-invalid @enderror" id="forename" wire:model="editOrderForm.forename" required>
            @error('editOrderForm.forename')
            <div class="invalid-feedback">{{$message}}</div>
            @enderror
        </div>
    </div>
    <div class="row mb-3">
        <label for="surname" class="col-sm-4 col-form-label">Nachname</label>
        <div class="col">
            <input class="form-control @error('editOrderForm.surname')is-invalid @enderror" id="surname" wire:model="editOrderForm.surname" required>
            @error('editOrderForm.surname')
            <div class="invalid-feedback">{{$message}}</div>
            @enderror
        </div>
    </div>
    <div class="row mb-3">
        <label for="legalname" class="col-sm-4 col-form-label">Juristischer Name</label>
        <div class="col">
            <input class="form-control @error('editOrderForm.legalname')is-invalid @enderror" id="legalname" wire:model="editOrderForm.legalname">
            @error('editOrderForm.legalname')
            <div class="invalid-feedback">{{$message}}</div>
            @enderror
        </div>
    </div>
    <div class="row mb-3">
        <label for="street" class="col-sm-4 col-form-label">Rechnungs&shy;adresse</label>
        <div class="col">
            <div class="row mb-2">
                <div class="col-7 col-sm-8">
                    <input class="form-control @error('editOrderForm.street')is-invalid @enderror" id="street" wire:model="editOrderForm.street" placeholder="Straße">
                </div>
                <div class="col">
                    <input class="form-control @error('editOrderForm.number')is-invalid @enderror" id="number" wire:model="editOrderForm.number" placeholder="Hausnummer">
                </div>
                @if($errors->hasAny('editOrderForm.street', 'editOrderForm.number'))
                    <div class="invalid-feedback d-block">
                        {{ $errors->first('editOrderForm.street') }}
                        {{ $errors->first('editOrderForm.number') }}
                    </div>
                @endif
            </div>
            <div class="row">
                <div class="col-5 col-sm-4">
                    <input class="form-control @error('editOrderForm.zipcode')is-invalid @enderror" id="zipcode" wire:model="editOrderForm.zipcode" placeholder="Postleitzahl">
                </div>
                <div class="col">
                    <input class="form-control @error('editOrderForm.city')is-invalid @enderror" id="city" wire:model="editOrderForm.city" placeholder="Ort">
                </div>
                @if($errors->hasAny('editOrderForm.zipcode', 'editOrderForm.city'))
                    <div class="invalid-feedback d-block">
                        {{ $errors->first('editOrderForm.zipcode') }}
                        {{ $errors->first('editOrderForm.city') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
    <div class="row mb-3">
        <label for="email" class="col-sm-4 col-form-label">E-Mail-Adresse</label>
        <div class="col">
            <input type="email" class="form-control @error('editOrderForm.email')is-invalid @enderror" id="email" wire:model="editOrderForm.email" required>
            @error('editOrderForm.email')
            <div class="invalid-feedback">{{$message}}</div>
            @enderror
        </div>
    </div>
    <div class="row">
        <label for="mobile" class="col-sm-4 col-form-label">Handynummer</label>
        <div class="col">
            <input type="tel" class="form-control @error('editOrderForm.mobile')is-invalid @enderror" id="mobile" wire:model="editOrderForm.mobile">
            @error('editOrderForm.mobile')
            <div class="invalid-feedback">{{$message}}</div>
            @enderror
        </div>
    </div>
</div>

<div class="col-md-6 col-lg-12 col-xl-6">
    <h5 class="mt-3 mt-md-0 mt-lg-3 mt-xl-0">Bestellung</h5>
    <div class="row mb-3">
        <label for="status" class="col-sm-4 col-form-label">Status</label>
        <div class="col">
            <select class="form-select @error('editOrderForm.status')is-invalid @enderror" id="status" wire:model="editOrderForm.status" required>
                @foreach(OrderStatus::cases() as $case)
                    <option value="{{$case->value}}">{{$case->getShortName()}}</option>
                @endforeach
            </select>
            @error('editOrderForm.status')
            <div class="invalid-feedback">{{$message}}</div>
            @enderror
        </div>
    </div>
    <div class="row mb-3">
        <label for="eventName" class="col-sm-4 col-form-label">
            <span class="d-none d-md-inline d-lg-none d-xl-inline">Veranstaltungs&shy;name / Verwendungs&shy;zweck</span>
            <span class="d-md-none d-lg-inline d-xl-none">Veranstaltungs&shy;name&nbsp;/ Verwen&shy;dungs&shy;zweck</span>
        </label>
        <div class="col">
            <div class="autogrow-textarea @error('editOrderForm.eventName')is-invalid @enderror" data-replicated-value="{{$editOrderForm->eventName}}">
                <textarea onInput="this.parentNode.dataset.replicatedValue=this.value" wire:model="editOrderForm.eventName" rows="2" id="eventName" class="form-control @error('editOrderForm.eventName')is-invalid @enderror" required></textarea>
            </div>
            @error('editOrderForm.eventName')
            <div class="invalid-feedback">{{$message}}</div>
            @enderror
        </div>
    </div>
    <div class="row mb-3">
        <label for="note" class="col-sm-4 col-form-label">Bemerkung</label>
        <div class="col">
            <div class="autogrow-textarea @error('editOrderForm.note')is-invalid @enderror" data-replicated-value="{{$editOrderForm->note}}">
                <textarea onInput="this.parentNode.dataset.replicatedValue=this.value" wire:model="editOrderForm.note" rows="2" id="note" class="form-control @error('editOrderForm.note')is-invalid @enderror"></textarea>
            </div>
            @error('editOrderForm.note')
            <div class="invalid-feedback">{{$message}}</div>
            @enderror
        </div>
    </div>
    <div class="row mb-3">
        <label for="start" class="col-sm-4 col-form-label">Zeitraum</label>
        <div class="col">
            <div class="input-group has-validation mb-1">
                <input class="form-control @error('editOrderForm.start')is-invalid @enderror" id="start" type="date" wire:model="editOrderForm.start" min="2000-01-01" max="2099-12-31">
                <span class="input-group-text">–</span>
                <input class="form-control @error('editOrderForm.end')is-invalid @enderror" id="end" type="date" wire:model="editOrderForm.end" min="2000-01-01" max="2099-12-31">
                @if($errors->hasAny('editOrderForm.start', 'editOrderForm.end'))
                    <div class="invalid-feedback">
                        {{ $errors->first('editOrderForm.start') ?: $errors->first('editOrderForm.end') }}
                    </div>
                @endif
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="recalculatePrice" wire:model="editOrderForm.recalculatePrice">
                <label class="form-check-label" for="recalculatePrice">Betrag neu berechnen</label>
                <span data-bs-title="Dadurch werden alle Artikelbeträge bei einer Anpassung des Zeitraums anhand des geänderten Zeitraums neu berechnet. Dies überschreibt einen ggf. gewährten Artikelrabatt."
                      data-bs-toggle="tooltip"
                      tabindex="0" {{-- makes tooltip keyboard-accessible --}}
                      class="px-1">
                    <i class="fa-regular fa-circle-question"></i>
                </span>
            </div>
            @if(!$order->hasSinglePeriod)
                <div class="form-text">Die Artikel verfügen über unterschiedliche Zeiträume. Durch Festlegen eines Zeitraums hier wird der Zeitraum aller Artikel überschrieben.</div>
            @endif
        </div>
    </div>
    <div class="row mb-3">
        <label for="deposit" class="col-sm-4 col-form-label">Kaution</label>
        <div class="col">
            <div class="input-group has-validation">
                <input class="form-control @error('editOrderForm.deposit')is-invalid @enderror" id="deposit" type="number" min="0" wire:model="editOrderForm.deposit" required>
                <span class="input-group-text">€</span>
                @error('editOrderForm.deposit')
                <div class="invalid-feedback">{{$message}}</div>
                @enderror
            </div>
        </div>
    </div>
    <div class="row">
        <label for="discount" class="col-sm-4 col-form-label">Rabattierung</label>
        <div class="col">
            <div class="input-group has-validation">
                <input class="form-control @error('editOrderForm.discount')is-invalid @enderror" id="discount" type="number" min="0" max="100" wire:model="editOrderForm.discount" required>
                <span class="input-group-text">%</span>
                @error('editOrderForm.discount')
                <div class="invalid-feedback">{{$message}}</div>
                @enderror
            </div>
        </div>
    </div>
</div>
