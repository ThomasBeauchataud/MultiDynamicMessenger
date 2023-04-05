<?php

namespace TBCD\Messenger\MultiDynamicTransport;

final class NullTransportDataProvider implements TransportDataProviderInterface
{

    public function getAll(): array
    {
        return [];
    }
}