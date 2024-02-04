<?php

declare(strict_types=1);

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use Tarach\SelfSignedCert\SSLGenerateCommand;

class ApplicationRunner
{
    private ?array $answers = [];
    private array $questions = [
        'Country Name' => '--un',
        'State or province' => '--sp',
        'Locality name' => '--ln',
        'Organization Name' => '--on',
        'Organization Unit Name' => '--oun',
        'Common Name' => '--cn',
        'Email address' => '--ea',
        'Choose schema' => '',
    ];
    private array $options = [];
    private Application $application;
    private SSLGenerateCommand $command;
    private CommandTester $tester;

    public function __construct()
    {
        $this->application = $this->getApplication();
        $command = $this->application->find('ssl:generate');
        assert($command instanceof SSLGenerateCommand);
        $this->command = $command;

        $this->tester = new CommandTester($this->command);
    }

    public function run(): int
    {
        if (!empty($this->answers)) {
            $this->tester->setInputs($this->answers);
        }

        return $this->tester->execute(
            array_merge(
                [
                    '--directory' => 'php://temp/ssl-test',
                ],
                $this->options
            ),
            [
                'interactive' => true,
//                'verbosity' => OutputInterface::VERBOSITY_VERY_VERBOSE,
            ]
        );
    }

    public function clearAnswers(): void
    {
        $this->answers = [];
    }

    public function addAnswer(string $topic, string $answer): void
    {
        if (!array_key_exists($topic, $this->questions)) {
            throw new \InvalidArgumentException(sprintf('No command option defined under topic [%s].', $topic));
        }

        $this->answers[] = $answer;
    }

    public function setOptions(string $options): void
    {
        $this->options = $this->parseOptions($options);
    }

    public function getTester(): CommandTester
    {
        return $this->tester;
    }

    private function getApplication(): Application
    {
        return require __DIR__ . '/../../bin/app.php';
    }

    private function parseOptions(string $options): array
    {
        $options = trim($options) . ' ';
        if (!str_starts_with($options, '-')) {
            throw new \InvalidArgumentException('Command options need to start with - or --.');
        }
        $output = [];
        $buffer = '';
        $stringStarted = false;
        for ($i=0; $i<strlen($options); $i++)
        {
            $char = $options[$i];

            if ('"' === $char) {
                $stringStarted = !$stringStarted;
            }

            if (' ' === $char && !$stringStarted) {
                list($name, $value) = $this->parseOptionNameAndValue($buffer);
                $output[$name] = $value;
                $buffer = '';
                continue;
            }

            $buffer .= $char;
        }

        return $output;
    }

    private function parseOptionNameAndValue(string $option): array
    {
        $name = '';
        $value = null;
        $nameEnded = false;
        $valueStarted = false;
        for ($i=0; $i<strlen($option); $i++)
        {
            $char = $option[$i];
            if (!$nameEnded) {
                if ('=' === $char) {
                    $nameEnded = true;
                    continue;
                }
                $name .= $char;
            }

            if ($nameEnded && !$valueStarted) {
                if ('=' !== $char && '"' !== $char) {
                    $valueStarted = true;
                }
            }

            if ($valueStarted) {
                $value .= $char;
            }
        }

        return [$name, $value];
    }
}