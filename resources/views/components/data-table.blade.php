@assets
<link rel="stylesheet" href="https://cdn.datatables.net/v/bs5/jq-3.7.0/dt-2.2.1/r-3.0.3/datatables.min.css" integrity="sha384-0bu26ne7NEvEquG1f9uVHu4PPAgluEU2eNxlStLOQVlJncoJ3GcgR+IEcwzPRFb5" crossorigin="anonymous">
<script src="https://cdn.datatables.net/v/bs5/jq-3.7.0/dt-2.2.1/r-3.0.3/datatables.min.js" integrity="sha384-zptpTncqGLFjGATdLlLQcNmlcw73kGfOOIFLdsL8+wmpx8BdIfNBY4yoWxkdUy51" crossorigin="anonymous"></script>
@endassets

<table id="{{$id}}"
       class="table table-hover responsive fancy-datatable d-none {{$class}}"
       @if($elements->count() < 10)data-paging="false" @endif
        {{$attributes}}
>
    <thead>
    <tr>
        <th data-orderable="false" data-searchable="false" class="control">&nbsp;</th>
        <x-dynamic-component :component="$itemComponent" />
    </tr>
    </thead>
    <tbody>
    @foreach($elements as $element)
        <tr @if($loop->odd)class="odd"@endif>
            <td class="control">
                <i class="fas fa-chevron-right expand"></i>
                <i class="fas fa-chevron-down restore"></i>
            </td>
            <x-dynamic-component :component="$itemComponent" :$element />
        </tr>
    @endforeach
    </tbody>
</table>

{{-- Workaround for livewire bug: https://github.com/livewire/livewire/discussions/6722
           When navigating back, table has lost internal state, stale markup remains and datatables
           script gets re-executed on stale (and already modified) markup, leading to errors.
           Workaround:
            - Check if wrapper element already exists on load and skip (failing) datatable initialization.
            - Re-render page when popstate event occurred (happens on navigating back), see DOMContentLoaded event handler below.
           This re-renders the complete page leading to a working table (also containing refreshed information). --}}
<script>
    document.addEventListener("DOMContentLoaded", () => {
        window.addEventListener('popstate', (event) => {
            if (event.state) {
                Livewire.navigate(window.location.pathname); // forces re-rendering of complete page
            }
        });
    }, {once: true});
</script>

@script
<script>
    const existingElement = document.getElementById('{{$id}}_wrapper');
    if (existingElement !== null) {
        existingElement.remove(); // removing element prevents stale table from flashing
    } else {
        const element = $('#{{$id}}')
        element.children('tbody').on('click', 'tr[tabindex] a', e => e.stopImmediatePropagation()); // prevent row from toggling when clicking on link
        element.dataTable({
            initComplete: () => document.getElementById('{{$id}}').classList.remove('d-none'), // show table only after initialisation to prevent flashing unstyled table
            language: {url: 'https://cdn.datatables.net/plug-ins/2.2.1/i18n/de-DE.json'},
            pageLength: 50,
            lengthMenu: [[10, 25, 50, 100, 200, -1], [10, 25, 50, 100, 200, 'Alle']],
            order: [],
            stateSave: true,
            stateDuration: 0, // store indefinitely
            stateSaveParams: function (settings, data) {
                // only store page length
                console.log(settings,data)
                data.order = [];
                data.search.search = '';
            },
            responsive: {
                details: {
                    type: 'column',
                    target: 'tr',
                    renderer: (api, rowIdx, columns) => {
                        if (!columns.some(col => col.hidden))
                            return false;

                        const content = $.map(columns, (col, i) => {
                            if (!col.hidden)
                                return '';

                            return `<tr data-dt-row="${col.rowIndex}" data-dt-column="${col.columnIndex}">
                                        <td class="ps-4 py-2 pe-1 w-0 text-nowrap">${col.title.trim()}:</td>
                                        <td class="text-ellipsis max-w-0">${col.data}</td>
                                    </tr>`;
                        }).join('');

                        return `<div class="slider">
                                    <table class="w-100">${content}</table>
                                </div>`;
                    },
                    display: function (row, update, render) {
                        if (update && $(row.node()).hasClass('parent') || !update && !row.child.isShown()) { // show due to update or for the first time
                            row.child(render(), 'child p-0').show();

                            if (update)
                                $('div.slider', row.child()).show(); // was already slid down
                            else {
                                $('div.slider', row.child()).slideDown(100);
                                $(row.node()).addClass('parent');
                            }

                            return true;
                        } else if (!update && row.child.isShown()) { // hide
                            $('div.slider', row.child()).slideUp(100, function () {
                                row.child(false);
                                $(row.node()).removeClass('parent');
                            });

                            return false;
                        }
                    }
                }
            }
        }).on('keydown', 'tbody > tr[tabindex]', function (e) {
            if (e.target.nodeName === 'TR' && (e.which === 13 || e.which === 32))
                $(this).trigger('click');
        }).api().on('responsive-resize', function () {
            $(this).find('tbody tr').attr({'tabindex': $('.control').css('display') === 'none' ? -1 : 0});
        });
    }
</script>
@endscript
