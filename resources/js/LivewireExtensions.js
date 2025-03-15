document.addEventListener('livewire:init', () => {
    //
    // adds a new livewire directive to execute javascript code after a component has been (re-)rendered
    //
    const componentRenderCallbacks = {}
    const initializedComponents = {}
    const previouslyRegisteredComponentIds = {}

    // Instead of using wire:rendered alpine has an x-init tag. Unfortunately, x-init is currently broken (see https://github.com/alpinejs/alpine/discussions/4453).
    Livewire.directive('rendered', ({el, directive, component, cleanup}) => {
        componentRenderCallbacks[component.id] = () => Function('element', directive.expression)(el)
        cleanup(() => {
            delete componentRenderCallbacks[component.id]

            // Cleanup gets called when the element that contained the directive gets removed from the DOM. If the element is lateron
            // reinserted into the DOM without component re-initialisation, only morphed gets invoked (before the rendered directive is
            // handled). So, we store any previously registered component IDs to re-add them to initializedComponents when they are
            // morphed again. Thus, when the rendered directive is handled afterward we know to re-execute the directive's expression.
            previouslyRegisteredComponentIds[component.id] = true
        })

        // On initial page load morphed hook will not be called (no morphing occurs).
        // So, we invoke the callback here as the component has been rendered when directives are being processed.
        if (Object.hasOwn(initializedComponents, component.id)) {
            delete initializedComponents[component.id]
            componentRenderCallbacks[component.id]()
        }
    })

    // we use morphed hook to execute callback after component has been refreshed
    Livewire.hook('morphed', ({el, component}) => {
        if (Object.hasOwn(componentRenderCallbacks, component.id)) {
            componentRenderCallbacks[component.id]()
        } else if (Object.hasOwn(previouslyRegisteredComponentIds, component.id)) {
            delete previouslyRegisteredComponentIds[component.id]
            initializedComponents[component.id] = true
        }
    })

    // store all initialized components so we can later execute their render callback if they register any
    Livewire.hook('component.init', ({component, cleanup}) => {
        initializedComponents[component.id] = true
        cleanup(() => delete initializedComponents[component.id])
    })

    //
    // reload when page expired
    //
    Livewire.hook('request', ({fail}) => {
        fail(({status, preventDefault}) => {
            if (status === 419) {
                preventDefault()
                location.reload()
            }
        })
    })

    //
    // adds a new livewire directive to refresh a component if it is cached on navigation
    //
    const refreshComponents = {}
    let cached = false

    Livewire.directive('refresh-when-cached', ({el, directive, component, cleanup}) => {
        refreshComponents[component.id] = true
        // intentionally no cleanup
    })

    document.addEventListener('livewire:navigate', event => cached = event.detail.cached)

    document.addEventListener('livewire:navigated', () => {
        if (cached) {
            for (const id of Object.keys(refreshComponents)) {
                Livewire.find(id)?.$refresh()
            }

            cached = false
        }
    })
})
