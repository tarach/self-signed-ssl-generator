<?php

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;

/**
 * Defines application features from the specific context.
 */
class FeatureContext implements Context
{
    private ApplicationRunner $applicationRunner;
    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
        $this->applicationRunner = new ApplicationRunner();
    }

    /**
     * @When /^I execute command sslgen (?<params>.*)$/
     */
    public function iExecuteCommandSslgen(string $params): void
    {
        $this->applicationRunner->clearAnswers();
        $this->applicationRunner->setOptions($params);
    }

    /**
     * @When /^I execute command:$/
     */
    public function iExecuteCommand(PyStringNode $string): void
    {
        $params = '';
        foreach ($string->getStrings() as $line) {
            $params .= rtrim(trim($line), '\\');
        }
        if (!str_starts_with($params, 'sslgen ')) {
            throw new \Exception('Invalid command. Only sslgen command is supported.');
        }
        $this->applicationRunner->clearAnswers();
        $this->applicationRunner->setOptions(substr($params, 7));
    }

    /**
     * @When /^when asked (to|about) "(?<question>[^"]*)" I answer "(?<answer>[^"]*)"$/
     */
    public function whenAskedAbout(string $question, string $answer): void
    {
        $this->applicationRunner->addAnswer($question, $answer);
    }

    /**
     * @When /^displayed log message should contain:$/
     */
    public function displayedLogMessageShouldContain(PyStringNode $string): void
    {
        if (0 !== $this->applicationRunner->run()) {
            throw new \Exception('Command did not returned correct exit code.');
        }

        $display = $this->applicationRunner->getTester()->getDisplay();

        foreach ($string->getStrings() as $line) {
            if (!str_contains($display, $line)) {
                throw new \Exception(sprintf('Display does not contain [%s] line.', $line));
            }
        }
    }

    /**
     * @Given I'm in a project root directory
     */
    public function imInAProjectRootDirectory(): void
    {
        if (!is_dir(getcwd() . DIRECTORY_SEPARATOR . 'example')) {
            throw new \Exception('Not in project root directory.');
        }
    }

    /**
     * @Then I remove the example\/test directory
     */
    public function iRemoveTheExampleTestDirectory(): void
    {
        $path = getcwd() . '/example/test/';
        array_map('unlink', glob($path . '*.*'));
        rmdir($path);
    }

}
