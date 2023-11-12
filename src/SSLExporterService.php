<?php

declare(strict_types=1);

namespace Tarach\SelfSignedCert;

class SSLExporterService
{
    public function toFiles(SSLGeneratorOutput $ssl, string $directory): void
    {
        $directory = $this->normalizeDirectoryPath($directory);

        openssl_x509_export_to_file($ssl->certificate, $directory . 'ca.pem');
        openssl_pkey_export_to_file($ssl->privateKey, $directory . 'privkey.pem');
    }

    private function normalizeDirectoryPath(string $directory): string
    {
        $directory = rtrim($directory, '\\/') . DIRECTORY_SEPARATOR;

        if (!file_exists($directory)) {
            if (!@mkdir($directory)) {
                throw new \RuntimeException(sprintf('Unable to create output directory [%s].', $directory));
            }
        }

        return $directory;
    }
}