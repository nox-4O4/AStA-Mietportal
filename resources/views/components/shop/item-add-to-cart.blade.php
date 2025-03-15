<div x-data="{
    cleanup: $wire.$js.cleanup,
    destroy() {this.cleanup()}
}">
    @script
    <script>
        const minDate = new Date('{{$this->minDate->format('c')}}')
            , maxDateStr = '{{$this->maxDate?->format('c')}}'
            , maxDate = maxDateStr ? new Date(maxDateStr) : null
            , defaultAmount = ({{$item->amount}})
            , defaultAvailable = ({{$item->available ? 'true' : 'false'}})
            , disabledRanges = [
                /* @foreach($this->disabledDates as $disabledRange) */ {{-- need to comment out those tags with JS comments as Livewire injects HTML comment markers with @foreach tags for DOM diffing... --}}
                [
                    new Date('{{$disabledRange->start->format('c')}}'),
                    new Date('{{$disabledRange->end->format('c')}}'),
                ],
                /* @endforeach */
            ]
            , availabilities = [
                /* @foreach($this->item->getFutureAvailabilities(false) as $availability) */
                [
                    new Date('{{$availability->date->format('c')}}'),
                    {{$availability->available}},
                ],
                /* @endforeach */
            ]
            /* @if($start && $end) */
            , startDate = new Date('{{$start->format('c')}}')
            , endDate = new Date('{{$end->format('c')}}')
            /* @endif */
            , selectedDates = typeof startDate !== 'undefined' ? [startDate, endDate] : false

            /**
             * @param date
             * @returns {"out-of-range"|"disabled"|"available-all"|"available-some"|"not-available"}
             */
            , getBookingStatus = date => {
                // check range bounds
                if (date < minDate || maxDate && date > maxDate) {
                    return 'out-of-range'
                }

                // check disabled dates
                for (const [start, end] of disabledRanges) {
                    if (date >= start && date <= end) {
                        return 'disabled'
                    }
                }

                // check availabilities
                if (!defaultAvailable) {
                    return 'not-available'
                }

                for (const [availabilityDate, amount] of availabilities) {
                    if (date.getTime() === availabilityDate.getTime()) {
                        return amount === defaultAmount
                            ? 'available-all'
                            : amount
                                ? 'available-some'
                                : 'not-available'
                    }
                }

                return 'available-all'
            }

            /**
             * @param {Date} dateA
             * @param {Date} dateB
             * @returns {boolean} returns true if the specified range is valid, false otherwise
             */
            , isRangeValid = (dateA, dateB) => {
                if (!defaultAvailable)
                    return false

                if (dateA > dateB)
                    return isRangeValid(dateB, dateA)

                if (dateA < minDate || maxDate && dateB > maxDate)
                    return false

                for (const [start, end] of disabledRanges) {
                    if (dateA <= end && dateB >= start)
                        return false
                }

                if (!defaultAmount)
                    return true

                for (const [availabilityDate, amount] of availabilities) {
                    if (!amount && dateA <= availabilityDate && dateB >= availabilityDate)
                        return false
                }

                return true
            }

            , updateApplyButton = () => datepicker.$datepicker?.querySelectorAll('button.apply').forEach(b => b.toggleAttribute('disabled', !datepicker.selectedDates.length))

            /**
             * @param {array<Date>} dates
             * @param {boolean} skipYear
             * @returns {string}
             */
            , formatDates = (dates, skipYear = false) => {
                if (dates.length !== 2)
                    return '';

                const formattedDate = [
                    dates[0].toLocaleDateString(undefined, {day: '2-digit', month: '2-digit', ...(skipYear || {year: 'numeric'})}),
                    dates[1].toLocaleDateString(undefined, {day: '2-digit', month: '2-digit', ...(skipYear || {year: 'numeric'})}),
                ]

                if (formattedDate[0] === formattedDate[1])
                    return formattedDate[0]

                return `${formattedDate[0]} \u2013 ${formattedDate[1]}`
            }

            , renderCell = ({date, cellType, datepicker}) => {
                if (cellType !== 'day')
                    return;

                const state = getBookingStatus(date)

                if (state === 'out-of-range')
                    return; // use default cell content

                const titles = {
                    'available-all': 'Verfügbar',
                    'available-some': 'Wenige verfügbar',
                    'not-available': 'Nicht verfügbar',
                    'disabled': 'Mietservice in diesem Zeitraum nicht verfügbar',
                }

                return {
                    html: `
                        <div class="text-center" title="${titles[state]}">
                            <p class="m-0 fw-semibold">${date.getDate()}</p>
                            <p class="m-0 small">
                               ${state === 'available-all' ? '<i class="fa-solid fa-check text-success"></i>' : ''}
                               ${state === 'available-some' ? '<span class="text-warning">(<i class="fa-solid fa-check"></i>)</span>' : ''}
                               ${state === 'not-available' ? '<i class="fa-solid fa-xmark text-danger"></i>' : ''}
                               ${state === 'disabled' ? '<i class="fa-solid fa-ban text-secondary"></i>' : ''}
                            </p>
                        </div>
                    `,
                    disabled: state === 'disabled' || state === 'not-available',
                }
            }

            , renderNavTitle = datepicker => {
                const viewDateStr = datepicker.viewDate.toLocaleDateString(undefined, {month: 'long', year: "numeric"})

                let subtext
                switch (datepicker.selectedDates.length) {
                    case 0:
                        subtext = '<p class="fw-bold">Startdatum wählen</p>';
                        break;

                    case 1:
                        subtext = '<p class="fw-bold">Enddatum wählen</p>';
                        break;

                    default:
                        const sameYear = datepicker.selectedDates[0].getFullYear() === datepicker.selectedDates[1].getFullYear() &&
                            datepicker.selectedDates[0].getFullYear() === datepicker.viewDate.getFullYear()

                        const displayPretext = sameYear || datepicker.selectedDates[0].getTime() === datepicker.selectedDates[1].getTime()

                        subtext = `<p class="text-italic">${displayPretext ? 'Gewählt: ' : ''}${formatDates(datepicker.selectedDates, sameYear)}</p>`;
                        break;
                }

                return `
                        <div class="text-center">
                            <p>${viewDateStr}</p>
                            ${subtext}
                        </div>
                    `;
            }

            , btnResetAction = datepicker => {
                datepicker.clear()

                // workaround for highlighted range days not being resetted
                datepicker.$datepicker.querySelectorAll('.-in-range-, .-range-from-, .-range-to-').forEach(e => e.classList.remove('-in-range-', '-range-from-', '-range-to-'))
            }

            , btnApplyAction = datepicker => {
                if (datepicker.selectedDates.length === 1) {
                    // On mobile, it might not be obvious to tap the same date again to set the end date when only a single day is required.
                    // So, we treat incomplete ranges as single day ranges and complete the range here.
                    datepicker.selectDate(datepicker.selectedDates[0])
                }

                datepicker.hide()
            }

            , {onShow, onHide} = (() => {
                /** @type {Date[]} */
                let initialValues = [];

                const onShow = () => {
                    initialValues = datepicker.selectedDates.slice() // need to copy array as otherwise initialValues sometimes get changed when expanding a single-day-range to a multi-day-range
                    updateApplyButton()
                }

                const onHide = (isFinished) => {
                    if (isFinished)
                        return;

                    if (datepicker.selectedDates.length !== 2) {
                        datepicker.clear()
                        datepicker.selectDate(initialValues)
                    } else if ( // check if selected dates were changed
                        initialValues.length !== datepicker.selectedDates.length ||
                        initialValues[0] !== undefined && initialValues[0].getTime() !== datepicker.selectedDates[0].getTime() ||
                        initialValues[1] !== undefined && initialValues[1].getTime() !== datepicker.selectedDates[1].getTime()
                    ) {
                        $wire.start = datepicker.selectedDates[0] ? new Date(datepicker.selectedDates[0].getTime() - datepicker.selectedDates[0].getTimezoneOffset() * 60 * 1000).toISOString().split('T')[0] : null;
                        $wire.end = datepicker.selectedDates[1] ? new Date(datepicker.selectedDates[1].getTime() - datepicker.selectedDates[1].getTimezoneOffset() * 60 * 1000).toISOString().split('T')[0] : null;
                        $wire.$refresh();
                    }
                }

                return {onShow, onHide}
            })()

            , updatePosition = () => {
                const isMobile = window.innerWidth < 576 // equals bootstrap breakpoint
                const dpMaxHeight = 420 // magic number (6x day cell + header + buttons + offset from input element + some offset at the bottom)

                if (isMobile) {
                    // workaround for buggy update behaviour: we have to manually clear and reselect selected dates as update function will always reset selected dates to values provided in options object ([] when omitted, i.e. it unselects all dates)
                    const selectedDates = datepicker.selectedDates
                    datepicker.clear()
                    datepicker.update({isMobile, selectedDates})
                } else {
                    // check if position has to be adjusted to prevent horizontal / vertical overflow
                    const dpWidth = parseInt(getComputedStyle(document.documentElement).getPropertyValue('--datepicker-width')),
                        alignRight = dpWidth > datepicker.$el.getBoundingClientRect().width,
                        placeBelow = datepicker.$el.offsetHeight + datepicker.$el.offsetTop + dpMaxHeight < datepicker.$el.offsetParent.scrollHeight,
                        hTarget = alignRight ? 'right' : 'left',
                        hInverse = alignRight ? 'left' : 'right',
                        vTarget = placeBelow ? 'bottom' : 'top',
                        vInverse = placeBelow ? 'top' : 'bottom'

                    datepicker.$datepicker?.classList.remove(`-${vInverse}-${hInverse}-`, `-${vInverse}-${hTarget}-`, `-${vTarget}-${hInverse}-`) // bug: old class does not get removed on position update so we have to remove it ourselves
                    datepicker.$datepicker?.classList.add(`-${vTarget}-${hTarget}-`) // bug: new class only gets added on datepicker show, not on position update, so we have to add it ourselves to prevent unstyled pointer during size change

                    // workaround for buggy update behavior: we have to manually clear and reselect selected dates as update function will always reset selected dates to values provided in options object ([] when omitted, i.e. it unselects all dates)
                    const selectedDates = datepicker.selectedDates
                    datepicker.clear()
                    datepicker.update({isMobile, position: `${vTarget} ${hTarget}`, selectedDates})
                }

                // when switching from mobile to standard view buttons get re-renderd, so we have to update button to reapply disabled attribute if required
                updateApplyButton()
            }

            , datepicker = new AirDatepicker('#duration', {
                classes: 'user-select-none',
                locale: AirDatepicker.DefaultLocale,
                multipleDates: true, // otherwise dateFormat only gets the first date
                moveToOtherMonthsOnSelect: false,
                minDate,
                maxDate: maxDateStr,
                startDate: selectedDates[0] ?? minDate,
                selectedDates,
                range: true,
                dynamicRange: false, // need to disable dynamic ranges as otherwise it's possible to drag range over disabled date, see https://github.com/t1m0n/air-datepicker/issues/664
                navTitles: {days: renderNavTitle},
                buttons: [{
                    attrs: {type: 'button', class: 'btn btn-outline-secondary btn-sm fs-6 me-1'},
                    content: '<div><i class="fa-solid fa-arrow-rotate-left"></i>&nbsp;Zurücksetzen</div>',
                    onClick: btnResetAction
                }, {
                    attrs: {type: 'button', class: 'btn btn-outline-primary btn-sm fs-6 ms-1 apply'},
                    content: '<div><i class="fa-solid fa-check"></i>&nbsp;Festlegen</div>',
                    onClick: btnApplyAction
                }],
                onShow,
                onHide,
                dateFormat: formatDates,
                onSelect: updateApplyButton,
                onRenderCell: renderCell,
                toggleSelected: ({datepicker, date}) => datepicker.selectedDates.length !== 1, // prevent unselecting single date as otherwise it's not possible to choose a single day by clicking the day twice
                onBeforeSelect: ({datepicker, date}) => datepicker.selectedDates.length !== 1 || isRangeValid(date, datepicker.selectedDates[0]), // prevent selection of invalid ranges
                onFocus: ({datepicker, date}) => datepicker.$datepicker.classList.toggle('-invalid-range-', datepicker.selectedDates.length === 1 && !isRangeValid(date, datepicker.selectedDates[0])), // add attribute to style invalid ranges when viewing different months
            })
            , abortController = new AbortController()

        // We have to force initialisation of mobile overlay in case page was cached as reference to previous overlay won't be in DOM any more. (It also does not reference cached DOM element which gets removed below.)
        // Call to _createMobileOverlay() must occur before first call to updatePosition() as otherwise a second mobile overlay gets created when page was not cached. Also, isMobile must not be set to true in costructor options for the same reason.
        datepicker._createMobileOverlay()

        updatePosition()
        window.addEventListener('resize', updatePosition, {signal: abortController.signal})
        $js('cleanup', () => {
            abortController.abort()
            datepicker.destroy()
        })

        $wire.on('item-added-to-cart', () => datepicker.clear())
    </script>
    @endscript
    <fieldset>
        <legend>Artikel buchen</legend>
        <div class="row mb-3">
            <label for="duration" class="col-form-label col-lg-2 col-3">Zeitraum</label>
            <div class="col">
                <input class="form-control readonly-default @if($errors->hasAny('start', 'end'))is-invalid @endif" id="duration" placeholder="Datum wählen" readonly>
                @if($errors->hasAny('start', 'end'))
                    <div class="invalid-feedback">
                        @foreach($errors->get('start') as $error)
                            {{ $error }}
                        @endforeach
                        @foreach($errors->get('end') as $error)
                            {{ $error }}
                        @endforeach
                    </div>
                @endif

                @if(config('shop.booking_ahead_days_min'))
                    @php($constraints[] = 'müssen mindestens ' . config('shop.booking_ahead_days_min'))
                @endif
                @if(config('shop.booking_ahead_days_max'))
                    @php($constraints[] = 'können höchstens ' . config('shop.booking_ahead_days_max'))
                @endif
                @isset($constraints)
                    <div class="form-text">
                        Buchungen {{Arr::join($constraints, ', ', ' und ')}} Tage im Voraus vorgenommen werden.
                    </div>
                @endisset
            </div>
        </div>
    </fieldset>
    @if(!$start || !$end || $errors->hasAny('start', 'end'))
        <p wire:loading wire:target="start,end" wire:loading.block>
            <i class="fas fa-spinner fa-pulse"></i>&nbsp;Bitte warten...
        </p>

        <button title="Bitte erst einen Zeitraum wählen" class="btn btn-outline-secondary btn-lg disabled pointer-events-auto" type="button" onclick="document.getElementById('duration-missing-message').classList.add('d-block')"><i class="fa-solid fa-cart-plus"></i> In den Warenkorb</button>
        <div class="invalid-feedback" id="duration-missing-message">Bitte erst einen Zeitraum wählen.</div>
    @else
        @php($available = $item->getMaximumAvailabilityInRange($start, $end))
        <form wire:submit="addToCart" x-data="{amount: $wire.entangle('amount'), showComment: $wire.comment !== ''}">
            <div class="row position-relative">
                <div class="col mb-2">
                    <p class="text-success fw-semibold mb-2">
                        @if($start == $end)
                            An diesem Tag
                        @else
                            In diesem Zeitraum
                        @endif
                        @if($available !== true)
                            noch {{$available}} Stück
                        @endif
                            verfügbar!
                    </p>
                    @if($start != $end && $item->price && null !== $chargedDays = $this->priceCalculator->getChargedDays($item, $start, $end))
                        <p class="mb-2">
                            Berechnete Tage: {{$chargedDays}}<br>
                            Preis pro Stück: @money($this->priceCalculator->calculatePrice($item, $start, $end)) (gesamter Zeitraum)
                        </p>
                    @endif
                </div>
                <div class="position-absolute top-0 h-100 pb-3" wire:target="start,end" wire:loading>
                    <div class="h-100 d-flex align-items-center bg-body">
                        <i class="fas fa-spinner fa-pulse"></i>&nbsp;Bitte warten...
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <label for="amount" class="col-form-label col-lg-2 col-3">Anzahl</label>
                <div class="col">
                    <input type="number" step="1" min="1" @if($available !== true)max="{{$available}}" @endif class="form-control @error('amount')is-invalid @enderror" id="amount" required wire:model="amount">
                    @error('amount')
                    <div class="invalid-feedback">{{$message}}</div>
                    @enderror
                </div>
            </div>
            <p x-show="!showComment">
                <button type="button" class="btn btn-link p-0" x-on:click="showComment=true">Kommentar zur Buchung hinzufügen</button>
            </p>
            <div class="row mb-3" x-show="showComment" x-cloak>
                <label for="comment" class="col-form-label col-lg-2 col-3 pe-0">Kommen&shy;tar</label>
                <div class="col">
                    <div class="autogrow-textarea @error('comment')is-invalid @enderror" data-replicated-value="{{$comment}}">
                        <textarea onInput="this.parentNode.dataset.replicatedValue=this.value" wire:model="comment" rows="2" id="comment" class="form-control @error('comment')is-invalid @enderror" placeholder="Optionaler Kommentar zur Bestellung"></textarea>
                    </div>
                    @error('comment')
                    <div class="invalid-feedback">{{$message}}</div>
                    @enderror
                </div>
            </div>
            @if($this->cartRepository->containsItem($item))
                <div class="row mb-2">
                    <div class="col-auto">
                        <div class="alert-primary alert small p-2 m-0">
                            Dieser Artikel befindet sich bereits im Warenkorb.
                        </div>
                    </div>
                </div>
            @endif
            <div class="row">
                <div class="col">
                    <button wire:key="{{rand()}}" class="btn btn-lg" :class="amount && $el.form.checkValidity() ? 'btn-primary' : 'btn-outline-primary'"><i class="fa-solid fa-cart-plus"></i> In den Warenkorb</button>
                </div>
            </div>
        </form>
    @endif
    <x-status-message scope="cart.status" wire:loading.remove class="mt-3" />
    <script>
        // remove stale markup when page was cached
        document.getElementById('air-datepicker-global-container')?.remove()
    </script>
</div>

