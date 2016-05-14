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

#### default configuration

```yml
# Default configuration for extension with alias: "f_devs_cron"
f_devs_cron:
    exporter:
        key:                  f_devs_cron # Example: generated
        mailto:               ~ # Example: cron@example.com
        path:                 '/usr/local/bin:/usr/bin:/bin' # Example: /usr/local/bin:/usr/bin:/bin
        executor:             php # Example: php
        console:              bin/console # Example: bin/console(symfony 3.0)
        shell:                ~ # Example: /bin/sh
    commands:

        # Prototype
        name:
            command:              ~ # Required, Example: swiftmailer:spool:send
            minute:               '*' # Example: */5 - Every 5 minutes
            hour:                 '*' # Example: 8 - 5 minutes past 8am every day
            day_of_week:          '*' # Example: 0 - 5 minutes past 8am every Sunday
            day:                  '*' # Example: 1 -  5 minutes past 8am on first of each month
            month:                '*' # Example: 1 - 5 minutes past 8am on first of of January
            log_file:             ~ # Example: %kernel.logs_dir%/%kernel.environment%_cron.log
            error_file:           ~ # Example: %kernel.logs_dir%/%kernel.environment%_error.log
            params:               '' # Example: --color=red

            # add if use custom executor
            executor:             ~ # Example: /usr/bin/php
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
