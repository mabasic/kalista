<?php

require __DIR__ . '/vendor/autoload.php';

$filesystem = new \Illuminate\Filesystem\Filesystem();

//var_dump($filesystem->glob('*.php'));

var_dump(glob("/arena/mixed/*.{jpg,gif,png}", GLOB_BRACE));


