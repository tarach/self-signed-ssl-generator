<?php

declare(strict_types=1);

namespace Tarach\SelfSignedCert\Command\Config;

trait ArrayHelperTrait
{
    protected function &getElementInArrayToSet(array &$config, array $path)
    {
        $key = array_shift($path);
        if (!array_key_exists($key, $config)) {
            $config[$key] = [];
        }

        if (0 === count($path)) {
            return $config[$key];
        }

        return $this->getElementInArrayToSet($config[$key], $path);
    }
}