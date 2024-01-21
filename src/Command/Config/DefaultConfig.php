<?php

declare(strict_types=1);

namespace Tarach\SelfSignedCert\Command\Config;

class DefaultConfig extends Config
{
    private array $defaultValues = [
        self::KEY_DIRECTORY => null,
        self::KEY_SKIP => false,
        self::KEY_OVERWRITE => false,
        self::KEY_FILES => [
            self::KEY_CSR_FILE_NAME => 'csr.req',
            self::KEY_CERT_FILE_NAME => 'cert.pem',
            self::KEY_PKEY_FILE_NAME => 'pkey.key',
        ],
    ];
    public function __construct(array $config)
    {
        $this->defaultValues[self::KEY_DIRECTORY] = getcwd() . DIRECTORY_SEPARATOR . 'ssl-cert';

        parent::__construct(array_merge($this->defaultValues, $config));
    }
}