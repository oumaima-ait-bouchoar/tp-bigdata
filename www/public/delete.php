<?php
include_once '../init.php';
$manager = getMongoDbManager();
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $objectId = new MongoDB\BSON\ObjectId($id);
    $manager->selectCollection('tp')->deleteOne(['_id' => $objectId]);

    header("Location: http://localhost:8080/");
    exit;
}