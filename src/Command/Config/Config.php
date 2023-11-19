<?php

declare(strict_types=1);

namespace Tarach\SelfSignedCert\Command\Config;

class Config
{
    const KEY_AUTHORITY = 'authority';
    const KEY_AUTHORITY_CERT = 'cert';
    const KEY_AUTHORITY_PKEY = 'pkey';
    const KEY_DIRECTORY = 'directory';
    const KEY_OVERWRITE = 'overwrite';
    const KEY_SKIP = 'skip';
    const KEY_FILES = 'files';
    const KEY_DEFAULTS = 'defaults';
    const KEY_CSR_FILE_NAME = 'csr';
    const KEY_CERT_FILE_NAME = 'cert';
    const KEY_PKEY_FILE_NAME = 'pkey';

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

    public function getSigningRequestFile(): SigningRequestFile
    {
        return new SigningRequestFile($this->config[self::KEY_FILES][self::KEY_CSR_FILE_NAME] ?? []);
    }

    public function getCertificateFile(): CertificateFile
    {
        return new CertificateFile($this->config[self::KEY_FILES][self::KEY_CERT_FILE_NAME] ?? []);
    }

    public function getPrivateKeyFile(): PrivateKeyFile
    {
        return new PrivateKeyFile($this->config[self::KEY_FILES][self::KEY_PKEY_FILE_NAME] ?? []);
    }

    public function getOutputDirectory(): string
    {
        return $this->config[self::KEY_DIRECTORY];
    }

    public function isOverwriteEnabled(): bool
    {
        return $this->config[self::KEY_OVERWRITE] ?? false;
    }

    public function isSkipEnabled(): bool
    {
        return $this->config[self::KEY_SKIP] ?? false;
    }

    public function getAuthority(): ?Authority
    {
        return new Authority(
                $this->config[self::KEY_AUTHORITY][self::KEY_AUTHORITY_CERT] ?? null,
                $this->config[self::KEY_AUTHORITY][self::KEY_AUTHORITY_PKEY] ?? null,
            )
        ;
    }
}