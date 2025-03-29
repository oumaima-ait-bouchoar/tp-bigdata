<?php

include_once '../init.php';

use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

$twig = getTwig();
$manager = getMongoDbManager();
$collection = $manager->selectCollection('tp');

// Pagination
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 10;
$search = isset($_GET['search']) ? $_GET['search'] : '';
$offset = ($page - 1) * $limit;

$list = null;
$totalCount = null;
$totalPages = null;

$redis = ($_ENV["REDIS_ENABLE"]) ? getRedisClient() : null;
$cacheKeyPage30 = 'page_books_30';

if ($redis && $page == 30) {
    $cachedPage30 = $redis->get($cacheKeyPage30);
    if ($cachedPage30) {
        $list = json_decode($cachedPage30, true);
    }
}

if (!$list) {
    if ($search) {
        // Cas avec recherche : on interroge Elasticsearch
        $client = getElasticSearchClient();
        
        // Recherche dans Elasticsearch
        $params = [
            'index' => 'books', // Le nom de ton index Elasticsearch
            'body' => [
                'query' => [
                    'bool' => [
                        'should' => [
                            [
                                'match' => [
                                    'titre' => [
                                        'query' => $search,
                                        'fuzziness' => 'AUTO'  // Tolérance aux fautes de frappe
                                    ]
                                ]
                            ],
                            [
                                'match' => [
                                    'auteur' => [
                                        'query' => $search,
                                        'fuzziness' => 'AUTO'  // Tolérance aux fautes de frappe
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];

        // Effectuer la recherche dans Elasticsearch
        $response = $client->search($params);

        // Récupérer les résultats de la recherche
        if (isset($response['hits']['hits'])) {
            $totalCount = count($response['hits']['hits']);
            $list = array_slice($response['hits']['hits'], $offset, $limit);
            $list = array_map(fn($hit) => $hit['_source'], $list); // On garde seulement les données des livres
        }
    } else {
        // Cas sans recherche : on récupère tous les livres depuis MongoDB
        $filter = [];
        $options = [
            'skip' => $offset,
            'limit' => $limit
        ];

        $list = $collection->find($filter, $options)->toArray();
        $totalCount = $collection->countDocuments($filter);
    }

    // Stocker la page 30 en cache pour 1 heure
    if ($redis && $page == 30 && $list) {
        $redis->setex($cacheKeyPage30, 3600, json_encode($list));
    }
}

if ($totalCount === null) {
    $totalCount = $collection->countDocuments([]);
}

$totalPages = ceil($totalCount / $limit);

// Afficher la page avec Twig
try {
    echo $twig->render('index.html.twig', [
        'list' => $list,
        'page' => $page,
        'totalPages' => $totalPages,
        'search' => $search,
    ]);
} catch (LoaderError|RuntimeError|SyntaxError $e) {
    echo $e->getMessage();
}
