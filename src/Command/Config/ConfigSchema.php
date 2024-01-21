<?php

declare(strict_types=1);

namespace Tarach\SelfSignedCert\Command\Config;

readonly class ConfigSchema
{
    public function __construct(
        private array $config
    ) {
    }

    public function hasSchema(string $name): bool
    {
        return array_key_exists($name, $this->config);
    }

    public function getConfig(string $name): Config
    {
        if (!$this->hasSchema($name)) {
            throw new \InvalidArgumentException(sprintf('Schema [%s] is not defined in config.', $name));
        }

        return new Config($this->config[$name]);
    }

    public function getAllSchemas(): array
    {
        return array_keys($this->config);
    }
}