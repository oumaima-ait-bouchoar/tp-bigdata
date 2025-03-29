<?php

include_once '../init.php';

use MongoDB\BSON\ObjectId;

$twig = getTwig();
$manager = getMongoDbManager();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $title = $_POST['title'];
    $author = $_POST['author'];
    $century = $_POST['century'];


    $collection = $manager->selectCollection('tp');

    try {
        $objectId = new ObjectId($id);

        // Perform the update
        $updateResult = $collection->updateOne(
            ['_id' => $objectId],
            ['$set' => [
                'titre' => $title,
                'auteur' => $author,
                'siecle' => $century
            ]]
        );

        if ($updateResult->getModifiedCount() > 0) {
            header('Location: /index.php');
            exit;
        } else {
            echo "No changes were made to the document.";
        }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "Invalid request method.";
}
