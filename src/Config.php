<?php

declare(strict_types=1);

namespace Tarach\SelfSignedCert;

class Config
{
    const KEY_DEFAULTS = 'defaults';

    private bool $hasAnyDefaults;

    public function __construct(
        private readonly array $config
    ) {
        $this->hasAnyDefaults = array_key_exists(self::KEY_DEFAULTS, $this->config);
    }

    public function hasDefault(string $key): bool
    {
        return $this->hasAnyDefaults && array_key_exists($key, $this->config[self::KEY_DEFAULTS]);
    }

    public function getDefault(string $key): mixed
    {
        return $this->config[self::KEY_DEFAULTS][$key];
    }
}