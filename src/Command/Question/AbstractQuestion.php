<?php

declare(strict_types=1);

namespace Tarach\SelfSignedCert\Command\Question;

use Symfony\Component\Console\Question\Question;

abstract class AbstractQuestion extends Question implements CommandQuestionInterface
{
    public function __construct(float|bool|int|string $default = null)
    {
        parent::__construct(static::getQuestionString(), $default);
    }

    public function getQuestion(): string
    {
        return $this->format(parent::getQuestion());
    }

    public function hasDefault(): bool
    {
        $default = $this->getDefault();
        return !is_null($default) && $default !== '';
    }

    private function format(string $question): string
    {
        return $question . $this->getDefaultString() . PHP_EOL . ': ';
    }

    private function getDefaultString(): string
    {
        $default = $this->getDefault();
        if (is_null($default) || $default === '') {
            return '';
        }

        return PHP_EOL . sprintf('default: "<fg=green>%s</>"', $default);
    }

    abstract public static function getName(): string;

    abstract public static function getQuestionString(): string;
}