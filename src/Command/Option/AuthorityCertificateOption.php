<?php

declare(strict_types=1);

namespace Tarach\SelfSignedCert\Command\Option;

use Symfony\Component\Console\Input\InputOption;
use Tarach\SelfSignedCert\Command\Config\Config;
use Tarach\SelfSignedCert\Command\Config\ConfigOverrideInterface;

class AuthorityCertificateOption extends AbstractInputOption implements ConfigOverrideInterface
{
    public function __construct()
    {
        parent::__construct(
            'caCert',
            null,
            InputOption::VALUE_REQUIRED,
            'Certificate Authority (CA) certificate file path.',
        );
    }

    public function getConfigName(): string
    {
        return Config::KEY_AUTHORITY . '.' . Config::KEY_AUTHORITY_CERT;
    }
}