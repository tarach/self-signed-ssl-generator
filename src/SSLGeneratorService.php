<?php

declare(strict_types=1);

namespace Tarach\SelfSignedCert;

use Tarach\SelfSignedCert\Command\Config\Config;

readonly class SSLGeneratorService
{
    public function __construct(
        private Config $config
    ) {
    }

    public function generate(DistinguishedNames $names): SSLGeneratorOutput
    {
        $privateKey = openssl_pkey_new($this->config->getPrivateKeyFile()->getOptions());

        // Certificate signing request
        $csr = openssl_csr_new($names->getArray(), $privateKey, ['digest_alg' => 'sha256']);

        $authority = $this->config->getAuthority();
        $key = $authority->getPrivateKey() ?: $privateKey;
        $certificate = openssl_csr_sign($csr, $authority->getCertificate(), $key, $days=365, ['digest_alg' => 'sha256']);

        return new SSLGeneratorOutput(
            $privateKey,
            $csr,
            $certificate
        );
    }
}