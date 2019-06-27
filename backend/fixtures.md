

# How to use efficiently use fixtures in your PhpUnit tests


## Load fixtures in your setup

- reprendre l'article précédent
- loader les fixtures avec Hautelook alice -> (en fait nelmio alice)
- configure purge mode ? -> delete , or truncate (expliquer la différence). Mais pas besoin car ...

## Improve your test performance with doctrine test bundle

[Doctrine Test Bundle](https://packagist.org/packages/dama/doctrine-test-bundle)

--> transaction before every testcase and rolled back after the test

- `composer require --dev dama/doctrine-test-bundle`

- How to setup in phpunit.xml


```php
    class PHPUnitListener extends \PHPUnit\Framework\BaseTestListener
    {
        public function startTest(\PHPUnit\Framework\Test $test)
        {
            StaticDriver::beginTransaction();
        }
        public function endTest(\PHPUnit\Framework\Test $test, $time)
        {
            StaticDriver::rollBack();
        }
        public function startTestSuite(\PHPUnit\Framework\TestSuite $suite)
        {
            StaticDriver::setKeepStaticConnections(true);
        }
    }
```



```xml
<?xml version="1.0" encoding="UTF-8"?>

<phpunit xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
         xsi:noNamespaceSchemaLocation="http://schema.phpunit.de/6.5/phpunit.xsd"
         backupGlobals="false"
         colors="true"
         bootstrap="tests/phpunit.bootstrap.php"
>
    <php>
        <ini name="error_reporting" value="-1" />
        <server name="APP_ENV" value="test" force="true" />
        <server name="SHELL_VERBOSITY" value="-1" />
    </php>

    <testsuites>
        <testsuite name="Project Test Suite">
            <directory>tests</directory>
        </testsuite>
    </testsuites>

    <listeners>
        <listener class="Symfony\Bridge\PhpUnit\SymfonyTestsListener" />
        <listener class="\DAMA\DoctrineTestBundle\PHPUnit\PHPUnitListener" />
    </listeners>
</phpunit>

```

-> theofidry configuration : no effect

## How to load global fixtures used by all tests

Dans le bootstrap -> loader les fixtures globales

```php
<?php

use Symfony\Component\Dotenv\Dotenv;

require dirname(__DIR__).'/vendor/autoload.php';

(new Dotenv())->loadEnv(dirname(__DIR__).'/.env');

passthru('bin/console hautelook:fixtures:load --env=test --no-interaction');

```


Config dans `config/packages/test/hautelook_alice.yaml`
```yaml
hautelook_alice:
    fixtures_path: 'tests/fixtures/globalFixtures'
```
