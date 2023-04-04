<?php

namespace App\Messenger;

interface TransportDataProviderInterface
{

    /**
     * Return the transport data list the multi dynamic transport has to handle
     *
     * @return TransportData[]
     */
    public function getAll(): array;

}