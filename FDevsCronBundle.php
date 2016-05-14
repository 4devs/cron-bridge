<?php

namespace FDevs\Bridge\Cron;

use FDevs\Bridge\Cron\DependencyInjection\Compiler\CronJobPass;
use FDevs\Bridge\Cron\DependencyInjection\FDevsCronExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class FDevsCronBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new CronJobPass());
    }

    /**
     * {@inheritdoc}
     */
    protected function createContainerExtension()
    {
        return new FDevsCronExtension();
    }
}
