<x-slot:breadcrumbs>
    <li class="breadcrumb-item"><i class="fa-solid fa-cart-shopping"></i>&nbsp;Bestellungen</li>
    <li class="breadcrumb-item"><a href="{{route('dashboard.orders.list')}}" wire:navigate>Übersicht</a></li>
    <li class="breadcrumb-item">Bestellung #{{$order->id}}</li>
</x-slot:breadcrumbs>

<div x-data="{edit: false}">
    <form wire:submit="updateOrder">
        <h1 class="mb-4 d-flex justify-content-between flex-wrap gap-3">
            <div>
                Bestellung #{{$order->id}}
                <x-dashboard.orders.status-badge :status="$order->status" class="fs-6" />
            </div>
            <div x-show="!edit">
                <button type="button" class="btn btn-outline-primary" @click="edit=true">
                    <i class="fa-solid fa-pen-to-square"></i>&nbsp;Bearbeiten
                </button>
            </div>
            <div x-show="edit" x-cloak>
                <button type="button" wire:click="cancel()" @click="edit=false" class="btn btn-secondary">Abbrechen</button>
                <button type="submit" class="btn btn-primary">Speichern</button>
            </div>
        </h1>

        <x-status-message />

        <div class="row" x-show="!edit">
            @include('components.dashboard.orders.order-detail-view.common-information')
        </div>

        <div class="row mb-3" x-show="edit" x-cloak>
            @include('components.dashboard.orders.order-detail-view.edit-form')
        </div>
        <div class="row mb-3" x-show="edit" x-cloak>
            <div class="col">
                <button type="button" wire:click="cancel()" @click="edit=false" class="btn btn-secondary">Abbrechen</button>
                <button type="submit" class="btn btn-primary">Speichern</button>
            </div>
        </div>
    </form>

    @if($order->comments->isNotEmpty())
        <div class="row mb-3">
            <div class="col-auto">
                <h5>Kommentare</h5>
                @foreach($order->comments as $comment)
                    <div class="alert alert-secondary bg-gradient px-2 py-1 mb-2">
                        <p class="small fw-semibold mb-1 text-italic text-right text-muted">
                            {{$comment->user?->name ?? 'Gelöschter Benutzer'}}
                            @if($comment->created_at)
                                am&nbsp;{{$comment->created_at}}
                                um&nbsp;{{$comment->created_at->format('H:i:s')}}&nbsp;Uhr
                            @endif
                        </p>
                        <p class="m-0">
                            {{$comment->comment}}
                        </p>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
    <div class="row mb-3">
        <div class="col">
            <h5>Artikel</h5>
            @if($order->orderItems->isNotEmpty())
                <button type="button" class="btn btn-outline-primary" wire:click="recalculateItemPrices" wire:confirm="Dadurch werden alle Preise für die Artikel neu berechnet und ein ggf. gewährter Artikelrabatt zurückgesetzt. Fortfahren?">
                    <i class="fa-solid fa-calculator"></i>&nbsp;Alle Preise erneut berechnen
                </button>

                <livewire:data-table
                        class="child-responsive"
                        :elements="$order->orderItems"
                        :element-attributes="['data-hide-empty-children' => true]"
                        item-component="dashboard.orders.order-detail-view.item-entry"
                        :item-component-data="['order' => $order]"
                />
            @else
                <p>Diese Bestellung enthält noch keine Artikel.</p>
            @endif
        </div>
    </div>
</div>
