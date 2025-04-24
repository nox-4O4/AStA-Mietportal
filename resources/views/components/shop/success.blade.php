<div>
    <h1>Bestellung erfolgreich</h1>
    <p><span class="fs-6 badge text-bg-secondary">Bestellnummer: #{{$order_id}}</span></p>

    @content('checkout.success')

    <div class="mt-3 text-center text-sm-start d-print-none">
        <a href="{{route('shop')}}" wire:navigate class="btn btn-outline-primary"><i class="fa-solid fa-house"></i> Zurück zur Startseite</a>
    </div>
</div>
