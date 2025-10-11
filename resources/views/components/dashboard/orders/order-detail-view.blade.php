@use(App\Enums\OrderStatus)

<x-slot:breadcrumbs>
    <li class="breadcrumb-item"><i class="fa-solid fa-cart-shopping"></i>&nbsp;Bestellungen</li>
    <li class="breadcrumb-item"><a href="{{route('dashboard.orders.list')}}" wire:navigate>Übersicht</a></li>
    <li class="breadcrumb-item">Bestellung #{{$order->id}}</li>
</x-slot:breadcrumbs>

@script
<script>
    // When modal is opened and components are refreshed, modal gets reset but backdrop stays. So, we have to clear backdrop manually.
    clearBackdrop = () => {
        if (document.getElementsByClassName('modal-backdrop').length) {
            // using dispose() instead of hide() so we don't have to wait for backdrop-transition to finish
            bootstrap.Modal.getInstance(document.getElementById('editOrderItem')).dispose()

            document.body.classList.remove('modal-open')
            document.body.removeAttribute('style')
        }
    }

    // conditional confirmation for form submit: only ask for confirmation when by cancelling an order valid invoices will get cancelled automatically
    document.getElementById('orderDetailViewForm').addEventListener('submit', e => {
        if (e.target.dataset.validInvoices !== undefined &&
            $wire.editOrderForm.status === '{{OrderStatus::CANCELLED->value}}' &&
            $wire.editOrderForm.automaticInvoiceUpdates &&
            !confirm('Durch das Stornieren der Bestellung wird auch die Rechnung automatisch storniert.\nSoll diese Bestellung wirklich storniert werden?')
        ) {
            e.preventDefault()
            e.stopImmediatePropagation()
        }
    })
</script>
@endscript

