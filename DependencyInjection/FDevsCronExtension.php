<?php

namespace FDevs\Bridge\Cron\DependencyInjection;

use FDevs\Cron\Cron;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

class FDevsCronExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $config = $this->processConfiguration(new Configuration(), $configs);
        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));

        $loader->load('services.xml');
        $exporter = $config['exporter'];
        $exporter['path'] .= ':'.realpath($container->getParameter('kernel.root_dir').'/..');

        $this->configureCron($container->getDefinition('f_devs_cron.cron'), $exporter);
        $container->setParameter($this->getAlias().'.key', $config['exporter']['key']);

        foreach ($config['commands'] as $name => $command) {
            $container->setDefinition($this->getAlias().'.job.'.$name, $this->createJob($command, $exporter, $name, $container));
        }
    }

    /**
     * @param Definition $cron
     * @param array      $exporter
     *
     * @return Definition
     */
    protected function configureCron(Definition $cron, array $exporter)
    {
        if (isset($exporter['shell'])) {
            $cron->addMethodCall('addHeader', [Cron::HEADER_SHELL, $exporter['shell']]);
        }
        if (isset($exporter['mailto'])) {
            $cron->addMethodCall('addHeader', [Cron::HEADER_MAILTO, $exporter['mailto']]);
        }
        $cron->addMethodCall('addHeader', [Cron::HEADER_PATH, $exporter['path']]);

        return $cron;
    }

    /**
     * @param array            $config
     * @param array            $exporter
     * @param string           $name
     * @param ContainerBuilder $container
     *
     * @return Definition
     */
    private function createJob(array $config, array $exporter, $name, ContainerBuilder $container)
    {
        $output = new Definition('FDevs\Cron\Model\Output');
        if (isset($config['log_file'])) {
            $output->addMethodCall('setOutFile', $config['log_file']);
        }
        if (isset($config['error_file'])) {
            $output->addMethodCall('setErrFile', $config['error_file']);
        }
        $output->setPublic(false);
        $outputName = $this->getAlias().'.job_output.'.$name;
        $container->setDefinition($outputName, $output);

        $time = new Definition('FDevs\Cron\Model\Time');
        $time
            ->setPublic(false)
            ->addMethodCall('setMinute', [$config['minute']])
            ->addMethodCall('setHour', [$config['hour']])
            ->addMethodCall('setDay', [$config['day']])
            ->addMethodCall('setMonth', [$config['month']])
            ->addMethodCall('setDayOfWeek', [$config['day_of_week']])
        ;
        $timeName = $this->getAlias().'.job_time.'.$name;
        $container->setDefinition($timeName, $time);

        $job = new Definition('FDevs\Cron\Model\Job', [
            $this->getCommand($config, $exporter),
            new Reference($timeName),
            new Reference($outputName),
        ]);
        $job->addTag($this->getAlias().'.job');

        return $job;
    }

    /**
     * @param array $config
     * @param array $exporter
     *
     * @return string
     */
    private function getCommand(array $config, array $exporter)
    {
        $command = (isset($config['executor']) ? $config['executor'] : $exporter['executor']).' ';
        $command .= isset($config['executor']) ? '' : $exporter['console'].' ';
        $command .= $config['command'].' '.$config['params'];

        return $command;
    }
}
