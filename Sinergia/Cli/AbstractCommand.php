<?php

namespace Sinergia\Cli;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

abstract class AbstractCommand extends Command
{
    /**
     * @var OutputInterface
     */
    protected $out;

    /**
     * @var InputInterface
     */
    protected $in;

    /**
     * Custom Aliases
     * @var array
     */
    protected $aliases = array();

    protected function configure()
    {
        $this->setAliases($this->aliases);
        $this
            ->setName($this->autoName())
            ->setDescription($this->autoDescription());
    }

    protected function autoName()
    {
        $namespaces = explode("\\", get_called_class());

        return substr(strtolower(end($namespaces)), 0, -7);
    }

    protected function autoDescription()
    {
        $r = new \ReflectionClass(get_called_class());

        return trim($r->getDocComment(), "/* \r\n");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->out = $output;
        $this->in = $input;
        $this->exec();
    }

    abstract protected function exec();

    protected function info($msg)
    {
        $msg = trim($msg);
        $this->out->writeln("<info>$msg</info>");
    }

    protected function error($msg)
    {
        $msg = trim($msg);
        $this->out->writeln("<error>$msg</error>");
    }

    protected function comment($msg)
    {
        $msg = trim($msg);
        $this->out->writeln("<comment>$msg</comment>");
    }

    protected function LF()
    {
        $this->out->writeln("");
    }

    protected function isVerbose($level = OutputInterface::VERBOSITY_VERBOSE)
    {
        return $this->out->getVerbosity() >= $level;
    }
}
