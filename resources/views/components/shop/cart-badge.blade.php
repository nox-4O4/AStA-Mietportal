<a class="h-100 d-flex align-items-center"
   href="{{route('shop.cart')}}"
   title="Warenkorb"
   wire:navigate
   x-data="{items: $persist($wire.entangle('items').live).as('cart-items')}"
   @@storage.window="$event.key == 'cart-items' && $wire.$refresh()"
>
    <span class="me-1 d-none d-md-inline">
        Warenkorb
        @if($items)
        ({{count($items)}})
        @endif
    </span>
    <span class="fa-stack shopping-cart fa-lg h-100 fa-animate-once" :class="{'fa-bounce': $wire.newItem, 'fa-shake': $wire.cleared}" x-on:animationend="$wire.newItem = $wire.cleared = false">
        @if($items)
            <i class="fa-solid fa-cloud fa-stack-1x loot"></i>
        @endif
        <i class="fa-solid fa-cart-shopping fa-stack-1x fa-xl cart"></i>
    </span>
</a>
