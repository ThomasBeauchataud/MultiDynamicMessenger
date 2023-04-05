<?php

namespace TBCD\Messenger\MultiDynamicTransport;

use Symfony\Component\DependencyInjection\Attribute\TaggedIterator;
use Symfony\Component\Messenger\Transport\Serialization\SerializerInterface;
use Symfony\Component\Messenger\Transport\TransportFactoryInterface;
use Symfony\Component\Messenger\Transport\TransportInterface;
use Symfony\Contracts\Service\Attribute\Required;

final class MultiDynamicTransportFactory implements TransportFactoryInterface
{

    private TransportDataProviderInterface $dsnProvider;
    private iterable $transportFactories = [];

    public function __construct(TransportDataProviderInterface $dsnProvider = new NullTransportDataProvider())
    {
        $this->dsnProvider = $dsnProvider;
    }


    #[Required]
    public function setTransportFactories(#[TaggedIterator('messenger.transport_factory')] iterable $transportFactories): void
    {
        $this->transportFactories = $transportFactories;
    }

    public function createTransport(string $dsn, array $options, SerializerInterface $serializer): TransportInterface
    {
        return new MultiDynamicTransport($this->dsnProvider, $this->transportFactories, $serializer);
    }

    public function supports(string $dsn, array $options): bool
    {
        return str_starts_with($dsn, 'multi-dynamic://');
    }
}