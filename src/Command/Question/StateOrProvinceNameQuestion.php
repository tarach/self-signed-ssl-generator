<?php

declare(strict_types=1);

namespace Tarach\SelfSignedCert\Command\Question;

use Symfony\Component\Console\Input\InputOption;

class StateOrProvinceNameQuestion extends AbstractQuestion
{
    public static function getQuestionString(): string
    {
        return 'State or province';
    }

    public static function getCommandOption(): InputOption
    {
        return new InputOption(
            'sp',
            null,
            InputOption::VALUE_REQUIRED,
            self::getQuestionString(),
        );
    }

    public static function getName(): string
    {
        return 'stateOrProvinceName';
    }
}