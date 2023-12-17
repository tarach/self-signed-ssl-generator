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

        $days = 365;
        $options = [
            'digest_alg' => 'sha256',
        ];

        // Certificate signing request
        $csr = openssl_csr_new($names->getArray(), $privateKey, $options);

        $authority = $this->config->getAuthority();
        $key = $authority->getPrivateKey() ?: $privateKey;
        $certificate = openssl_csr_sign($csr, $authority->getCertificate(), $key, $days, $options);

        return new SSLGeneratorOutput(
            $privateKey,
            $csr,
            $certificate
        );
    }
}