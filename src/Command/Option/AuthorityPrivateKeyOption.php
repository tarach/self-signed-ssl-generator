<?php

declare(strict_types=1);

namespace Tarach\SelfSignedCert\Command\Option;

use Symfony\Component\Console\Input\InputOption;
use Tarach\SelfSignedCert\Command\Config\Config;
use Tarach\SelfSignedCert\Command\Config\ConfigOverrideInterface;

class AuthorityPrivateKeyOption extends AbstractInputOption implements ConfigOverrideInterface
{
    public function __construct()
    {
        parent::__construct(
            'caKey',
            null,
            InputOption::VALUE_REQUIRED,
            'Certificate Authority (CA) private key file path.',
        );
    }

    public function getConfigName(): string
    {
        return Config::KEY_AUTHORITY . '.' . Config::KEY_AUTHORITY_PKEY;
    }
}