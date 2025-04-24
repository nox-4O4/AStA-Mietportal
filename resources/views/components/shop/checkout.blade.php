<x-slot:breadcrumbs>
    <li class="breadcrumb-item text-center">
        <a href="{{route('shop.cart')}}" wire:navigate class="link-secondary">
            <i class="fa-solid fa-cart-shopping me-1"></i>Warenkorb
        </a>
    </li>
    <li class="breadcrumb-item text-center fw-semibold"><i class="fa-solid fa-table-list me-1"></i><span class="text-nowrap">Daten angeben</span></li>
    <li class="breadcrumb-item text-center text-muted"><i class="fa-regular fa-square-check me-1"></i>Bestätigen</li>
</x-slot:breadcrumbs>

<div>
    <h1 class="mb-3">
        <span class="d-none d-sm-inline">Check-out:</span>
        Erforderliche Daten
    </h1>

    <x-status-message />

    @if($this->items)
        <div x-data="{prefill: $persist($wire.entangle('prefill').live).as('checkout-prefill')}"></div>

        <form wire:submit="storeData">
            <div class="row mb-3">
                <label for="forename" class="col-sm-3 col-lg-2 col-form-label">Vorname</label>
                <div class="col">
                    <input class="form-control @error('forename')is-invalid @enderror" id="forename" wire:model.live="forename" required>
                    @error('forename')
                    <div class="invalid-feedback">{{$message}}</div>
                    @enderror
                </div>
            </div>
            <div class="row mb-3">
                <label for="surname" class="col-sm-3 col-lg-2 col-form-label">Nachname</label>
                <div class="col">
                    <input class="form-control @error('surname')is-invalid @enderror" id="surname" wire:model="surname" required>
                    @error('surname')
                    <div class="invalid-feedback">{{$message}}</div>
                    @enderror
                </div>
            </div>
            <div class="row mb-3">
                <label for="email" class="col-sm-3 col-lg-2 col-form-label">E-Mail-Adresse</label>
                <div class="col">
                    <input type="email" class="form-control @error('email')is-invalid @enderror" id="email" wire:model="email" required>
                    @error('email')
                    <div class="invalid-feedback">{{$message}}</div>
                    @enderror
                </div>
            </div>
            <div class="row mb-3">
                <label for="mobile" class="col-sm-3 col-lg-2 col-form-label">Handynummer</label>
                <div class="col">
                    <input type="tel" class="form-control @error('mobile')is-invalid @enderror" id="mobile" wire:model="mobile">
                    @error('mobile')
                    <div class="invalid-feedback">{{$message}}</div>
                    @enderror
                    <div class="form-text">Optional. Wird nur bei Bedarf zur einfacheren Terminabsprache genutzt.</div>
                </div>
            </div>

            <fieldset class="row mb-3">
                <legend class="col-sm-3 col-lg-2 col-form-label pt-0">Die Mietung ist</legend>
                <div class="col">
                    <div class="form-check">
                        <input class="form-check-input @error('rentalType')is-invalid @enderror" type="radio" name="rentalType" value="personal" id="type_personal" wire:model="rentalType" required>
                        <label class="form-check-label" for="type_personal">für mich persönlich</label>
                        <div class="form-text mt-0 mb-1">Eine Vermietung ist nur möglich, wenn du in einer Hochschule oder Universität in Karlsruhe immatrikuliert bist.</div>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input @error('rentalType')is-invalid @enderror" type="radio" name="rentalType" value="organisation" id="type_organisation" wire:model="rentalType" required>
                        <label class="form-check-label" for="type_organisation">für einen studentischen Verein oder eine studentische Institution</label>
                        <div class="form-text mt-0 mb-1">Eine Vermietung ist nur nach manueller Prüfung möglich.</div>
                    </div>
                    @error('rentalType')
                    <div class="invalid-feedback d-block">{{$message}}</div>
                    @enderror
                </div>
            </fieldset>

            <fieldset class="row mb-3" wire:show="rentalType == 'personal'" wire:cloak>
                <legend class="col-sm-3 col-lg-2 col-form-label pt-0">Ich studiere</legend>
                <div class="col">
                    <div class="form-check">
                        <input class="form-check-input @error('studying')is-invalid @enderror" type="radio" name="studying" value="hka" id="studying_hka" wire:model="studying" :required="$wire.rentalType == 'personal'">
                        <label class="form-check-label" for="studying_hka">an der HKA</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input @error('studying')is-invalid @enderror" type="radio" name="studying" value="other" id="studying_other" wire:model="studying" :required="$wire.rentalType == 'personal'">
                        <label class="form-check-label" for="studying_other">an einer anderen Karlsruher Universität oder Hochschule</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input @error('studying')is-invalid @enderror" type="radio" name="studying" value="none" id="studying_none" wire:model="studying" :required="$wire.rentalType == 'personal'">
                        <label class="form-check-label" for="studying_none">nicht</label>
                    </div>
                    <div class="small text-danger" wire:show="studying === 'none'">Leider können wir den Mietservice nur für Personen anbieten, welche in einer Karlsruher Hochschule oder Universität immatrikuliert sind.</div>
                    @error('studying')
                    <div class="invalid-feedback d-block">{{$message}}</div>
                    @enderror
                </div>
            </fieldset>

            <div class="row mb-3" wire:show="rentalType == 'organisation'" wire:cloak>
                <label for="legalname" class="col-sm-3 col-lg-2 col-form-label">Juristischer Name</label>
                <div class="col">
                    <input class="form-control @error('legalname')is-invalid @enderror" id="legalname" wire:model="legalname" :required="$wire.rentalType == 'organisation'">
                    @error('legalname')
                    <div class="invalid-feedback">{{$message}}</div>
                    @enderror
                </div>
            </div>

            <div class="row mb-3">
                <label for="street" class="col-sm-3 col-lg-2 col-form-label">Rechnungs&shy;adresse</label>
                <div class="col">
                    <div class="row mb-2">
                        <div class="col-7 col-sm-8">
                            <input class="form-control @error('street')is-invalid @enderror" id="street" wire:model="street" required placeholder="Straße">
                            @error('street')
                            <div class="invalid-feedback">{{$message}}</div>
                            @enderror
                        </div>
                        <div class="col">
                            <input class="form-control @error('number')is-invalid @enderror" id="number" wire:model="number" required placeholder="Hausnummer">
                            @error('number')
                            <div class="invalid-feedback">{{$message}}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-5 col-sm-4">
                            <input class="form-control @error('zip')is-invalid @enderror" id="zip" wire:model="zip" required placeholder="Postleitzahl">
                            @error('zip')
                            <div class="invalid-feedback">{{$message}}</div>
                            @enderror
                        </div>
                        <div class="col">
                            <input class="form-control @error('city')is-invalid @enderror" id="city" wire:model="city" required placeholder="Ort">
                            @error('city')
                            <div class="invalid-feedback">{{$message}}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-3">
                <label for="eventName" class="col-sm-3 col-lg-2 col-form-label">
                    <span class="d-none d-md-inline d-lg-none d-xl-inline">Veranstaltungs&shy;name / Verwendungs&shy;zweck</span>
                    <span class="d-md-none d-lg-inline d-xl-none">Veranstaltungs&shy;name&nbsp;/ Verwen&shy;dungs&shy;zweck</span>
                </label>
                <div class="col">
                    <div class="autogrow-textarea @error('eventName')is-invalid @enderror" data-replicated-value="{{$eventName}}">
                        <textarea onInput="this.parentNode.dataset.replicatedValue=this.value" wire:model="eventName" rows="2" id="eventName" class="form-control @error('eventName')is-invalid @enderror" required></textarea>
                    </div>
                    @error('eventName')
                    <div class="invalid-feedback">{{$message}}</div>
                    @enderror
                </div>
            </div>

            <div class="row mb-3">
                <div class="col offset-sm-3 offset-lg-2">
                    <div class="form-check">
                        <input class="form-check-input @error('revenue')is-invalid @enderror" type="radio" name="revenue" id="revenue_yes" value="1" wire:model="revenue" required>
                        <label class="form-check-label" for="revenue_yes">Es werden Einnahmen erzielt</label>
                        <div class="form-text mt-0 mb-1">Die Vermietung steht im Zusammenhang mit einer Veranstaltung, bei welcher Einnahmen erzielt werden.</div>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input @error('revenue')is-invalid @enderror" type="radio" name="revenue" id="revenue_no" value="0" wire:model="revenue" required>
                        <label class="form-check-label" for="revenue_no">Es werden keine Einnahmen erzielt</label>
                    </div>
                    @error('revenue')
                    <div class="invalid-feedback d-block">{{$message}}</div>
                    @enderror
                </div>
            </div>

            <div x-data="{showComment: $wire.note !== ''}">
                <p x-show="!showComment">
                    <button type="button" class="btn btn-link p-0" x-on:click="showComment=true">Kommentar zur Bestellung hinzufügen</button>
                </p>
                <div class="row mb-3" x-show="showComment" x-cloak>
                    <label for="note" class="col-sm-3 col-lg-2 col-form-label">Kommentar zur Bestellung</label>
                    <div class="col">
                        <div class="autogrow-textarea @error('note')is-invalid @enderror" data-replicated-value="{{$note}}">
                            <textarea onInput="this.parentNode.dataset.replicatedValue=this.value" wire:model="note" rows="2" id="note" class="form-control @error('note')is-invalid @enderror"></textarea>
                        </div>
                        @error('note')
                        <div class="invalid-feedback">{{$message}}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="form-check mb-1">
                <input class="form-check-input" type="checkbox" name="storePrefill" id="storePrefill" value="1" wire:model="storePrefill">
                <label class="form-check-label" for="storePrefill">Angaben auf diesem Gerät merken</label>
            </div>
            <button class="btn btn-primary" :disabled="$wire.studying === 'none'"><i class="fa-solid fa-arrow-right"></i>&nbsp;Zur Bestätigung</button>
        </form>
    @else
        <div class="alert alert-danger">Es befinden sich keine Artikel im Warenkorb.</div>
        <p><a href="{{route('shop')}}" class="btn btn-success btn-lg" wire:navigate><i class="fa-solid fa-right-long"></i> Zu den Artikeln</a></p>
    @endif
</div>
