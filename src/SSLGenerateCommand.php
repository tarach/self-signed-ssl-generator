<?php

declare(strict_types=1);

namespace Tarach\SelfSignedCert;

use Exception;
use InvalidArgumentException;
use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Monolog\Logger;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Tarach\SelfSignedCert\Command\Config\Config;
use Tarach\SelfSignedCert\Command\Config\ConfigSchemaLoader;
use Tarach\SelfSignedCert\Command\Option\ConfigOption;
use Tarach\SelfSignedCert\Command\Option\SchemaOption;
use Tarach\SelfSignedCert\Command\OptionsCollection;
use Tarach\SelfSignedCert\Command\OptionsCollectionFactory;
use Tarach\SelfSignedCert\Command\QuestionCollectionFactory;

/**
 * @method getName(): string
 */
class SSLGenerateCommand extends Command
{
    public const RETURN_INVALID_OUTPUT = 3;

    private string $defaultOutputDirectory;
    private QuestionCollectionFactory $questionFactory;
    private OptionsCollection $options;

    public function __construct()
    {
        parent::__construct('ssl:generate');

        $this->defaultOutputDirectory = getcwd() . DIRECTORY_SEPARATOR . 'ssl-cert';
        $this->questionFactory = new QuestionCollectionFactory();

        foreach ($this->questionFactory->getMap() as $question) {
            $inputOption = call_user_func([$question, 'getCommandOption']);
            assert($inputOption instanceof InputOption);
            $this->addInputOption($inputOption);
        }

        $this->options = (new OptionsCollectionFactory())->create(
            $this->defaultOutputDirectory,
            implode(',', $this->getConfigPaths())
        );

        foreach ($this->options as $option) {
            $this->addInputOption($option);
        }
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $logger = $this->createLogger($output->getVerbosity());
        if (!$logger) {
            $logger = $this->createLogger(128);
            $logger->error('Incorrect verbosity level ? To many -vv ?');
            return self::FAILURE;
        }

        try {
            $configSchemaLoader = new ConfigSchemaLoader($this->questionFactory, $this->options);
            $configSchema = $configSchemaLoader->load($input->getOption(ConfigOption::NAME), $input, $this->getDefinition());
        } catch (Exception $exception) {
            $logger->error($exception->getMessage());
            return self::FAILURE;
        }

        $schemas = $configSchema->getAllSchemas();
        $schema = $this->selectSchema($schemas, $input, $output);
        if (!$schema) {
            $logger->error('Wrong schema selected.');
            return self::FAILURE;
        }

        $logger->info(sprintf('Using schema [%s].', $schema));

        $config = $configSchema->getConfig($schema);

        $directory = $config->getOutputDirectory();
        $overwrite = $config->isOverwriteEnabled();

        $directory = (new DirectoryPathNormalizer())->normalize($directory);

        if (file_exists($directory)) {
            if (!is_dir($directory)) {
                $logger->error(sprintf('Path [%s] is not a directory.', $directory));
                return self::RETURN_INVALID_OUTPUT;
            }

            if (!$overwrite) {
                $logger->warning(sprintf('Directory [%s] already exists. Use -o option to force overwrite.', $directory));
                return self::SUCCESS;
            }

            $logger->info(sprintf('Directory [%s] already exists. Will overwrite files.', $directory));
        }

        $names = $this->createDistinguishedNames($config, $input, $output);

        try {
            $service = new SSLGeneratorService($config);
            $ssl = $service->generate($names);
        } catch (Exception $exception) {
            $logger->error($exception->getMessage());
            return self::FAILURE;
        }

        $exporter = new SSLExporterService($config, $logger);
        $exporter->toFiles($ssl, $directory);

        return self::SUCCESS;
    }

    public function createDistinguishedNames(Config $config, InputInterface $input, OutputInterface $output): DistinguishedNames
    {
        $skip = $config->isSkipEnabled();
        $helper = $this->getHelper('question');

        $names = [];
        foreach ($this->questionFactory->create($config) as $question) {
            if ($skip && $question->hasDefault()) {
                $names[$question->getName()] = $question->getDefault();
                continue;
            }
            $names[$question->getName()] = $helper->ask($input, $output, $question);
        }
        return new DistinguishedNames(...$names);
    }

    private function getConfigPaths(): array
    {
        return [
            getcwd() . DIRECTORY_SEPARATOR . 'sslgen.yaml',
            $_SERVER['HOME'] . DIRECTORY_SEPARATOR . '.sslgen' . DIRECTORY_SEPARATOR . 'config.yaml',
            getenv('SSL_GEN_CONFIG'),
        ];
    }

    private function createLogger(int $level): ?LoggerInterface
    {
        $levels = [
            32 => Level::Notice,
            64 => Level::Info,
            128 => Level::Debug,
        ];
        if (!array_key_exists($level, $levels)) {
            return null;
        }

        $logger = new Logger('logger');
        $logger->pushHandler(new StreamHandler('php://stdout', $levels[$level]));

        return $logger;
    }

    private function addInputOption(InputOption $inputOption): void
    {
        assert(!$this->getDefinition()->hasOption($inputOption->getName()));
        $this->getDefinition()->addOption($inputOption);
    }

    private function selectSchema(array $schemas, InputInterface $input, OutputInterface $output): ?string
    {
        if (empty($schemas)) {
            throw new InvalidArgumentException('There must be at least one schema.');
        }

        if (1 === count($schemas)) {
            return array_pop($schemas);
        }

        $schema = $input->getOption(SchemaOption::NAME);
        if (!empty($schema)) {
            $schema = $this->getSelectedSchema($schemas, $schema);
            if (!$schema) {
                return null;
            }
            return $schema;
        }

        return $this->userSelectSchema($schemas, $input, $output);
    }

    private function userSelectSchema(array $schemas, InputInterface $input, OutputInterface $output): string
    {
        $helper = $this->getHelper('question');

        $question = '';
        foreach ($schemas as $index => $schema)
        {
            $index += 1;
            $question .= sprintf("[%s] %s\n", $index, $schema);
        }

        $question .= 'Choose schema: ';

        $schema = null;
        do {
            $index = $helper->ask($input, $output, new Question($question));
            $index -= 1;
            if (array_key_exists($index, $schemas)) {
                $schema = $schemas[$index];
            }
        } while (!$schema);

        return $schema;
    }

    private function getSelectedSchema(array $schemas, mixed $schema): ?string
    {
        if (in_array($schema, $schemas)) {
            return $schema;
        }

        if (array_key_exists($schema - 1, $schemas)) {
            return $schemas[$schema - 1];
        }

        return null;
    }
}