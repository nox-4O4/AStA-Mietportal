document.addEventListener('livewire:init', () => {
    //
    // adds a new livewire directive to execute javascript code after a component has been (re-)rendered
    //
    const componentRenderCallbacks = {}
    const initializedComponents = {}

    Livewire.directive('rendered', ({el, directive, component, cleanup}) => {
        componentRenderCallbacks[component.id] = () => Function('element', directive.expression)(el)
        cleanup(() => delete componentRenderCallbacks[component.id])

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
        }
    })

    // store all initialized components so we can later execute their render callback if they register any
    Livewire.hook('component.init', ({component, cleanup}) => {
        initializedComponents[component.id] = true;
        cleanup(() => delete initializedComponents[component.id])
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
