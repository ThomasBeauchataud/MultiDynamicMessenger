<?php

namespace App\tests;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Messenger\Transport\Serialization\PhpSerializer;
use Symfony\Component\Messenger\Transport\TransportInterface;
use TBCD\Messenger\MultiDynamicTransport\MultiDynamicTransportFactory;

class MultiDynamicTransportFactoryTest extends TestCase
{

    public function testSupport(): void
    {
        $transportFactory = new MultiDynamicTransportFactory();
        $support = $transportFactory->supports('multi-dynamic://', []);
        $this->assertTrue($support);
        $support = $transportFactory->supports('multi-dym://', []);
        $this->assertFalse($support);
    }

    public function testCreate(): void
    {
        $transportFactory = new MultiDynamicTransportFactory();
        $transport = $transportFactory->createTransport('multi-dynamic://', [], new PhpSerializer());
        $this->assertInstanceOf(TransportInterface::class, $transport);
    }
}