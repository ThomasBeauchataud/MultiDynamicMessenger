<?php

namespace TBCD\Messenger\MultiDynamicTransport;

/**
 * Provide information about transport handled by the multi-dynamic transport
 */
final class TransportData
{

    /**
     * @var string The name of the transport
     */
    private string $name;

    /**
     * @var string The dsn of the transport
     */
    private string $dsn;

    /**
     * @var array The option of the transport
     */
    private array $options;

    /**
     * @param string $name The name of the transport
     * @param string $dsn The dsn of the transport
     * @param array $options The option of the transport
     */
    public function __construct(string $name, string $dsn, array $options = [])
    {
        $this->name = $name;
        $this->dsn = $dsn;
        $this->options = $options;
    }


    public function getName(): string
    {
        return $this->name;
    }

    public function getDsn(): string
    {
        return $this->dsn;
    }

    public function getOptions(): array
    {
        return $this->options;
    }
}