<?php

declare(strict_types=1);

namespace Tarach\SelfSignedCert\Command\Config;

use OpenSSLAsymmetricKey;
use OpenSSLCertificate;
use RuntimeException;

readonly class Authority
{
    public ?string $cert;
    public ?string $pkey;

    public function __construct(
        ?string $cert,
        ?string $pkey
    ){
        $files = [
            'cert' => $cert,
            'pkey' => $pkey,
        ];

        foreach ($files as $type => $file) {
            if (!$file) {
                continue;
            }

            $filePath = realpath($file);

            if (false === $filePath) {
                throw new RuntimeException(sprintf('Specified path [%s] for [%s] is not a valid CA file.', $file, $type));
            }

            if (!is_readable($filePath)) {
                throw new RuntimeException(sprintf('Specified file  [%s] for [%s] is not readable.', $file, $type));
            }
        }

        $this->cert = $cert ? realpath($cert) : null;
        $this->pkey = $pkey ? realpath($pkey) : null;
    }

    public function getCertificate(): ?OpenSSLCertificate
    {
        if (!$this->cert) {
            return null;
        }

        $certificate = openssl_x509_read(file_get_contents($this->cert));
        if (!$certificate) {
            throw new RuntimeException(sprintf('Specified file [%s] is not a valid CA format.', $this->cert));
        }
        return $certificate;
    }

    public function getPrivateKey(): ?OpenSSLAsymmetricKey
    {
        if (!$this->pkey) {
            return null;
        }

        $privateKey = openssl_pkey_get_private(file_get_contents($this->pkey));
        if (!$privateKey) {
            throw new RuntimeException(sprintf('Specified file [%s] is not a valid Private Key format.', $this->cert));
        }
        return $privateKey;
    }
}