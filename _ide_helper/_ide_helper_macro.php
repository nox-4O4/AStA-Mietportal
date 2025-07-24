<?php //b5e1241eb869452a66a474db8c5acc80
/** @noinspection all */

namespace Carbon {
    
    /**
     * @method string formatLocalDate()
     * @method string formatLocalTime(bool $seconds = true)
     */
    class CarbonImmutable {}
}

namespace Illuminate\Contracts\View {
    
    /**
     * @method $this extends($view, $params = [])
     * @method $this layout($view, $params = [])
     * @method $this layoutData($data = [])
     * @method $this response(callable $callback)
     * @method $this section($section)
     * @method $this slot($slot)
     * @method $this title($title)
     */
    class View {}
}

namespace Illuminate\Database\Eloquent {

    use Illuminate\Support\HigherOrderTapProxy;
    
    /**
     * @method Model|HigherOrderTapProxy createOrRestore(array $attributes = [], array $values = [])
     * @method $this onlyTrashed()
     * @method int restore()
     * @method Model|HigherOrderTapProxy restoreOrCreate(array $attributes = [], array $values = [])
     * @method $this withTrashed($withTrashed = true)
     * @method $this withoutTrashed()
     */
    class Builder {}
}

namespace Illuminate\Http {
    
    /**
     * @method bool hasValidRelativeSignature()
     * @method bool hasValidRelativeSignatureWhileIgnoring($ignoreQuery = [])
     * @method bool hasValidSignatureWhileIgnoring($ignoreQuery = [], $absolute = true)
     */
    class Request {}
}

namespace Illuminate\Redis {
    
    /**
     * @mixin \Illuminate\Redis\Connections\PhpRedisConnection
     * @mixin \Redis
     */
    class RedisManager {}
}

namespace Illuminate\Routing {
    
    /**
     * @method $this lazy($enabled = true)
     */
    class Route {}
}

namespace Illuminate\Testing {
    
    /**
     * @method $this assertDontSeeLivewire($component)
     * @method $this assertSeeLivewire($component)
     */
    class TestResponse {}
    
    /**
     * @method $this assertDontSeeLivewire($component)
     * @method $this assertSeeLivewire($component)
     */
    class TestView {}
}

namespace Illuminate\View {

    use Livewire\WireDirective;
    
    /**
     * @method WireDirective wire($name)
     */
    class ComponentAttributeBag {}
    
    /**
     * @method $this extends($view, $params = [])
     * @method $this layout($view, $params = [])
     * @method $this layoutData($data = [])
     * @method $this response(callable $callback)
     * @method $this section($section)
     * @method $this slot($slot)
     * @method $this title($title)
     */
    class View {}
}

namespace Laravel\Dusk {
    
    /**
     * @method $this assertAttributeMissing($selector, $attribute)
     * @method $this assertClassMissing($selector, $className)
     * @method $this|void assertConsoleLogHasWarning($expectedMessage)
     * @method $this|void assertConsoleLogMissingWarning($expectedMessage)
     * @method $this assertHasClass($selector, $className)
     * @method $this|void assertInViewPort($selector, $invert = false)
     * @method \Closure assertNotInViewPort($selector)
     * @method $this|void assertNotPresent($selector)
     * @method $this|void assertNotVisible($selector)
     * @method $this|void assertScript($js, $expects = true)
     * @method mixed offline()
     * @method mixed online()
     * @method $this runScript($js)
     * @method $this scrollTo($selector)
     * @method $this selectMultiple($field, $values = [])
     * @method object waitForLivewire($callback = null)
     * @method mixed waitForLivewireToLoad()
     * @method object waitForNavigate($callback = null)
     * @method object waitForNavigateRequest($callback = null)
     * @method object waitForNoLivewire($callback = null)
     * @method object waitForNoNavigateRequest($callback = null)
     */
    class Browser {}
}
