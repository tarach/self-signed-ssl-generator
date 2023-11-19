<?php

declare(strict_types=1);

namespace Tarach\SelfSignedCert\Command\Option;

use Symfony\Component\Console\Input\InputOption;

abstract class AbstractInputOption extends InputOption
{
    protected bool $isDefaultValueSet = false;

    public function __construct(string $name, array|string $shortcut = null, int $mode = null, string $description = '', float|array|bool|int|string $default = null, array|\Closure $suggestedValues = [])
    {
        if (5 <= func_num_args()) {
            $this->isDefaultValueSet = true;
        }

        parent::__construct($name, $shortcut, $mode, $description, $default, $suggestedValues);
    }

    public function isDefaultValueSet(): bool
    {
        return $this->isDefaultValueSet;
    }
}