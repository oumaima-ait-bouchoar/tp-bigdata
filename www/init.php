<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__.'/vendor/autoload.php';
// require 'vendor/autoload.php';
use Elasticsearch\ClientBuilder;



use MongoDB\Database;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

// env configuration
(Dotenv\Dotenv::createImmutable(__DIR__))->load();

function getTwig(): Environment
{
    // twig configuration
    return new Environment(new FilesystemLoader('../templates'));
}

function getMongoDbManager(): Database
{
    $client = new MongoDB\Client("mongodb://{$_ENV['MDB_USER']}:{$_ENV['MDB_PASS']}@{$_ENV['MDB_SRV']}:{$_ENV['MDB_PORT']}");
    return $client->selectDatabase($_ENV['MDB_DB']);
}

function getRedisClient(): ?Redis
{
    $redisEnable = filter_var($_ENV['REDIS_ENABLE'], FILTER_VALIDATE_BOOLEAN);

    if ($redisEnable) {
        $redisHost = $_ENV['REDIS_HOST'];
        $redisPort = $_ENV['REDIS_PORT'];

        try {
            $redis = new Redis();
            $redis->connect($redisHost, $redisPort);

            return $redis;
        } catch (Exception $e) {
            echo "Erreur de connexion Redis : " . $e->getMessage();
            return null;
        }
    } else {
        return null;
    }
}

function convertObjectIdToString(array $documents): array {
    foreach ($documents as &$doc) {
        if (isset($doc['_id']) && $doc['_id'] instanceof MongoDB\BSON\ObjectId) {
            $doc['_id'] = (string)$doc['_id'];
        }
    }
    return $documents;
}


// Mettre en cache une donnée
function setCache(string $key, $value, int $expiration = 3600): bool
{
    $redis = getRedisClient();
    if ($redis) {
        return $redis->setex($key, $expiration, json_encode($value));
    }
    return false;
}

// Récupérer une donnée du cache
function getCache(string $key)
{
    $redis = getRedisClient();
    if ($redis) {
        $data = $redis->get($key);
        if ($data) {
            return json_decode($data, true);
        }
    }
    return null;
}




function getElasticSearchClient() {
    // Chargez les variables d'environnement à partir du fichier .env
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();

    // Récupérez les informations de configuration d'ElasticSearch à partir du fichier .env
    $elasticHost = $_ENV['ELASTIC_HOST'];
    $elasticPort = $_ENV['ELASTIC_PORT'];





if (empty($elasticHost) || empty($elasticPort)) {
    die("Les variables d'environnement pour Elasticsearch ne sont pas définies correctement.\n");
}



    // echo "Hôte Elasticsearch : $elasticHost\n";
    // echo "Port Elasticsearch : $elasticPort\n";
    // Créez une instance du client ElasticSearch
    $client =Elastic\Elasticsearch\ClientBuilder::create()
        ->setHosts(["$elasticHost:$elasticPort"])  
        ->build();

    return $client;
}
