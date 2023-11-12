<?php

declare(strict_types=1);

namespace Tarach\SelfSignedCert;

readonly class DistinguishedNames
{
    public function __construct(
        public string $countryName,
        public string $stateOrProvinceName,
        public string $localityName,
        public string $organizationName,
        public string $organizationalUnitName,
        public string $commonName,
        public string $emailAddress,
    ) {
    }

    public function getArray(): array
    {
        return get_object_vars($this);
    }
}