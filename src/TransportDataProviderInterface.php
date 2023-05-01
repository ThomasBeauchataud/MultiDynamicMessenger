<?php

namespace TBCD\Messenger\MultiDynamicTransport;

use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag('tbcd.messenger.transport_data_provider')]
interface TransportDataProviderInterface
{

    /**
     * Return the transport data list the multi dynamic transport has to handle
     *
     * @return TransportData[]
     */
    public function getAll(): array;

}