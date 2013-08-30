<?php

namespace Sinergia\Cli;

use Symfony\Component\Console\Command\ListCommand;

/**
 * Replaces default list command with a usage command
 * Class UsageCommand
 * @package Sinergia\Cli
 */
class UsageCommand extends ListCommand
{
    public function configure()
    {
        parent::configure();
        $this->setName("usage");
        $this->setDescription('Show this help');
    }
}
