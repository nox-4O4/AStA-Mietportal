<x-slot:breadcrumbs>
    <li class="breadcrumb-item"><i class="fa-solid fa-cart-shopping"></i>&nbsp;Bestellungen</li>
    <li class="breadcrumb-item"><a href="{{route('dashboard.orders.list')}}" wire:navigate>Übersicht</a></li>
    <li class="breadcrumb-item">Bestellung #{{$order->id}}</li>
</x-slot:breadcrumbs>

<div x-data="{edit: false, addComment: false}">
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

    <div class="row mb-1">
        <div class="col-auto">
            <h5>Kommentare</h5>
            @if($order->comments->isNotEmpty())
                @if(!$allComments)
                    @php($limitComments = $order->comments->count() > 4 ? 3 : 4)
                    @php($hidden = $order->comments->count() - $limitComments)
                    @if($hidden > 0)
                        <button class="btn btn-link px-0 pt-0" wire:click="showAllComments">
                            <i class="fas fa-spinner fa-pulse me-1 fa-fw" wire:loading wire:target="showAllComments"></i><i class="fa-solid fa-caret-down me-1 fa-fw" wire:loading.remove wire:target="showAllComments"></i>{{$hidden}} ältere Kommentare anzeigen...
                        </button>
                    @endif
                @endif
                @foreach($order->recentComments($limitComments ?? null) as $comment)
                    <div class="alert alert-secondary bg-gradient py-1 px-2 mb-2">
                        <div class="d-flex align-items-start gap-3">
                            @if($comment->user->id == auth()->user()->id)
                                <button class="btn btn-sm btn-outline-danger text-nowrap my-1" title="Kommentar entfernen" wire:confirm="Möchtest du diesen Kommentar wirklich entfernen?" wire:click="deleteComment({{$comment->id}})">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                            @endif
                            <p class="small fw-semibold mb-1 text-italic text-right text-muted ms-auto">
                                {{$comment->user?->name ?? 'Gelöschter Benutzer'}}
                                @if($comment->created_at)
                                    am&nbsp;{{$comment->created_at}}
                                    um&nbsp;{{$comment->created_at->format('H:i:s')}}&nbsp;Uhr
                                @endif
                            </p>
                        </div>
                        <p class="m-0">
                            {{$comment->comment}}
                        </p>
                    </div>
                @endforeach
            @else
                <p class="mb-0">Noch keine Kommentare.</p>
            @endif
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-12 col-md-6">
            <form x-cloak x-show="addComment" wire:submit="addComment">
                <div class="autogrow-textarea @error('newComment')is-invalid @enderror" data-replicated-value="{{$newComment}}">
                    <textarea onInput="this.parentNode.dataset.replicatedValue=this.value" wire:model="newComment" rows="3" id="newComment" placeholder="Kommentar eingeben..." class="form-control @error('newComment')is-invalid @enderror"></textarea>
                </div>
                @error('newComment')
                <div class="invalid-feedback">{{$message}}</div>
                @enderror
                <div class="mt-2">
                    <button type="button" class="btn btn-danger" @click="addComment=false" wire:click="cancelComment">Abbrechen</button>
                    <button type="submit" class="btn btn-primary">Kommentar hinzufügen</button>
                </div>
            </form>
            <button class="btn btn-outline-primary btn-sm" @click="addComment=true" x-show="!addComment">
                <i class="fa-solid fa-pencil"></i>
                Kommentar hinzufügen
            </button>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col">
            <h5>Artikel</h5>
            @if($order->orderItems->isNotEmpty())
                <div class="d-flex justify-content-between flex-wrap gap-2">
                    <button type="button" class="btn btn-outline-primary btn-sm"
                            data-bs-toggle="modal" data-bs-target="#editOrderItem" data-bs-order-item="">
                        <i class="fa-solid fa-plus"></i>&nbsp;Artikel hinzufügen
                    </button>
                    <button type="button" class="btn btn-outline-primary btn-sm" wire:click="recalculateItemPrices" wire:confirm="Dadurch werden alle Preise für die Artikel neu berechnet und ein ggf. gewährter Artikelrabatt zurückgesetzt. Fortfahren?">
                        <i class="fa-solid fa-calculator"></i>&nbsp;Alle Preise erneut berechnen
                    </button>
                </div>

                <livewire:data-table
                        class="child-responsive"
                        wire:key="{{\App\Util\Helper::HashCollection($order->orderItems)}}"
                        :elements="$order->orderItems"
                        :element-attributes="['data-hide-empty-children' => true]"
                        item-component="dashboard.orders.order-detail-view.item-entry"
                        :item-component-data="['order' => $order]"
                />
            @else
                <p>Diese Bestellung enthält noch keine Artikel.</p>
                <button type="button" class="btn btn-primary"
                        data-bs-toggle="modal" data-bs-target="#editOrderItem" data-bs-order-item="">
                    <i class="fa-solid fa-plus"></i>&nbsp;Artikel hinzufügen
                </button>
            @endif
        </div>
    </div>

    <div>
        <div class="modal fade" id="editOrderItem" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-fullscreen-sm-down">
                <div class="modal-content overflow-auto">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5">Bestellung #{{$order->id}} &ndash; Artikel <span id="modalAction">bearbeiten</span></h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Schließen"></button>
                    </div>
                    <livewire:dashboard.orders.order-item-editing :order="$order" />
                </div>
            </div>
        </div>
    </div>

</div>
