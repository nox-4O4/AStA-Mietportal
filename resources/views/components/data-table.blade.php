@assets
<link rel="stylesheet" href="/static/datatables.css" integrity="sha384-faFFLRSCeSKhXw5yqEuilNYybh/M8lFS9UrsllicczmVEHePhIsWBX/EN39tpy+E" crossorigin="anonymous">
<script src="/static/datatables.js" integrity="sha384-GxaTBPyY1o9/G9GlhDCEwMtbkd6TU/o9jxyeUNo4wJ9GXf42Iga/QGL41h4q7LdK" crossorigin="anonymous"></script>
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
