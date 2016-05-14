<?php

namespace FDevs\Bridge\Cron\Command;

use FDevs\Cron\Cron;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DumpCommand extends Command
{
    private $cron;

    /**
     * {@inheritdoc}
     */
    public function __construct($name, Cron $cron)
    {
        parent::__construct($name);
        $this->cron = $cron;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->write(strval($this->cron));
    }
}
