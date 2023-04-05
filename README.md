# MultiDynamicMessenger

This package is an extension of symfony/messenger component providing transport that be modified during runtime
without having the restart consumers

## Installation

```
composer require tbcd/multi-dynamic-messenger
```

## Usage

1. First you have to implements a service providing the transport data.

```
# src\Messenger\EntityTransportDataProvider.php

class EntityTransportDataProvider implements TransportDataProviderInterface
{

    public function getAll(): array
    {
        $entities = $this->em->getRepository(TransportEntity::class)->findAll();
        return array_map(function(TransportEntity $entity) {
            return new TransportData($entity->getName(), $entity->getDsn(), $entity->getOptions());
        }, $entities);
    }
}
```

2. Then you have to configure the transport factory

```
# config/services.yaml

services:

    ...

    TBCD\Messenger\MultiDynamicTransport\MultiDynamicTransportFactory:
        tags: [ messenger.transport_factory ]
        binds:
            $transportDataProvider: App\Messenger\EntityTransportDataProvider
```