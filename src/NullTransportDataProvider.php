<?php

namespace TBCD\Messenger\MultiDynamicTransport;

final class NullTransportDataProvider implements TransportDataProviderInterface
{

    /**
     * @inheritDoc
     */
    public function getAll(): array
    {
        return [];
    }
}