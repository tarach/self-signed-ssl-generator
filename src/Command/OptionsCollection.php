<?php

declare(strict_types=1);

namespace Tarach\SelfSignedCert\Command;

use Iterator;
use Symfony\Component\Console\Input\InputOption;

class OptionsCollection implements Iterator
{
    private int $index = 0;
    /**
     * @var array<int, InputOption>
     */
    private array $option;

    public function __construct(array $options)
    {
        $this->option = $options;
    }

    public function current(): InputOption
    {
        return $this->option[$this->index];
    }

    public function next(): void
    {
        $this->index++;
    }

    public function key(): int
    {
        return $this->index;
    }

    public function valid(): bool
    {
        return array_key_exists($this->index, $this->option);
    }

    public function rewind(): void
    {
        $this->index = 0;
    }
}