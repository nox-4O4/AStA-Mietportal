<div>
    <h1>Bestellung erfolgreich</h1>
    <p><span class="fs-6 badge text-bg-secondary">Bestellnummer: #{{$order_id}}</span></p>

    @content('checkout.success')

    @if(!$mailSent)
        <div class="alert alert-warning mt-3">
            Beim E-Mail-Versand ist leider ein Fehler aufgetreten. Die Bestellung wurde dennoch gespeichert.
            @if(config('shop.notification_address'))
                <br>
                Wenn du eine Eingangsbestätigung erhalten möchtest, wende dich per E-Mail an <a href="mailto:{{config('shop.notification_address')}}">{{config('shop.notification_address')}}</a>.
            @endif
        </div>
    @endif

    <div class="mt-3 text-center text-sm-start d-print-none">
        <a href="{{route('shop')}}" wire:navigate class="btn btn-outline-primary"><i class="fa-solid fa-house"></i> Zurück zur Startseite</a>
    </div>
</div>
