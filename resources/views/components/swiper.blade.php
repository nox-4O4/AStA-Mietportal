@props(['id' => '', 'class' => ''])

<div class="swiper-container {{$class}}" wire:rendered="initSwiper('{{$id}}')">
    <div id="swiper-main-{{$id}}" class="swiper swiper-large">{{$slot}}</div>
    <div id="swiper-thumbs-{{$id}}" class="swiper swiper-thumbs img-small">{{$slot}}</div>
</div>

{{-- without @script tags the script won't be executed when it first appears during livewire refresh and initSwiper won't be available --}}
@script
<script>
    initSwiper = (id) => {
        const mainSwiperContaier = document.getElementById(`swiper-main-${id}`)

        if (!mainSwiperContaier)
            return

        const thumbsSwiper = new Swiper(`#swiper-thumbs-${id}`, {
            createElements: true,
            spaceBetween: 10,
            slidesPerView: 3,
            breakpointsBase: 'container',
            breakpoints: {
                300: {slidesPerView: 3},
                500: {slidesPerView: 5},
                800: {slidesPerView: 7},
            },
            freeMode: {
                enabled: true,
                sticky: true,
            },
            watchSlidesProgress: true,
            centerInsufficientSlides: true,
        })

        const swiper = new Swiper(`#swiper-main-${id}`, {
            createElements: true,
            loop: true,
            effect: 'coverflow',
            coverflowEffect: {
                slideShadows: false,
            },
            zoom: true,
            navigation: true,
            thumbs: {
                swiper: thumbsSwiper,
            },
            hashNavigation: {
                replaceState: true,
                watchState: true,
            },
        })

        // enable zoom on single click for desktop
        let lastX, lastY
        mainSwiperContaier.addEventListener('pointerdown', event => {
            lastX = event.clientX
            lastY = event.clientY
        })
        mainSwiperContaier.addEventListener('pointerup', event => {
            // only fire on left mouse click (touch devices zoom by pinching)
            if (lastX !== null &&
                event.target.tagName === 'IMG' &&
                event.pointerType === 'mouse' &&
                event.button === 0
            ) {
                const deltaX = event.clientX - lastX
                const deltaY = event.clientY - lastY
                const distance = Math.sqrt(deltaY * deltaY + deltaX * deltaX)

                if (distance < 5) { // default swiper threshold
                    swiper.zoom.toggle(event)
                }
            }

            lastX = null
            lastY = null
        })
    }
</script>
@endscript

