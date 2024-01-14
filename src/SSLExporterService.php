<?php

declare(strict_types=1);

namespace Tarach\SelfSignedCert;

use Psr\Log\LoggerInterface;
use Tarach\SelfSignedCert\Command\Config\Config;

class SSLExporterService
{
    public function __construct(
        private Config $config,
        private LoggerInterface $logger,
    ){
    }

    public function toFiles(SSLGeneratorOutput $ssl, string $directory): void
    {
        $this->logger->info(sprintf('Output directory set to [%s].', $directory));

        $csr = $this->config->getSigningRequestFile();
        if ($csr->hasName()) {
            $this->logger->info(sprintf('Saving certificate signing request (CSR) as file [%s].', $csr->getName()));
            openssl_csr_export_to_file($ssl->signingRequest, $directory . $csr->getName());
        }

        $cert = $this->config->getCertificateFile();
        if ($cert->hasName()) {
            $this->logger->info(sprintf('Saving certificate as file [%s].', $cert->getName()));
            openssl_x509_export_to_file($ssl->certificate, $directory . $cert->getName());
        }

        $pkey = $this->config->getPrivateKeyFile();
        if ($pkey->hasName()) {
            $this->logger->info(sprintf('Saving private key as file [%s].', $pkey->getName()));
            openssl_pkey_export_to_file($ssl->privateKey, $directory . $pkey->getName());
        }
    }
}