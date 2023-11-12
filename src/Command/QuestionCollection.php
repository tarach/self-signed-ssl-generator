<?php

declare(strict_types=1);

namespace Tarach\SelfSignedCert\Command;

use Iterator;
use Symfony\Component\Console\Question\Question;
use Tarach\SelfSignedCert\Command\Question\CommandQuestionInterface;

class QuestionCollection implements Iterator
{
    private int $index = 0;
    /**
     * @var array<int, Question|CommandQuestionInterface>
     */
    private array $questions;

    public function __construct(array $questions)
    {
        $this->questions = $questions;
    }

    public function current(): Question|CommandQuestionInterface
    {
        return $this->questions[$this->index];
    }

    public function next(): void
    {
        $this->index++;
    }

    public function key(): int
    {
        return $this->index;
    }

    public function valid(): bool
    {
        return array_key_exists($this->index, $this->questions);
    }

    public function rewind(): void
    {
        $this->index = 0;
    }
}