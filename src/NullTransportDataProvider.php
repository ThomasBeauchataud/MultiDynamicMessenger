<?php

namespace App\Messenger;

final class NullTransportDataProvider implements TransportDataProviderInterface
{

    public function getAll(): array
    {
        return [];
    }
}