<?php

declare(strict_types=1);

namespace Tarach\SelfSignedCert\Command\Question;

interface CommandQuestionInterface
{
    public function __construct(float|bool|int|string $default = null);

    public static function getName(): string;

    public static function getQuestionString(): string;

    public function hasDefault(): bool;
}