<div x-data="{edit: false, addComment: false}" wire:rendered="clearBackdrop()">
    <form wire:submit="updateOrder" id="orderDetailViewForm" {{$order->hasValidInvoices() ? 'data-valid-invoices' : ''}}>
        <h1 class="mb-4 d-flex justify-content-between flex-wrap gap-3">
            <div>
                Bestellung #{{$order->id}}
                <x-dashboard.orders.status-badge :status="$order->status" class="fs-6" />
            </div>
            @if(!$order->status->orderClosed())
                <div x-show="!edit">
                    <button type="button" class="btn btn-outline-primary" @click="edit=true">
                        <i class="fa-solid fa-pen-to-square"></i>&nbsp;Bearbeiten
                    </button>
                </div>
                <div x-show="edit" x-cloak>
                    <button type="button" wire:click="cancel" @click="edit=false" class="btn btn-secondary">Abbrechen</button>
                    <button type="submit" class="btn btn-primary">Speichern</button>
                </div>
            @endif
        </h1>

        <x-status-message />

        @if($order->status == OrderStatus::COMPLETED)
            <div class="alert alert-secondary d-flex align-items-baseline flex-wrap gap-3">
                <span class="me-auto">Diese Bestellung ist abgeschlossen und kann daher nicht mehr bearbeitet werden.</span>
                <button type="button" class="btn btn-sm btn-outline-primary btn-icon" wire:click="openOrder" wire:confirm="Du bist dabei, eine bereits abgeschlossene Bestellung zu bearbeiten. Um diese daraufhin wieder abschließen zu können, wird es gegebenenfalls erforderlich sein, eine neue Rechnung zu erstellen und zu versenden.">Bestellung wieder öffnen</button>
            </div>
        @elseif($order->status == OrderStatus::CANCELLED)
            <div class="alert alert-secondary d-flex align-items-baseline flex-wrap gap-3">
                <span class="me-auto">Diese Bestellung ist storniert und kann daher nicht mehr bearbeitet werden.</span>
                <div class="d-flex flex-wrap gap-2">
                    <button type="button" class="btn btn-sm btn-outline-primary btn-icon" wire:click="openOrder" wire:confirm="Du bist dabei, eine stornierte Bestellung zu bearbeiten. Um diese daraufhin wieder schließen zu können, wird es gegebenenfalls erforderlich sein, eine neue Rechnung zu erstellen und zu versenden.">Bestellung wieder öffnen</button>
                    @if($order->canBeCancelled())
                        {{-- Make sure cancellation criteria still applies prior to permanently deleting order. (Criteria usually won't change while order is cancelled unless someone tampered with the database.) --}}
                        <button type="button" class="btn btn-sm btn-outline-danger" wire:click="deleteOrder" wire:confirm="Möchtest du diese Bestellung wirklich endgültig löschen? Sie kann danach nicht wiederhergestellt werden."><i class="fa-solid fa-trash-can"></i>&nbsp;Endgültig Löschen</button>
                    @endif
                </div>
            </div>
        @endif

        @if($order->status->orderClosed())
            <div class="row" wire:key="order_closed">
                @include('components.dashboard.orders.order-detail-view.common-information')
            </div>
        @else
            <div class="row" x-show="!edit">
                @include('components.dashboard.orders.order-detail-view.common-information')
            </div>

            <div class="row mb-3" x-show="edit" x-cloak>
                @include('components.dashboard.orders.order-detail-view.edit-form')
            </div>
            <div class="row mb-3 row-gap-2" x-show="edit" x-cloak>
                <div class="col">
                    <button type="button" wire:click="cancel" @click="edit=false" class="btn btn-secondary">Abbrechen</button>
                    <button type="submit" class="btn btn-primary">Speichern</button>
                </div>
                <div class="col-sm col-md-7 text-right">
                    @if($order->canBeCancelled())
                        <button type="button" class="btn btn-danger"
                                wire:click="deleteOrder"
                                wire:confirm="Möchtest du diese Bestellung wirklich endgültig löschen? Sie kann danach nicht wiederhergestellt werden.\nAlternativ kannst du den Status der Bestellung auch auf „Storniert“ setzen.\n\nBestellung endgültig löschen?">
                            <i class="fa-solid fa-trash-can"></i>&nbsp;Endgültig Löschen
                        </button>
                    @else
                        <button type="button" class="btn btn-danger" disabled><i class="fa-solid fa-trash-can"></i>&nbsp;Endgültig Löschen</button>
                        <p class="form-text me-0">Diese Bestellung muss erst storniert werden, bevor sie gelöscht werden kann.</p>
                    @endif
                </div>
            </div>
        @endif
    </form>

    <div class="mb-3 d-flex flex-wrap gap-2">
        <a href="{{route('dashboard.orders.confirmation', $order->id)}}" target="_blank" class="btn btn-sm btn-outline-primary btn-icon">
            <i class="fa-solid fa-file-pdf fa-fw"></i> Bestellübersicht öffnen
        </a>

        @if($order->status != OrderStatus::CANCELLED)
            <button class="btn btn-sm btn-outline-primary btn-icon" wire:click="sendOrderSummary" wire:confirm="Möchtest du eine aktuelle Bestellübersicht an {{htmlspecialchars($order->customer->email)}} senden?">
                <i class="fa-regular fa-envelope fa-fw"></i> Bestellübersicht per E-Mail senden
            </button>
        @endif

        @if($order->orderItems->isNotEmpty())
            <a href="{{route('dashboard.orders.contract', $order->id)}}" target="_blank" class="btn btn-sm btn-outline-primary btn-icon">
                <i class="fa-solid fa-file-pdf fa-fw"></i> Mietvertragsformular öffnen
            </a>
        @endif
    </div>

    <div class="row mb-1">
        <div class="col">
            <h5>Rechnung</h5>
            @php($notificationsMissing = $order->notificationsMissing())

            @if($order->invoice_required)
                <div class="alert alert-warning p-2 d-flex align-items-baseline flex-wrap gap-3">
                    {{-- special handling for order without items (creation of new invoice does not make sense but old invoice must still be cancelled), see Rechnungsverwaltung.md for details --}}
                    @if($order->orderItems->isEmpty() && ($firstInvoice = $order->invoices->first()) && !$firstInvoice->cancelled)
                        <span class="me-auto">Die Rechnung {{$firstInvoice->name}} ist veraltet. Sie muss storniert werden.</span>
                        <button class="btn btn-sm btn-primary btn-icon"
                                wire:click="cancelAllInvoices"
                                wire:confirm="Möchtest du die Rechnung wirklich stornieren?">
                            <i class="fa-solid fa-ban"></i> Rechnung stornieren
                        </button>
                    @else
                        <span class="me-auto">Diese Bestellung verfügt über keine aktuelle Rechnung.</span>
                        <button class="btn btn-sm btn-primary btn-icon"
                                wire:click="createInvoice"
                                wire:confirm="Möchtest du wirklich eine Rechnung zu dieser Bestellung erzeugen? Rechnungen können nicht bearbeitet oder gelöscht werden und sollten erst erzeugt werden, wenn davon auszugehen ist, dass sich die Bestellung nicht mehr ändert.">
                            <i class="fa-solid fa-receipt"></i> Rechnung erzeugen
                        </button>
                    @endif
                </div>
            @elseif($notificationsMissing)
                <div class="alert alert-warning p-2 d-flex align-items-baseline flex-wrap gap-3">
                    <span class="me-auto">Der Versand zu mindestens einer Rechnung oder Stornierung ist noch ausstehend.</span>
                    <button class="btn btn-sm btn-primary btn-icon"
                            wire:click="sendNotification"
                            wire:confirm="Möchtest du die ausstehende Rechnung und / oder Stornierung wirklich per E-Mail an {{htmlspecialchars($order->customer->email)}} senden?">
                        <i class="fa-regular fa-envelope"></i> Rechnung per E-Mail senden
                    </button>
                </div>
            @endif

            @php($currentInvoice = $order->currentInvoice)
            @if(
                $order->status != OrderStatus::CANCELLED && // cancelled orders do not allow any interaction with invoices
                ($currentInvoice || $order->status != OrderStatus::COMPLETED && $order->orderItems->isNotEmpty() || $notificationsMissing) // this part is only to prevent an empty <div> element from being displayed and for displaying an information in the elseif part
            )
                <div class="mb-2 d-flex gap-2 flex-wrap">
                    @if($currentInvoice)
                        <button class="btn btn-sm btn-outline-primary btn-icon"
                                wire:click="sendNotification"
                                wire:confirm="Möchtest du die aktuelle Rechnung ({{$currentInvoice->name}}) über @money($currentInvoice->total_amount) {{$notificationsMissing ? 'sowie ggf. weitere ausstehende Rechnungen ' : ($currentInvoice->notified ? 'erneut ' : '')}}an {{htmlspecialchars($order->customer->email)}} senden?">
                            <i class="fa-regular fa-envelope fa-fw"></i> Rechnung per E-Mail senden
                        </button>
                    @else
                        @if($order->status != OrderStatus::COMPLETED && $order->orderItems->isNotEmpty())
                            <a href="{{route('dashboard.orders.invoicePreview', $order->id)}}" target="_blank" class="btn btn-sm btn-outline-primary btn-icon">
                                <i class="fa-solid fa-eye fa-fw"></i> Rechnungsvorschau
                            </a>
                            <button class="btn btn-sm btn-outline-primary btn-icon"
                                    wire:click="createInvoice"
                                    wire:confirm="Möchtest du wirklich eine Rechnung zu dieser Bestellung erzeugen? Rechnungen können nicht bearbeitet oder gelöscht werden und sollten erst erzeugt werden, wenn davon auszugehen ist, dass sich die Bestellung nicht mehr ändert.">
                                <i class="fa-solid fa-receipt fa-fw"></i> Rechnung erzeugen
                            </button>
                        @endif

                        @if($notificationsMissing)
                            <button class="btn btn-sm btn-outline-primary btn-icon"
                                    wire:click="sendNotification"
                                    wire:confirm="{{$order->invoice_required ? 'Warnung: Diese Bestellung verfügt noch nicht über eine aktuelle Rechnung. Wenn du jetzt die ausstehende Rechnungsstornierung versendest und später eine aktuelle Rechnung an den Kunden schickst, bekommt er dadurch insgesamt zwei Benachrichtigungs-E-Mails.\nDu kannst stattdessen erst eine aktuelle Rechnung generieren und direkt beide Dokumente in einer Benachrichtigung versenden.\n\n' : ''}}Möchtest du wirklich die ausstehende Rechnungsstornierung an {{htmlspecialchars($order->customer->email)}} senden?">
                                <i class="fa-regular fa-envelope fa-fw"></i> Ausstehende Rechnungsstornierung per E-Mail senden
                            </button>
                        @endif
                    @endif
                </div>
            @elseif($order->invoices->isEmpty())
                <p>Diese Bestellung verfügt über keine Rechnungen.</p>
            @endif

            @if($order->invoices->isNotEmpty())
                <ul id="invoice-list">
                    @php($orderInvoiceHash = $order->calculateInvoiceHash())
                    @foreach($order->invoices as $invoice)
                        <li class="pb-1 break-inside-avoid">
                            <div class="d-flex align-items-baseline column-gap-3 row-gap-2 col-auto flex-wrap flex-sm-nowrap">
                                <div>
                                    <p class="mb-0 pt-1">
                                        <span @if($currentInvoice == $invoice)class="fw-bold"@endif>
                                            Rechnung <a href="{{route('dashboard.orders.invoice', [$order->id, $invoice->id])}}" target="_blank" class="font-monospace">{{$invoice->name}}</a>
                                        </span>
                                        vom {{$invoice->created_at}}; @money($invoice->total_amount)

                                        @if($invoice->cancelled)
                                        (<a href="{{route('dashboard.orders.invoiceCancellation', [$order->id, $invoice->id])}}" target="_blank">Stornierung</a>)
                                        @endif
                                    </p>
                                    @if($invoice->cancelled)
                                        <span class="badge text-danger-emphasis bg-danger-subtle fw-semibold mt-1">storniert</span>
                                    @elseif($invoice->content_hash == $orderInvoiceHash)
                                        <span class="badge text-success-emphasis bg-success-subtle mt-1">aktuell</span>
                                        @if($invoice->total_amount != 0 && !$invoice->notified)
                                            <span class="badge text-bg-warning text-wrap mt-1">Versand ausstehend</span>
                                        @endif
                                    @else
                                        <span class="badge text-secondary-emphasis bg-secondary-subtle fw-semibold mt-1">veraltet</span>
                                    @endif

                                    @if($invoice->notified)
                                        <span class="badge text-info-emphasis bg-info-subtle fw-semibold mt-1">versendet</span>

                                        @if($invoice->cancelled && !$invoice->cancellation_notified)
                                            <span class="badge text-bg-warning text-wrap mt-1">Storno-Benachrichtigung ausstehend</span>
                                        @endif
                                    @endif
                                </div>

                                @if(!$invoice->cancelled && !$order->status->orderClosed())
                                    <button class="btn btn-sm btn-outline-danger text-nowrap"
                                            wire:click="cancelInvoice({{$invoice->id}})"
                                            wire:confirm="Möchtest du die Rechnung {{$invoice->name}} wirklich stornieren?">
                                        <i class="fa-solid fa-ban"></i> Stornieren
                                    </button>
                                @endif
                            </div>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>

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
                <div class="mt-2 d-flex flex-wrap gap-2">
                    <button type="button" class="btn btn-danger" @click="addComment=false" wire:click="cancelComment">Abbrechen</button>
                    <button type="submit" class="btn btn-primary">Kommentar hinzufügen</button>
                </div>
            </form>
            <button class="btn btn-outline-primary btn-sm btn-icon" @click="addComment=true" x-show="!addComment">
                <i class="fa-solid fa-pencil"></i>
                Kommentar hinzufügen
            </button>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col">
            <h5>Artikel</h5>
            @if($order->orderItems->isNotEmpty())
                @if(!$order->status->orderClosed())
                    <div class="d-flex justify-content-between flex-wrap gap-2">
                        <button type="button" class="btn btn-outline-primary btn-sm btn-icon"
                                data-bs-toggle="modal" data-bs-target="#editOrderItem" data-bs-order-item="">
                            <i class="fa-solid fa-plus fa-fw"></i> Artikel hinzufügen
                        </button>
                        <button type="button" class="btn btn-outline-primary btn-sm btn-icon" wire:click="recalculateItemPrices"
                                wire:confirm="Dadurch werden alle Preise für die Artikel neu berechnet und ein ggf. gewährter Artikelrabatt zurückgesetzt. Fortfahren?">
                            <i class="fa-solid fa-calculator fa-fw"></i> Alle Preise erneut berechnen
                        </button>
                    </div>
                @endif

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
