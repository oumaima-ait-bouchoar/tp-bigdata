<?php

// On inclut le fichier d'initialisation
include_once __DIR__ . '/../init.php';

// Fonction pour indexer un livre dans ElasticSearch
function indexBookInElasticSearch($book) {
    // Assurez-vous que le client ElasticSearch est bien initialisé
    $client = getElasticSearchClient();

    // Prépare les paramètres d'indexation
    $params = [
        'index' => 'books',  // Nom de l'index ElasticSearch où les livres seront stockés
        'id' => $book['_id'], // ID du livre (ici on utilise l'ID MongoDB comme identifiant unique)
        'body' => [           // Corps du document (les informations du livre)
            'titre' => $book['titre'],
            'auteur' => $book['auteur'],
            'edition' => $book['edition'],
            'langue' => $book['langue'],
            'cote' => $book['cote'],
            'siecle' => $book['siecle'],
            'objectid' => $book['objectid'],
        ],
    ];

    // Indexer le livre dans ElasticSearch
    try {
        $response = $client->index($params);
        echo "Livre indexé avec succès : " . $book['titre'] . "\n";
    } catch (Exception $e) {
        echo "Erreur lors de l'indexation du livre : " . $book['titre'] . "\n";
        echo "Message d'erreur : " . $e->getMessage() . "\n";
    }
}

// Récupérer tous les livres de MongoDB
function getBooksFromMongoDB() {
    // Créez une connexion MongoDB en utilisant la configuration du fichier .env
    $manager = getMongoDbManager();
    $collection = $manager->selectCollection('tp');

    try {
        // Récupérer tous les documents de la collection "livres"
        $cursor = $collection->find();

        // Retourner les résultats sous forme de tableau
        return iterator_to_array($cursor);
    } catch (MongoDB\Driver\Exception\Exception $e) {
        echo "Erreur lors de la récupération des livres depuis MongoDB : " . $e->getMessage() . "\n";
        return [];
    }
}

// Main : Récupérer les livres et les indexer
echo "Début de l'indexation des livres...\n";

// Récupérer les livres depuis MongoDB
$books = getBooksFromMongoDB();

// Indexer chaque livre dans ElasticSearch
foreach ($books as $book) {
    indexBookInElasticSearch($book);
}

echo "Fin de l'indexation des livres.\n";

return 1;

