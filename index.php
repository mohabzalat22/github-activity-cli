#!/usr/bin/env php
<?php

require __DIR__ . "/vendor/autoload.php";

use Console\Kernel;

$kernel = new Kernel();

$kernel->run($argv);
