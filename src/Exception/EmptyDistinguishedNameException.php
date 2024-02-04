<?php

declare(strict_types=1);

namespace Tarach\SelfSignedCert\Exception;

class EmptyDistinguishedNameException extends \Exception
{
    public function __construct(string $name)
    {
        parent::__construct(sprintf('Distinguished name [%s] cannot be empty.', $name));
    }
}