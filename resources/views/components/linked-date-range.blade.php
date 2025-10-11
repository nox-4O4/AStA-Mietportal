@props([
	'class' => '',
	'modelPrefix' => '',
	'idSuffix' => '',
	'live' => false,
	'required' => false,
])

@script
<script>
    // When changing an order from completed to open script runs before Livewire applied DOM change, leading to elements not yet existing. Using $nextTick delays execution until after DOM change is complete.
    $nextTick(() => {
        const field = {
                start: document.getElementById('start{{$idSuffix}}'),
                end: document.getElementById('end{{$idSuffix}}')
            }
            , lastUserValue = {start: null, end: null}
            , wireObject = $wire{{$modelPrefix ? '.' . trim($modelPrefix, '.') : '' }}

        /**
         * Makes start and end fields of date range follow each other, so that `start <= end` always holds true.
         *
         * @param {Element} element The input field that was changed.
         */
            , changeHandler = element => {
                // get the field index for the field that was changed by user (primary)
                // and the index for the one that should be adjusted (secondary)
                const primary = element.dataset.role
                    , secondary = primary === 'start' ? 'end' : 'start'

                // update / initialize user-provided values
                lastUserValue[primary] = field[primary].value
                if (!lastUserValue[secondary])
                    lastUserValue[secondary] = field[secondary].value

                // only adjust secondary field when the current field got a value
                if (field[primary].value) {
                    // The range is incomplete or invalid, set the secondary field to the primary field value.
                    if (!lastUserValue[secondary] || lastUserValue['end'] < lastUserValue['start'])
                        wireObject[secondary] = field[primary].value

                    // The range is not invalid (any more). If the secondary field has previously been adjusted, reset it to the last user-provided value.
                    else if (field[secondary].value !== lastUserValue[secondary])
                        wireObject[secondary] = lastUserValue[secondary]
                }
            }

        field['start'].addEventListener('change', () => changeHandler(field['start']))
        field['end'].addEventListener('change', () => changeHandler(field['end']))

        // Store submitted values / reset values on form reset.
        $wire.$hook('commit', ({commit, succeed}) => {
            succeed(() => {
                // With live updates of properties `commit.calls` is empty.
                // We mustn't store values for live updates as this would override user-entered values when values are adjusted automatically.
                if (commit.calls.length) {
                    lastUserValue['start'] = wireObject['start']
                    lastUserValue['end'] = wireObject['end']
                }
            })
        })
    })
</script>
@endscript

<div class="input-group has-validation {{$class}}">
    <input class="form-control @error("{$modelPrefix}start")is-invalid @enderror" id="start{{$idSuffix}}" type="date" wire:model{{$live ? '.live' : ''}}="{{$modelPrefix}}start" min="2000-01-01" max="2099-12-31" data-role="start" {{$required ? 'required' : ''}}>
    <span class="input-group-text">&ndash;</span>
    <input class="form-control @error("{$modelPrefix}end")is-invalid @enderror" id="end{{$idSuffix}}" type="date" wire:model{{$live ? '.live' : ''}}="{{$modelPrefix}}end" min="2000-01-01" max="2099-12-31" data-role="end" {{$required ? 'required' : ''}}>
    <div class="invalid-feedback">
        @if($errors->hasAny("{$modelPrefix}start", "{$modelPrefix}end"))
            {{ $errors->first("{$modelPrefix}start") ?: $errors->first("{$modelPrefix}end") }}
        @endif
    </div>
</div>
