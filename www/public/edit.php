<?php

include_once '../init.php';

use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

$twig = getTwig();
$manager = getMongoDbManager();

// @todo afficher un formulaire de modification à l\'aide de twig et retournez sur la liste

$id = $_GET['id'];
$collection = $manager->selectCollection('tp');
$entity = $collection->findOne(['_id' => new MongoDB\BSON\ObjectId($id)]);


try {
    echo $twig->render('update.html.twig', ['entity' => $entity]);
} catch (LoaderError|RuntimeError|SyntaxError $e) {
    echo $e->getMessage();
}

