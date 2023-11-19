<?php

declare(strict_types=1);

namespace Tarach\SelfSignedCert\Command\Config;

class ConfigFile
{
    const KEY_NAME = 'name';

    public function __construct(
        protected readonly array $config
    ) {
    }

    public function getName(): ?string
    {
        return $this->config[self::KEY_NAME] ?? null;
    }

    public function hasName(): bool
    {
        return array_key_exists(self::KEY_NAME, $this->config) && !empty($this->config[self::KEY_NAME]);
    }
}