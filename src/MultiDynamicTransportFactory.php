<?php

namespace TBCD\Messenger\MultiDynamicTransport;

use Exception;
use InvalidArgumentException;
use Symfony\Component\DependencyInjection\Attribute\TaggedIterator;
use Symfony\Component\Messenger\Transport\Serialization\SerializerInterface;
use Symfony\Component\Messenger\Transport\TransportFactoryInterface;
use Symfony\Component\Messenger\Transport\TransportInterface;
use Symfony\Contracts\Service\Attribute\Required;

final class MultiDynamicTransportFactory implements TransportFactoryInterface
{

    /**
     * @var TransportDataProviderInterface[]
     */
    private array $transportDataProviders = [];

    /**
     * @var TransportFactoryInterface[]
     */
    private array $transportFactories = [];

    /**
     * @param TransportDataProviderInterface[] $transportDataProviders
     */
    public function __construct(#[TaggedIterator('tbcd.messenger.transport_data_provider')] iterable $transportDataProviders = [])
    {
        if (empty($transportDataProviders)) {
            $this->transportDataProviders = [new NullTransportDataProvider()];
        }

        foreach ($transportDataProviders as $transportDataProvider) {
            if (!$transportDataProvider instanceof TransportDataProviderInterface) {
                throw new InvalidArgumentException();
            }
            $this->transportDataProviders[$transportDataProvider::class] = $transportDataProvider;
        }
    }


    /**
     * @param iterable $transportFactories
     * @return void
     */
    #[Required]
    public function setTransportFactories(#[TaggedIterator('messenger.transport_factory')] iterable $transportFactories): void
    {
        foreach ($transportFactories as $transportFactory) {
            if (!$transportFactory instanceof TransportFactoryInterface) {
                throw new InvalidArgumentException();
            }
            $this->transportFactories[] = $transportFactory;
        }
    }

    /**
     * @param string $dsn
     * @param array $options
     * @param SerializerInterface $serializer
     * @return TransportInterface
     * @throws Exception
     */
    public function createTransport(string $dsn, array $options, SerializerInterface $serializer): TransportInterface
    {
        if (count($this->transportDataProviders) === 0) {
            throw new Exception(sprintf('The transport factory %s has be initialized with transport data providers via the method setTransportFactories with a none empty iterator', self::class));
        }

        if (isset($options['transport_data_provider'])) {
            if (!isset($this->transportDataProviders[$options['transport_data_provider']])) {
                throw new Exception(sprintf('The transport data provider %s does exist in the transport factory %s', $options['transport_data_provider'], self::class));
            } else {
                $transportDataProvider = $this->transportDataProviders[$options['transport_data_provider']];
            }
        } else {
            if (count($this->transportDataProviders) > 1) {
                throw new Exception(sprintf('When dealing with multiple transport data providers with the transport %s you have to specify which one to use for each transport', self::class));
            } else {
                $transportDataProvider = reset($this->transportDataProviders);
            }
        }

        return new MultiDynamicTransport($transportDataProvider, $this->transportFactories, $serializer);
    }

    /**
     * @param string $dsn
     * @param array $options
     * @return bool
     */
    public function supports(string $dsn, array $options): bool
    {
        return str_starts_with($dsn, 'multi-dynamic://');
    }
}