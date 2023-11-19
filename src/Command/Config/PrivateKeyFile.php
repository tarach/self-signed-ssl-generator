<?php

declare(strict_types=1);

namespace Tarach\SelfSignedCert\Command\Config;

class PrivateKeyFile extends ConfigFile
{
    const KEY_OPTIONS = 'options';

    public function getOptions(): ?array
    {
        return $this->config[self::KEY_OPTIONS] ?? null;
    }
}