<?php

declare(strict_types=1);

namespace Tarach\SelfSignedCert;

class DirectoryPathNormalizer
{
    public function normalize(string $directory): string
    {
        $directory = rtrim($directory, '\\/') . DIRECTORY_SEPARATOR;

        if ('/' !== $directory[0]) {
            $directory = getcwd() . DIRECTORY_SEPARATOR . $directory;
        }

        if (!file_exists($directory)) {
            if (!@mkdir($directory)) {
                throw new \RuntimeException(sprintf('Unable to create output directory [%s].', $directory));
            }
        }

        return $directory;
    }
}