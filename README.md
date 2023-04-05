# MultiDynamicMessenger

This package is an extension of symfony/messenger component providing transport that be modified during runtime
without having the restart consumers

## Installation

```
composer require tbcd/multi-dynamic-messenger
```

## Usage

First you have to implements a service providing the transports data. This service as to implements
_TransportDataProviderInterface_ interface

In the example below we provide transports data stored with database entities

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

Then you have to configure the transport factory :

- Add the tag _messenger.transport_factory_ to the transport factory
- Bind your _TransportDataProvider_

```
# config/services.yaml

services:

    ...

    TBCD\Messenger\MultiDynamicTransport\MultiDynamicTransportFactory:
        tags: [ messenger.transport_factory ]
        binds:
            $transportDataProvider: App\Messenger\EntityTransportDataProvider
```

Then you have to create the messenger transport

```
# config/packages/messenger.yaml

framework:
    messenger:
        transports:
            # https://symfony.com/doc/current/messenger.html#transport-configuration
            multidynamic:
                dsn: 'multi-dynamic://'
```

Finally, start the consumer with the command `php bin/console messenger:consume mytransportname`