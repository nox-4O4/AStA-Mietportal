<?php //0e1d0a61cba31ed2a5cdea901143ee4d
/** @noinspection all */

namespace Illuminate\Broadcasting {
    
    /**
     * @method static void dispatch(array|Channel|string $channels)
     * @method static PendingBroadcast broadcast(array|Channel|string $channels)
     */
    class AnonymousEvent {}
}

namespace Illuminate\Bus {

    use Illuminate\Foundation\Bus\PendingDispatch;
    
    /**
     * @method static PendingDispatch dispatch(PendingBatch $batch)
     * @method static mixed dispatchSync(PendingBatch $batch)
     */
    class ChainedBatch {}
}

namespace Illuminate\Foundation\Console {

    use Illuminate\Foundation\Bus\PendingDispatch;
    
    /**
     * @method static PendingDispatch dispatch(array $data)
     * @method static mixed dispatchSync(array $data)
     */
    class QueuedCommand {}
}

namespace Illuminate\Foundation\Events {

    use Illuminate\Broadcasting\PendingBroadcast;
    
    /**
     * @method static void dispatch(array $stubs)
     * @method static PendingBroadcast broadcast(array $stubs)
     */
    class PublishingStubs {}
}

namespace Illuminate\Queue {

    use Illuminate\Foundation\Bus\PendingDispatch;
    use Laravel\SerializableClosure\SerializableClosure;
    
    /**
     * @method static PendingDispatch dispatch(SerializableClosure $closure)
     * @method static mixed dispatchSync(SerializableClosure $closure)
     */
    class CallQueuedClosure {}
}
