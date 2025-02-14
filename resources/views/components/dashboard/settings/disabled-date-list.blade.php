<x-slot:breadcrumbs>
    <li class="breadcrumb-item"><i class="fa-solid fa-gear"></i>&nbsp;Einstellungen</li>
    <li class="breadcrumb-item"><i class="fa-regular fa-calendar-xmark"></i>&nbsp;Deaktivierte Zeiträume</li>
</x-slot:breadcrumbs>

<div>
    <h1 class="mb-4">Einstellungen</h1>

    <x-status-message />

    <h3>Deaktivierte Zeiträume</h3>

    <p>
        Hierdurch können bestimmte Zeiträume vom Buchungszeitraum der Artikel ausgeschlossen werden.
        Es ist zwar möglich, während eines deaktivierten Zeitraums eine Buchung vorzunehmen, allerdings kann sich der Buchungszeitraum nicht mit einem deaktivierten Zeitraum überschneiden.
    </p>

    @if($this->disabledDates->isNotEmpty())
        <div class="row">
            <div class="col-auto table-responsive">
                <table class="table table-hover align-middle table-borderless">
                    @foreach($this->disabledDates as $disabledDate)
                        <tr>
                            <td>
                                <ul class="m-0 ps-4">
                                    <li>
                                        <span class="text-nowrap">{{$disabledDate->start}}</span> &ndash; <span>{{$disabledDate->end}}</span>
                                        @if($disabledDate->comment)
                                            <span class="">({{$disabledDate->comment}})</span>
                                        @endif
                                        @if(!$disabledDate->active)
                                            <span class="badge text-bg-danger fw-normal d-sm-none">Nicht aktiv!</span>
                                            <span class="badge text-bg-danger d-none d-sm-inline-block">Nicht aktiv!</span>
                                        @endif
                                    </li>
                                </ul>
                            </td>
                            <td class="text-nowrap">
                                <a class="btn btn-outline-primary btn-sm px-3 px-sm-2 me-2" href="{{route('dashboard.settings.disabledDates.edit', $disabledDate->id)}}" wire:navigate>
                                    <i class="fa-solid fa-pen-to-square"></i>
                                    <span class="d-none d-sm-inline">Bearbeiten</span>
                                </a>
                                <button class="btn btn-outline-danger btn-sm px-3 px-sm-2"
                                        wire:click="removeDisabledDate({{$disabledDate->id}})"
                                        wire:confirm="Soll der Zeitraum vom {{$disabledDate->start}} bis zum {{$disabledDate->end}} {{$disabledDate->comment ? '(' . htmlspecialchars($disabledDate->comment) . ')' : ''}} wirklich gelöscht werden?">
                                    <i class="fa-solid fa-trash-can"></i>
                                    <span class="d-none d-sm-inline">Löschen</span>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>
    @else
        <p>Derzeit sind keine deaktivierten Zeiträume hinterlegt.</p>
    @endif

    <a href="{{route('dashboard.settings.disabledDates.create')}}" wire:navigate class="btn btn-primary"><i class="fa-solid fa-plus"></i>&nbsp;Deaktivierten Zeitraum hinzufügen</a>

</div>
