<?php
/*
include_once '../init.php';

use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

$twig = getTwig();
$manager = getMongoDbManager();

// @todo implementez la récupération des données d'une entité et la passer au template
// petite aide : https://github.com/VSG24/mongodb-php-examples

$id = $_GET['id'];
$search = $_GET['search'] ?? null;
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;

$entity = null;

if ($_ENV["REDIS_ENABLE"]) {
    $redis    = getRedisClient();
    $cacheKey = 'page_books_all_' . $page;
    if ($redis) {
        $cachedJson = $redis->get($cacheKey);
        if ($cachedJson) {
            // Décoder le JSON pour obtenir le tableau de résultats
            $results = json_decode($cachedJson, true);
            // Rechercher dans le tableau l'élément dont le _id correspond à l'ID reçu en GET
            foreach ($results as $doc) {
                if (isset($doc['_id']) && $doc['_id'] == $id) {
                    $entity = $doc;
                    break;
                }
            }
        }
    }
}

// Si on n'a pas trouvé le document dans le cache, interroger MongoDB directement
if (!$entity) {
    $collection = $manager->selectCollection('tp');
    $entity = $collection->findOne(['_id' => new MongoDB\BSON\ObjectId($id)]);
}

// render template
try {
    echo $twig->render('get.html.twig', [
        'entity' => $entity,
        'search' => $search,
        'page'   => $page
    ]);
} catch (LoaderError|RuntimeError|SyntaxError $e) {
    echo $e->getMessage();
}
*/

include_once '../init.php';

use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

$twig = getTwig();
$manager = getMongoDbManager();

// @todo implementez la récupération des données d'une entité et la passer au template
// petite aide : https://github.com/VSG24/mongodb-php-examples

$id = $_GET['id'];
$search = $_GET['search'] ?? null;
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;

$entity = null;

if ($_ENV["REDIS_ENABLE"]) {
    $redis    = getRedisClient();
    $cacheKey = 'book_' . $id; // Clé de cache pour un livre spécifique (par son ID)

    if ($redis) {
        // Vérifier si le livre spécifique est déjà dans le cache
        $cachedJson = $redis->get($cacheKey);
        if ($cachedJson) {
            // Décoder le JSON pour obtenir l'entité
            $entity = json_decode($cachedJson, true);
        }
    }
}

// Si l'entité n'est pas dans le cache, on interroge MongoDB
if (!$entity) {
    $collection = $manager->selectCollection('tp');
    $entity = $collection->findOne(['_id' => new MongoDB\BSON\ObjectId($id)]);

    // Mettre en cache l'entité si elle existe
    if ($entity && $_ENV["REDIS_ENABLE"] && $redis) {
        // Encoder l'entité en JSON et la mettre en cache avec une expiration (par exemple 3600 secondes)
        $redis->setex($cacheKey, 3600, json_encode($entity));
    }
}

// Render le template
try {
    echo $twig->render('get.html.twig', [
        'entity' => $entity,
        'search' => $search,
        'page'   => $page
    ]);
} catch (LoaderError|RuntimeError|SyntaxError $e) {
    echo $e->getMessage();
}