<?php

declare(strict_types=1);

namespace Tarach\SelfSignedCert;

class DirectoryPathNormalizer
{
    private bool $isCreated = false;

    public function __construct(
        private string $directory
    ) {
    }

    public function normalize(): string
    {
        $directory = rtrim($this->directory, '\\/') . DIRECTORY_SEPARATOR;

        if ($this->isStream()) {
            return $directory;
        }

        if ('/' !== $directory[0]) {
            $directory = getcwd() . DIRECTORY_SEPARATOR . $directory;
        }

        if (!file_exists($directory)) {
            $this->isCreated = true;
            if (!@mkdir($directory, 0777, true)) {
                throw new \RuntimeException(sprintf('Unable to create output directory [%s].', $directory));
            }
        }

        return $directory;
    }

    public function hasCreatedDirectory(): bool
    {
        return $this->isCreated;
    }

    public function isStream(): bool
    {
        return str_starts_with($this->directory, 'php://');
    }
}