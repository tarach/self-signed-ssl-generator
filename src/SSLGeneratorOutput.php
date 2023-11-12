<?php

declare(strict_types=1);

namespace Tarach\SelfSignedCert;

use OpenSSLAsymmetricKey;
use OpenSSLCertificate;
use OpenSSLCertificateSigningRequest;

readonly class SSLGeneratorOutput
{
    public function __construct(
        public OpenSSLAsymmetricKey             $privateKey,
        public OpenSSLCertificateSigningRequest $signingRequest,
        public OpenSSLCertificate               $certificate,
    ){
    }

    public function getPublicKey(): OpenSSLAsymmetricKey
    {
        $details = openssl_pkey_get_details($this->privateKey);
        return openssl_pkey_get_public($details['key']);
    }
}