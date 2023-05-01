<?php

namespace TBCD\Messenger\MultiDynamicTransport\Tests;

use Exception;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Messenger\Transport\Serialization\PhpSerializer;
use Symfony\Component\Messenger\Transport\TransportFactoryInterface;
use Symfony\Component\Messenger\Transport\TransportInterface;
use TBCD\Messenger\MultiDynamicTransport\MultiDynamicTransportFactory;
use TBCD\Messenger\MultiDynamicTransport\NullTransportDataProvider;

class MultiDynamicTransportFactoryTest extends TestCase
{

    /**
     * @return void
     */
    public function testInstantiateWithoutTransportDataProvider(): void
    {
        $transportFactory = new MultiDynamicTransportFactory();
        $this->assertInstanceOf(TransportFactoryInterface::class, $transportFactory);
    }

    /**
     * @return void
     */
    public function testInstantiateWithTransportDataProvider(): void
    {
        $transportFactory = new MultiDynamicTransportFactory([new NullTransportDataProvider()]);
        $this->assertInstanceOf(TransportFactoryInterface::class, $transportFactory);
    }

    /**
     * @return void
     * @throws Exception
     */
    public function testInstantiateWithInvalidTransportDataProvider(): void
    {
        try {
            new MultiDynamicTransportFactory([new PhpSerializer()]);
            throw new Exception('Failed to pass the test testInstantiateWithInvalidTransportDataProvider');
        } catch (InvalidArgumentException) {
            $this->addToAssertionCount(1);
        }
    }

    /**
     * @return void
     */
    public function testSupport(): void
    {
        $transportFactory = new MultiDynamicTransportFactory();
        $support = $transportFactory->supports('multi-dynamic://', []);
        $this->assertTrue($support);
        $support = $transportFactory->supports('multi-dym://', []);
        $this->assertFalse($support);
    }

    /**
     * @return void
     * @throws Exception
     */
    public function testCreateWithoutTransportDataProvider(): void
    {
        $transportFactory = new MultiDynamicTransportFactory();
        $transport = $transportFactory->createTransport('multi-dynamic://', [], new PhpSerializer());
        $this->assertInstanceOf(TransportInterface::class, $transport);
    }

    /**
     * @return void
     * @throws Exception
     */
    public function testCreateWithTransportDataProvider(): void
    {
        $transportFactory = new MultiDynamicTransportFactory([new NullTransportDataProvider()]);
        $transport = $transportFactory->createTransport('multi-dynamic://', [], new PhpSerializer());
        $this->assertInstanceOf(TransportInterface::class, $transport);
    }
}