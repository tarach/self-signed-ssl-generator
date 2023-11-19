<?php

declare(strict_types=1);

namespace Tarach\SelfSignedCert\Command\Question;

use Symfony\Component\Console\Input\InputOption;

interface CommandQuestionInterface
{
    public function __construct(float|bool|int|string $default = null);

    public static function getName(): string;

    public static function getCommandOptionName(): string;

    public static function getQuestionString(): string;

    public static function getCommandOption(): InputOption;

    public function hasDefault(): bool;
}