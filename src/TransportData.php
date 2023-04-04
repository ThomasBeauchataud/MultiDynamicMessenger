<?php

namespace App\Messenger;

final class TransportData
{

    private string $name;
    private string $dsn;
    private array $options;

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