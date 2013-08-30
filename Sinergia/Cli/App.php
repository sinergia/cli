<?php

namespace Sinergia\Cli;

use KevinGH\Amend\Command as AmendCommand;
use KevinGH\Amend\Helper as AmendHelper;
use Stecman\Component\Symfony\Console\BashCompletion\CompletionCommand;
use Symfony\Component\Console\Application;
use FilesystemIterator;

class App extends Application
{
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

    public function setup()
    {
        $this->loadConfigFromComposer();
        $this->addSelfUpdateCommand();

        // eval `bin/cli _completion -g`
        $this->addCompletionCommand();
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
}
