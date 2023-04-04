<?php

namespace App\Messenger;

use Symfony\Component\Messenger\Stamp\NonSendableStampInterface;
use Symfony\Component\Messenger\Stamp\StampInterface;

final class MultiDynamicStamp implements StampInterface, NonSendableStampInterface
{

    private string $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }


    public function getName(): string
    {
        return $this->name;
    }
}