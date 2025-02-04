@use(App\Enums\OrderStatus)

@php($statusDefinitions = [
    'primary'   => null,
    'warning'   => OrderStatus::PENDING,
    'purple'    => OrderStatus::WAITING,
    'info'      => OrderStatus::PROCESSING,
    'secondary' => OrderStatus::CANCELLED,
    'success'   => OrderStatus::COMPLETED,
])

<x-slot:breadcrumbs>
    <li class="breadcrumb-item"><i class="fa-solid fa-cart-shopping"></i>&nbsp;Bestellungen</li>
    <li class="breadcrumb-item">Übersicht</li>
</x-slot:breadcrumbs>

<div>
    <h1 class="mb-4">Bestellungen</h1>

    <x-status-message />

    <a href="{{route('dashboard.orders.create')}}" wire:navigate class="btn btn-primary mb-3"><i class="fa-solid fa-plus"></i>&nbsp;Neue Bestellung anlegen</a><br>

    <div class="row text-nowrap">
        <label class="col-form-label col-xxl-auto col-xl-12 col-lg-auto">
            Bestellungen anzeigen:
        </label>
        <div class="col filter-button-container">
            <div class="row d-none d-sm-flex">
                <div class="col-lg-12 col-xl mb-2">
                    <div class="row">
                        @foreach(array_slice($statusDefinitions, 0, 3) as $color => $status)
                            <div class="col-4 px-1">
                                <input type="radio" class="btn-check" id="filter-{{$status->value ?? 'all'}}" name="filter" data-filter-target="{{$status->value ?? 'all'}}" autocomplete="off" @empty($status)checked @endif>
                                <label class="w-100 btn btn-{{$color}} btn-{{$color}}-subtle px-lg-1 position-relative" for="filter-{{$status->value ?? 'all'}}">
                                    <div class="d-flex flex-wrap justify-content-center position-absolute start-0 top-0 w-100 h-100 overflow-hidden btn border-0">
                                        <span>{{$status?->getShortName() ?? 'Alle'}}</span>
                                        <span class="fw-normal">&nbsp;({{$this->countOrders($status)}})</span>
                                    </div>
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="col-lg-12 col-xl mb-md-3">
                    <div class="row">
                        @foreach(array_slice($statusDefinitions, 3) as $color => $status)
                            <div class="col-4 px-1">
                                <input type="radio" class="btn-check" id="filter-{{$status->value ?? 'all'}}" name="filter" data-filter-target="{{$status->value ?? 'all'}}" autocomplete="off">
                                <label class="w-100 btn btn-{{$color}} btn-{{$color}}-subtle px-lg-1 position-relative" for="filter-{{$status->value ?? 'all'}}">
                                    <div class="d-flex flex-wrap justify-content-center position-absolute start-0 top-0 w-100 h-100 overflow-hidden btn border-0">
                                        <span>{{$status?->getShortName() ?? 'Alle'}}</span>
                                        <span class="fw-normal">&nbsp;({{$this->countOrders($status)}})</span>
                                    </div>
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="row d-sm-none">
                @foreach($statusDefinitions as $color => $status)
                    <div class="col-6 p-1">
                        <input type="radio" class="btn-check" id="filter-{{$status->value ?? 'all'}}-sm" name="filter-sm" data-filter-target="{{$status->value ?? 'all'}}" autocomplete="off" @empty($status)checked @endif>
                        <label class="w-100 btn btn-{{$color}} btn-{{$color}}-subtle btn-sm px-1 position-relative" for="filter-{{$status->value ?? 'all'}}-sm">
                            <div class="d-flex flex-wrap justify-content-center position-absolute start-0 top-0 w-100 h-100 overflow-hidden btn btn-sm border-0">
                                <span>{{$status?->getShortName() ?? 'Alle'}}</span>
                                <span class="fw-normal">&nbsp;({{$this->countOrders($status)}})</span>
                            </div>
                        </label>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <livewire:data-table :elements="$this->orders" item-component="dashboard.orders.order-list-entry" id="dt-order-list" />
    {{-- following script cannot be inside @script tags as it depends on jQuery which gets only loaded by data-table component. @script runs before --}}
    <script>$('#dt-order-list').on('init.dt', () => document.getElementById('filter-pending').dispatchEvent(new Event('change', {bubbles: true})))</script>
</div>

@script
<script>
    (() => {
        const defaultTarget = 'pending' // default filter to check when all options got unchecked. Only relevant when buttons are checkboxes instead of radio buttons
        const container = document.getElementsByClassName('filter-button-container')[0]

        if (!container)
            return;

        container.addEventListener('change', event => {
            const target = event.target

            // check datatable initialisation
            const table = $('#dt-order-list').DataTable()
            if (!table.ready()) { // revert changes
                target.checked = !target.checked
                event.stopPropagation()
                event.preventDefault()

                return;
            }

            // synchronize state of small buttons (mobile) and large buttons
            container.querySelectorAll(`[data-filter-target="${target.dataset.filterTarget}"]`).forEach(e => e.checked = target.checked)

            // when we got checkboxes instead of radio boxes additional constraints have to be checked
            if (container.querySelector('[data-filter-target][type="checkbox"]')) {
                // if none are checked, check default one
                if (!container.querySelector('input:checked'))
                    container.querySelectorAll(`[data-filter-target="${defaultTarget}"]`).forEach(e => e.checked = true)

                // if "all" got checked, check the other ones
                else if (target.checked && target.dataset.filterTarget === 'all')
                    container.querySelectorAll('input').forEach(e => e.checked = true)

                // if any single category got unchecked, uncheck "all"
                else if (!target.checked && target.dataset.filterTarget !== 'all')
                    container.querySelectorAll('[data-filter-target="all"]').forEach(e => e.checked = false)

                // if "all" got unchecked, make sure only default one remains checked
                else if (!target.checked && target.dataset.filterTarget === 'all')
                    container.querySelectorAll('input').forEach(e => e.checked = e.dataset.filterTarget === defaultTarget)

                // if the last single category got checked, make sure "all" gets checked as well
                else if (!container.querySelector('input:not([data-filter-target="all"]):not(:checked)'))
                    container.querySelectorAll('[data-filter-target="all"]').forEach(e => e.checked = true)
            }

            // Now we got a consistent set of filter input elements. Generate filter and perform search
            const expressions = {}
            container.querySelectorAll('input:checked').forEach(e => expressions[e.dataset.filterTarget] = true)

            table.column('.status')
                .search.fixed('statusFilter', Object.hasOwn(expressions, 'all') ? null : new RegExp(Object.keys(expressions).join('|')))
                .draw()
        });
    })()
</script>
@endscript
