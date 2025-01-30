export default id => {
    const additionalHeaderOffset = parseInt(window.getComputedStyle(document.body).getPropertyValue('--fixed-table-header-offset'), 10)
    const tableElement = $('#' + id)
    tableElement.children('tbody').on('click', 'tr[tabindex] a', e => e.stopImmediatePropagation()); // prevent row from toggling when clicking on link

    const table = tableElement.dataTable({
        initComplete: () => document.getElementById(id).classList.remove('d-none'), // show table only after initialisation to prevent flashing unstyled table
        language: {url: 'https://cdn.datatables.net/plug-ins/2.2.1/i18n/de-DE.json'},
        pageLength: 50,
        lengthMenu: [[10, 25, 50, 100, 200, -1], [10, 25, 50, 100, 200, 'Alle']],
        order: [],
        stateSave: true,
        stateDuration: 0, // store indefinitely
        stateSaveParams: function (settings, data) {
            // only store page length
            data.order = tableElement.data('order') ?? [];
            data.search.search = '';
            data.start = 0;
        },
        fixedHeader: {
            headerOffset: document.getElementsByClassName('topbar')[0]?.offsetHeight + additionalHeaderOffset
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

                        const title = document.createElement('div')
                        title.innerHTML = col.title

                        return title.innerText.trim() === ''
                            ? `<tr data-dt-row="${col.rowIndex}" data-dt-column="${col.columnIndex}">
                                <td colspan="2" class="px-4 py-2 text-ellipsis">${col.data}</td>
                            </tr>`
                            : `<tr data-dt-row="${col.rowIndex}" data-dt-column="${col.columnIndex}">
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
    });

    table.on('keydown', 'tbody > tr[tabindex]', function (e) {
        if (e.target.nodeName === 'TR' && (e.which === 13 || e.which === 32)) {
            $(this).trigger('click');
            e.preventDefault();
        }
    });

    table.api().on('responsive-resize draw', function () { // need draw event when changing pages (page event is too early)
        $(this).find('tbody tr').attr({'tabindex': $('.control').css('display') === 'none' ? -1 : 0});
    });

    // need to manually add scroll event on dashboard container as fixedHeader only listens on window scroll event
    document.getElementById('dashboard-content-container').addEventListener('scroll', () => table.api().fixedHeader.adjust())

    // adjust fixedHeader offset for when header size changes (e.g. due to wrapping breadcrumbs)
    new ResizeObserver(([entry]) => table.api().fixedHeader.headerOffset(entry.target.offsetHeight + additionalHeaderOffset)).observe(document.querySelector('.topbar'))
}
