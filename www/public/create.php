<?php
/*
include_once '../init.php';

use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

$twig = getTwig();
$manager = getMongoDbManager();

// petite aide : https://github.com/VSG24/mongodb-php-examples

if (!empty($_POST)) {
    // @todo coder l'enregistrement d'un nouveau livre en lisant le contenu de $_POST
    $title = $_POST['title'];
    $author = $_POST['author'];
    $century = $_POST['century'];

    $user = [
        'title' => $title,
        'author' => $author,
        'century' => $century,
    ];
    $manager->selectCollection('tp')->insertOne($user);


} else {
// render template
    try {
        echo $twig->render('create.html.twig');
    } catch (LoaderError|RuntimeError|SyntaxError $e) {
        echo $e->getMessage();
    }
}

*/


include_once '../init.php';

use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

$twig = getTwig();
$manager = getMongoDbManager();
$collection = $manager->selectCollection('tp'); // Assurez-vous que la collection existe

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Vérifier si les champs sont bien envoyés
    if (!empty($_POST['title']) && !empty($_POST['author']) && isset($_POST['century'])) {
        $title = trim($_POST['title']);
        $author = trim($_POST['author']);
        $century = (int) $_POST['century']; // S'assurer que c'est bien un nombre

        // Création du livre
        $book = [
            'titre' => $title,
            'auteur' => $author,
            'siecle' => $century,
        ];

        try {
            // Insertion du livre dans la base MongoDB
            $insertResult = $collection->insertOne($book);

            if ($insertResult->getInsertedCount() > 0) {
                // Récupérer l'ID généré pour ce livre
                $bookId = $insertResult->getInsertedId();

                // Convertir l'ID en chaîne de caractères pour l'envoyer dans la réponse
                $response = [
                    'status' => 'success',
                    'message' => 'Livre ajouté avec succès',
                    'id' => (string)$bookId // L'ID du livre
                ];

                // Retourner une réponse JSON contenant l'ID du livre
                header('Content-Type: application/json');
                echo json_encode($response);
                exit();
            } else {
                $error = "Erreur lors de l'ajout du livre.";
            }
        } catch (Exception $e) {
            $error = "Exception : " . $e->getMessage();
        }
    } else {
        $error = "Veuillez remplir tous les champs.";
    }
}

// Render template avec gestion des erreurs
try {
    echo $twig->render('create.html.twig', [
        'error' => $error ?? null,
    ]);
} catch (LoaderError|RuntimeError|SyntaxError $e) {
    echo $e->getMessage();
}
