Cron
====

Easily configure cron through PHP.

## Setup and Configuration
FDevsCron uses Composer, please checkout the [composer website](http://getcomposer.org) for more information.

The simple following command will install `cron-bridge` into your project. It also add a new
entry in your `composer.json` and update the `composer.lock` as well.
```bash
$ composer require fdevs/cron-bridge
```

> FDevsCron follows the PSR-4 convention names for its classes, which means you can easily integrate `cron` classes loading in your own autoloader.

## Usage with [Symfony framework](http://symfony.com/)

###Enable the bundle in the kernel

```php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new FDevs\Bridge\Cron\FDevsCronBundle(),
        // ...
    );
}
```

###add command

```yml
#app/config/config.yml
f_devs_cron:
    commands:
        swiftmailer:
            command: 'swiftmailer:spool:sends'
        bin:
            executor: '/usr/bin/php'
            command: 'your_best_command'
```

###use console command

```bash
$ bin/console fdevs:cron:dump
$ bin/console fdevs:cron:replace
$ bin/console fdevs:cron:delete
```

## Usage with [The Console Component](http://symfony.com/doc/current/components/console/introduction.html)

```php
#!/usr/bin/env php
<?php
// application.php

require __DIR__.'/vendor/autoload.php';

use FDevs\Bridge\Cron\Command\DeleteCommand;
use FDevs\Bridge\Cron\Command\DumpCommand;
use FDevs\Bridge\Cron\Command\ReplaceCommand;
use FDevs\Cron\Cron;
use FDevs\Cron\CrontabUpdater;
use Symfony\Component\Console\Application;

$cron = new Cron()
// $cron configuration...

$crontabUpdater = new CrontabUpdater('uniquie_key');

$application = new Application();
$application->add(new ReplaceCommand('cron:replace', $cron, $crontabUpdater));
$application->add(new DumpCommand('cron:dump', $cron));
$application->add(new DeleteCommand('cron:delete', $crontabUpdater));
$application->run();
```

use in console

```bash
$ php application.php cron:replace
$ php application.php cron:dump
$ php application.php cron:delete
```

## Usage with [The DependencyInjection Component ](http://symfony.com/doc/current/components/dependency_injection/introduction.html)

```php
<?php
use Symfony\Component\DependencyInjection\ContainerBuilder;
use FDevs\Bridge\Cron\DependencyInjection\FDevsCronExtension;
use FDevs\Bridge\Cron\DependencyInjection\Compiler\CronJobPass;
$container = new ContainerBuilder();
// $container configuration...

$container->registerExtension(FDevsCronExtension());
$container->addCompilerPass(new CronJobPass());

$updater = $container->get('f_devs_cron.crontab_updater');
$cron = $container->get('f_devs_cron.cron');

$updater->replace($cron);
echo strval($cron);
```

---
Created by [4devs](http://4devs.pro/) - Check out our [blog](http://4devs.io/) for more insight into this and other open-source projects we release.
