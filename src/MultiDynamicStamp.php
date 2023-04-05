<?php

namespace TBCD\Messenger\MultiDynamicTransport;

use Symfony\Component\Messenger\Stamp\NonSendableStampInterface;
use Symfony\Component\Messenger\Stamp\StampInterface;

/**
 * Stamp to identify the transport used or to use in the multi-dynamic transport by his name
 */
final class MultiDynamicStamp implements StampInterface, NonSendableStampInterface
{

    /**
     * @var string The name of the transport used or to use
     */
    private string $name;

    /**
     * @param string $name The name of the transport used or to use
     */
    public function __construct(string $name)
    {
        $this->name = $name;
    }


    public function getName(): string
    {
        return $this->name;
    }
}