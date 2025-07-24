<?php //0849994ff0be47f2c80c51b258081394
/** @noinspection all */

namespace Carbon {
    
    /**
     * @method static string formatLocalDate()
     * @method static string formatLocalTime(bool $seconds = true)
     */
    class CarbonImmutable {}
}

namespace Illuminate\Contracts\View {
    
    /**
     * @method static $this extends($view, $params = [])
     * @method static $this layout($view, $params = [])
     * @method static $this layoutData($data = [])
     * @method static $this response(callable $callback)
     * @method static $this section($section)
     * @method static $this slot($slot)
     * @method static $this title($title)
     */
    class View {}
}

namespace Illuminate\Database\Eloquent {

    use Illuminate\Support\HigherOrderTapProxy;
    
    /**
     * @method static Model|HigherOrderTapProxy createOrRestore(array $attributes = [], array $values = [])
     * @method static $this onlyTrashed()
     * @method static int restore()
     * @method static Model|HigherOrderTapProxy restoreOrCreate(array $attributes = [], array $values = [])
     * @method static $this withTrashed($withTrashed = true)
     * @method static $this withoutTrashed()
     */
    class Builder {}
}

namespace Illuminate\Http {
    
    /**
     * @method static bool hasValidRelativeSignature()
     * @method static bool hasValidRelativeSignatureWhileIgnoring($ignoreQuery = [])
     * @method static bool hasValidSignatureWhileIgnoring($ignoreQuery = [], $absolute = true)
     */
    class Request {}
}

namespace Illuminate\Routing {
    
    /**
     * @method static $this lazy($enabled = true)
     */
    class Route {}
}

namespace Illuminate\Testing {
    
    /**
     * @method static $this assertDontSeeLivewire($component)
     * @method static $this assertSeeLivewire($component)
     */
    class TestResponse {}
    
    /**
     * @method static $this assertDontSeeLivewire($component)
     * @method static $this assertSeeLivewire($component)
     */
    class TestView {}
}

namespace Illuminate\View {

    use Livewire\WireDirective;
    
    /**
     * @method static WireDirective wire($name)
     */
    class ComponentAttributeBag {}
    
    /**
     * @method static $this extends($view, $params = [])
     * @method static $this layout($view, $params = [])
     * @method static $this layoutData($data = [])
     * @method static $this response(callable $callback)
     * @method static $this section($section)
     * @method static $this slot($slot)
     * @method static $this title($title)
     */
    class View {}
}

namespace Laravel\Dusk {
    
    /**
     * @method static $this assertAttributeMissing($selector, $attribute)
     * @method static $this assertClassMissing($selector, $className)
     * @method static $this|void assertConsoleLogHasWarning($expectedMessage)
     * @method static $this|void assertConsoleLogMissingWarning($expectedMessage)
     * @method static $this assertHasClass($selector, $className)
     * @method static $this|void assertInViewPort($selector, $invert = false)
     * @method static \Closure assertNotInViewPort($selector)
     * @method static $this|void assertNotPresent($selector)
     * @method static $this|void assertNotVisible($selector)
     * @method static $this|void assertScript($js, $expects = true)
     * @method static mixed offline()
     * @method static mixed online()
     * @method static $this runScript($js)
     * @method static $this scrollTo($selector)
     * @method static $this selectMultiple($field, $values = [])
     * @method static object waitForLivewire($callback = null)
     * @method static mixed waitForLivewireToLoad()
     * @method static object waitForNavigate($callback = null)
     * @method static object waitForNavigateRequest($callback = null)
     * @method static object waitForNoLivewire($callback = null)
     * @method static object waitForNoNavigateRequest($callback = null)
     */
    class Browser {}
}
