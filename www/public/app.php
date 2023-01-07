<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__.'/../vendor/autoload.php';

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Loader\FilesystemLoader;

$username = 'zz3f3';
$password = 'easyma';
$server = 'localhost';

// twig configuration
$loader = new FilesystemLoader('../templates');
$twig = new Environment($loader);

// get data from mongodb
// connection
$client = new MongoDB\Client("mongodb://$username:$password@$server");
$manager = $client->selectDatabase('test');
// @todo implementez la récupération des données dans la variable $list
// petite aide : https://github.com/VSG24/mongodb-php-examples
$list = [['name' => 'test']];

// render template
try {
    echo $twig->render('index.html.twig', ['list' => $list]);
} catch (LoaderError|RuntimeError|SyntaxError $e) {
    echo $e->getMessage();
}



