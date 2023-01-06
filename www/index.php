<?php

require_once __DIR__.'/vendor/autoload.php';

$username = 'zz3f3';
$password = 'easyma';
$server =
$client = new MongoDB\Client('mongodb+srv://$username:$password@'.$_ENV['ATLAS_CLUSTER_SRV'].'/test');



