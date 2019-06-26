<?php

use Symfony\Component\Dotenv\Dotenv;

require dirname(__DIR__).'/vendor/autoload.php';

(new Dotenv())->loadEnv(dirname(__DIR__).'/.env');

passthru('bin/console hautelook:fixtures:load --env=test --no-interaction');
