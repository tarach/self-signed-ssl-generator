<?php

declare(strict_types=1);

namespace Tarach\SelfSignedCert\Command\Config;

interface ConfigOverrideInterface
{
    public function isDefaultValueSet(): bool;
    public function getConfigName(): string;
}