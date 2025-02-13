<x-slot:breadcrumbs>
    <li class="breadcrumb-item"><i class="fa-solid fa-gear"></i>&nbsp;Einstellungen</li>
    <li class="breadcrumb-item"><i class="fa-regular fa-newspaper"></i>&nbsp;Inhalte</li>
</x-slot:breadcrumbs>

<div>
    <h1 class="mb-4">Einstellungen</h1>

    <x-status-message />

    <h3>Inhalte</h3>

    <p>
        Einige der Texte im Shop können hier angepasst werden.
    </p>

    @if($this->contents->isNotEmpty())
        @foreach($this->contents as $content)
            <ul class="m-0 ps-4">
                <li>
                    <p class="mb-0">
                        <span>{{$content->name}}</span>
                        <a class="btn btn-outline-primary btn-sm align-baseline" href="{{route('dashboard.settings.contents.edit', $content->id)}}" wire:navigate>
                            <i class="fa-solid fa-pen-to-square"></i>&nbsp;Bearbeiten
                        </a>
                    </p>
                    <p class="text-muted small">
                        {{$content->description}}
                    </p>
                </li>
            </ul>
        @endforeach
    @else
        <p>Derzeit sind noch keine Inhalte vohanden.</p>
    @endif
</div>
