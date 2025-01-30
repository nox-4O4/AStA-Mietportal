@assets
<link rel="stylesheet" href="https://cdn.datatables.net/v/bs5/jq-3.7.0/dt-2.2.1/r-3.0.3/datatables.min.css" integrity="sha384-0bu26ne7NEvEquG1f9uVHu4PPAgluEU2eNxlStLOQVlJncoJ3GcgR+IEcwzPRFb5" crossorigin="anonymous">
<script src="https://cdn.datatables.net/v/bs5/jq-3.7.0/dt-2.2.1/r-3.0.3/datatables.min.js" integrity="sha384-zptpTncqGLFjGATdLlLQcNmlcw73kGfOOIFLdsL8+wmpx8BdIfNBY4yoWxkdUy51" crossorigin="anonymous"></script>
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
           class="table table-hover responsive d-none fancy-datatable {{$class}}"
           @if($elements->count() < 10)data-paging="false" @endif
            {!! collect($elementAttributes)->map(fn($value, $name) => $name . '="' . htmlentities($value) . '"')->join(' ') !!}>
        <thead>
        <tr>
            <th data-orderable="false" data-searchable="false" class="control">&nbsp;</th>
            <x-dynamic-component :component="$itemComponent" />
        </tr>
        </thead>
        <tbody>
        @foreach($elements as $element)
            <tr>
                <td class="control">
                    <i class="fas fa-chevron-right expand"></i>
                    <i class="fas fa-chevron-down restore"></i>
                </td>
                <x-dynamic-component :component="$itemComponent" :$element />
            </tr>
        @endforeach
        </tbody>
    </table>

    <script>
        // Remove stale markup to prevent flashing old table during refresh.
        // This script is executed at page load. Stale markup only exists then when the page was cached.
        document.getElementById('{{$id}}_wrapper')?.remove()
    </script>
</div>
