@assets
<script src="/static/datatables/datatables.min.js" integrity="sha384-A4d9of2hRmPrBlPk1pQZLQMSG7/Uo3H0cZ1X0gMzIPON+P5/usUt1fK7oA9SU/8J" crossorigin="anonymous"></script>
<link rel="stylesheet" href="/static/datatables/datatables.min.css" integrity="sha384-da66bu6P8r0okiL41GigAdrH0vl8Q4dzVMWiMjj50mjIDUh8KIB4UOVKaxieAosy" crossorigin="anonymous">
@endassets

<div>
    {{--
        Workaround for livewire bug: https://github.com/livewire/livewire/discussions/6722
        When navigating back, table has lost internal state, stale markup remains and datatables
        script gets re-executed on stale (and already modified) markup, leading to errors.

        Workaround: on navigation, check if this is a cached page (true for browser back / forward navigation).
        When is the case, manually refresh component to re-initialize table. After refreshing we need to rerun DataTable initializing code.
        This is handled by the two custom attributes wire:rendered and wire:refresh-when-cached defined in LivewireExtensions.js

        This way the table will also display updated information.
    --}}

    <table id="{{$id}}"
           wire:rendered="CreateDataTable(element.id)"
           wire:refresh-when-cached
           class="table table-hover responsive fancy-datatable {{$class}}"
           @if($elements->count() <= 10)data-paging="false" @endif
            {!! collect($elementAttributes)->map(fn($value, $name) => $name . '="' . htmlspecialchars($value) . '"')->join(' ') !!}>
        <thead>
        <tr>
            <th data-orderable="false" data-searchable="false" class="control">&nbsp;</th>
            <x-dynamic-component :component="$itemComponent" :attributes="new \Illuminate\View\ComponentAttributeBag($itemComponentData)" />
        </tr>
        </thead>
        <tbody>
        @foreach($elements as $element)
            <tr>{{-- to auto-open add class="parent" --}}
                <td class="control">
                    <i class="fas fa-chevron-right expand"></i>
                    <i class="fas fa-chevron-down restore"></i>
                </td>
                <x-dynamic-component :component="$itemComponent" :$element :attributes="new \Illuminate\View\ComponentAttributeBag($itemComponentData)" />
            </tr>
        @endforeach
        </tbody>
    </table>

    <div class="loading-indicator text-center">
        <p><i class="fa-4x fas fa-spinner fa-pulse"></i></p>
        <p>Liste wird geladen...</p>
    </div>

    <script>
        // Remove stale markup to prevent flashing old table during refresh.
        // This script is executed at page load. Stale markup only exists then when the page was cached.
        document.getElementById('{{$id}}_wrapper')?.remove()
        document.getElementsByClassName('dtfh-floatingparent-head')[0]?.remove()
    </script>
</div>
