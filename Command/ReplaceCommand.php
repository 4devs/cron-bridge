<?php

namespace FDevs\Bridge\Cron\Command;

use FDevs\Cron\Cron;
use FDevs\Cron\CrontabUpdater;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ReplaceCommand extends Command
{
    /**
     * @var Cron
     */
    private $cron;

    /**
     * @var CrontabUpdater
     */
    private $updater;

    /**
     * {@inheritdoc}
     */
    public function __construct($name, Cron $cron, CrontabUpdater $updater)
    {
        parent::__construct($name);
        $this->cron = $cron;
        $this->updater = $updater;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if ($this->updater->update($this->cron) && $output->isVerbose()) {
            $output->writeln(sprintf('<info>crontab with key %s replaced</info>', $this->updater->getKey()));
        }
    }
}
