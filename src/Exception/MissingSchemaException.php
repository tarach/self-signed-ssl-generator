<?php

declare(strict_types=1);

namespace Tarach\SelfSignedCert\Exception;

class MissingSchemaException extends \Exception
{
    public function __construct(mixed $name)
    {
        parent::__construct(sprintf('Schema [%s] does not exists.', $name));
    }
}