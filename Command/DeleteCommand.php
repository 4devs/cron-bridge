<?php
/**
 * Created by PhpStorm.
 * User: andrey
 * Date: 14/05/16
 * Time: 14:08.
 */
namespace FDevs\Bridge\Cron\Command;

use FDevs\Cron\CrontabUpdater;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DeleteCommand extends Command
{
    /**
     * @var CrontabUpdater
     */
    private $updater;

    /**
     * {@inheritdoc}
     */
    public function __construct($name, CrontabUpdater $updater)
    {
        parent::__construct($name);
        $this->updater = $updater;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if ($this->updater->delete() && $output->isVerbose()) {
            $output->writeln(sprintf('<info>crontab with key %s delete</info>', $this->updater->getKey()));
        }
    }
}
