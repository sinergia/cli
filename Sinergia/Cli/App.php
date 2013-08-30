<?php

namespace Sinergia\Cli;

use KevinGH\Amend\Command as AmendCommand;
use KevinGH\Amend\Helper as AmendHelper;
use Stecman\Component\Symfony\Console\BashCompletion\CompletionCommand;
use Symfony\Component\Console\Application;
use FilesystemIterator;
use Symfony\Component\Console\Command\HelpCommand;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class App extends Application
{
    protected $defaultCommandName = 'usage';

    public static function cli()
    {
        $app = new static;
        $app->setup();
        $app->run();
    }

    public function loadConfigFromComposer($file = null)
    {
        if (!$file) $file = $this->getComposerPath();

        $composer = $this->loadComposer($file);

        if ( isset($composer['version']) ) {

            $this->setVersion($composer['version']);
        }

        if ( isset($composer['description']) ) {
            $this->setName($composer['description']);
        }
    }

    public function addSelfUpdateCommand($manifestUrl = null, $name = 'self-update')
    {
        if (! $manifestUrl ) $manifestUrl = $this->getManifestUrl();

        $amend = new AmendCommand($name);
        $amend->setManifestUri($manifestUrl);

        $this->getHelperSet()->set(new AmendHelper());
        $this->add($amend);
    }

    public function addCommandsFromDir($dir)
    {
        $it = new FilesystemIterator($dir);

        foreach ($it as $file) {
            require_once $file;
            $class = basename($file, '.php');
            $this->add(new $class);
        }
    }

    public function addCompletionCommand()
    {
        $this->add(new CompletionCommand());
    }

    public function addUsageCommand()
    {
        $this->add(new UsageCommand());
    }

    public function setup()
    {
        $this->loadConfigFromComposer();
        $this->addSelfUpdateCommand();
        $this->addUsageCommand();

        // eval `bin/cli _completion -g`
        $this->addCompletionCommand();
    }

    /**
     * Gets the default commands that should always be available.
     *
     * @return Command[] An array of default Command instances
     */
    protected function getDefaultCommands()
    {
        return array(new HelpCommand());
    }

    protected function getManifestUrl()
    {
        $file = $this->getComposerPath();
        $composer = $this->loadComposer($file);
        $url = "https://raw.github.com/$composer[name]/master/manifest.json";

        return $url;
    }

    protected function loadComposer($file)
    {
        $json = file_get_contents($file);
        return json_decode($json, true);
    }

    protected function getRoot()
    {
        $entry = $_SERVER['argv'][0];
        return dirname(dirname($entry));
    }

    protected function getComposerPath()
    {
        return $this->getRoot()."/composer.json";
    }

    protected function getDefaultCommandName()
    {
        return $this->defaultCommandName;
    }

    /**
     * Runs the current application.
     *
     * @param InputInterface  $input  An Input instance
     * @param OutputInterface $output An Output instance
     *
     * @return integer 0 if everything went fine, or an error code
     */
    public function doRun(InputInterface $input, OutputInterface $output)
    {
        $name = $this->getCommandName($input);
        if (!$name) {
            $input = new ArrayInput(array('command' => $this->getDefaultCommandName()));
        }

        return parent::doRun($input, $output);
    }
}
