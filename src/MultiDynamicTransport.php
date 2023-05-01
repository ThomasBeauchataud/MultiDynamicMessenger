<?php

namespace TBCD\Messenger\MultiDynamicTransport;

use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Exception\TransportException;
use Symfony\Component\Messenger\Transport\Serialization\PhpSerializer;
use Symfony\Component\Messenger\Transport\Serialization\SerializerInterface;
use Symfony\Component\Messenger\Transport\TransportInterface;

final class MultiDynamicTransport implements TransportInterface
{

    private TransportDataProviderInterface $dsnProvider;
    private SerializerInterface $serializer;
    private iterable $transportFactories;
    private array $transports = [];
    private array $transportDataList = [];

    public function __construct(TransportDataProviderInterface $dsnProvider, iterable $transportFactories,  SerializerInterface $serializer = new PhpSerializer())
    {
        $this->dsnProvider = $dsnProvider;
        $this->serializer = $serializer;
        $this->transportFactories = $transportFactories;
        $this->rebuildTransportList();
    }


    /**
     * @inheritDoc
     */
    public function get(): iterable
    {
        $this->rebuildTransportList();

        $envelopes = [];

        foreach ($this->transports as $dsn => $transport) {
            foreach ($transport->get() as $envelope) {
                $envelope = $envelope->with(new MultiDynamicStamp($dsn));
                $envelopes[] = $envelope;
            }
        }

        return $envelopes;
    }

    /**
     * @inheritDoc
     */
    public function ack(Envelope $envelope): void
    {
        $multiDynamicStamp = $envelope->last(MultiDynamicStamp::class);

        if (null === $multiDynamicStamp) {
            throw new TransportException();
        }

        $dsn = $multiDynamicStamp->getName();

        if (false === isset($this->transports[$dsn])) {
            throw new TransportException();
        }

        $this->transports[$dsn]->ack($envelope);
    }

    /**
     * @inheritDoc
     */
    public function reject(Envelope $envelope): void
    {
        $multiDynamicStamp = $envelope->last(MultiDynamicStamp::class);

        if (null === $multiDynamicStamp) {
            throw new TransportException();
        }

        $dsn = $multiDynamicStamp->getName();

        if (false === isset($this->transports[$dsn])) {
            throw new TransportException();
        }

        $this->transports[$dsn]->reject($envelope);
    }

    /**
     * @inheritDoc
     */
    public function send(Envelope $envelope): Envelope
    {
        $multiDynamicStamp = $envelope->last(MultiDynamicStamp::class);

        if (null === $multiDynamicStamp) {
            throw new TransportException();
        }

        $dsn = $multiDynamicStamp->getName();

        if (false === isset($this->transports[$dsn])) {
            throw new TransportException();
        }

        return $this->transports[$dsn]->send($envelope);
    }

    private function rebuildTransportList(): void
    {
        $transportDataList = $this->dsnProvider->getAll();

        if (false === empty(array_diff($this->transportDataList, $transportDataList))) {
            return;
        }

        foreach ($transportDataList as $transportData) {
            foreach ($this->transportFactories as $transportFactory) {
                if ($transportFactory->supports($transportData->getDsn(), $transportData->getOptions())) {
                    $transport = $transportFactory->createTransport($transportData->getDsn(), $transportData->getOptions(), $this->serializer);
                    $this->transports[$transportData->getName()] = $transport;
                }
            }
        }
    }
}