<?php

declare(strict_types=1);

namespace Tarach\SelfSignedCert;

class SSLGeneratorService
{
    public function generate(DistinguishedNames $names): SSLGeneratorOutput
    {
        // private and public key pair
        $privateKey = openssl_pkey_new([
            "private_key_bits" => 2048,
            "private_key_type" => OPENSSL_KEYTYPE_RSA,
        ]);

        // Certificate signing request
        $csr = openssl_csr_new($names->getArray(), $privateKey, ['digest_alg' => 'sha256']);

        // Self-signed certificate, valid for 365 days
        $certificate = openssl_csr_sign($csr, null, $privateKey, $days=365, ['digest_alg' => 'sha256']);

        return new SSLGeneratorOutput(
            $privateKey,
            $csr,
            $certificate
        );
    }
}