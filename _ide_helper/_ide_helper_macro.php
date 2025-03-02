<?php //c675d1d939012b290ba6326ca2c40bb3
/** @noinspection all */

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

namespace Illuminate\Support {
    
    /**
     * @method string formatLocalDate()
     * @method string formatLocalTime(bool $seconds = true)
     */
    class Carbon {}
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
     * @method $this assertConsoleLogHasWarning($expectedMessage)
     * @method $this assertConsoleLogMissingWarning($expectedMessage)
     * @method $this assertHasClass($selector, $className)
     * @method $this assertInViewPort($selector, $invert = false)
     * @method \Closure assertNotInViewPort($selector)
     * @method $this assertNotPresent($selector)
     * @method $this assertNotVisible($selector)
     * @method $this assertScript($js, $expects = true)
     * @method void offline()
     * @method void online()
     * @method $this runScript($js)
     * @method $this scrollTo($selector)
     * @method $this selectMultiple($field, $values = [])
     * @method object waitForLivewire($callback = null)
     * @method void waitForLivewireToLoad()
     * @method object waitForNavigate($callback = null)
     * @method object waitForNavigateRequest($callback = null)
     * @method object waitForNoLivewire($callback = null)
     * @method object waitForNoNavigateRequest($callback = null)
     */
    class Browser {}
}
