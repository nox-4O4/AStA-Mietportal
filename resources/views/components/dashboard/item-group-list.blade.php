<x-slot:breadcrumbs>
    <li class="breadcrumb-item"><i class="fa-solid fa-object-group"></i>&nbsp;Artikelgruppen</li>
    <li class="breadcrumb-item">Übersicht</li>
</x-slot:breadcrumbs>

<div>
    <h1 class="mb-4">Artikelgruppen</h1>

    <x-status-message />

    <p>Artikelgruppen ermöglichen es, einzelne Artikel auf der Übersichtsseite zusammenzufassen, sodass dort nur der Gruppenname dargestellt wird.
       Die individuellen Artikel lassen sich dann auf der Artikelseite in einem Auswahlfeld selektieren.</p>

    <p>Das kann die Übersichtlichkeit erhöhen, wenn sich Artikel beispielsweise nur in einem Merkmal unterschieden, etwa Kabel mit unterschiedlicher Länge.</p>

    <a href="{{route('dashboard.groups.create')}}" wire:navigate class="btn btn-primary mb-3"><i class="fa-solid fa-plus"></i>&nbsp;Neue Gruppe anlegen</a>

    <livewire:data-table :elements="$this->groups" item-component="dashboard.item-group-list-entry" />
</div>
