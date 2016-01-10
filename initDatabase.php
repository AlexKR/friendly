<?php
require_once('config.php');
require_once('vendor/autoload.php');

use \Everyman\Neo4j\Client;
use \Everyman\Neo4j\Cypher;

$usersCount = 1000;

$client = new Client(NEO4J_HOST, NEO4J_PORT);
$client->getTransport()
    ->setAuth(NEO4J_LOGIN, NEO4J_PASSWORD);


$queryString = "CREATE (n:User { id : 1, name : 'Root User' })";
$query = new Cypher\Query($client, $queryString);
$query->getResultSet();

$queryString = "CREATE CONSTRAINT ON (n:User) ASSERT n.id IS UNIQUE";
$query = new Cypher\Query($client, $queryString);
$query->getResultSet();

$faker = Faker\Factory::create();

for ($i = 2; $i <= $usersCount; $i++) {
    $name = $faker->name;
    $queryString = "CREATE (n:User { id : {$i}, name : \"{$name}\" })";
    $query = new Cypher\Query($client, $queryString);
    $query->getResultSet();

    if (mt_rand(0, 4) > 1) {
        $toUserId = mt_rand(1, $i-1);
        $queryString = "MATCH (lft { id: {$i} }),(rgt { id: {$toUserId} })
                    CREATE UNIQUE (lft)-[r:friend]->(rgt)
                    RETURN r";

        $query = new Cypher\Query($client, $queryString);
        $query->getResultSet();
    } elseif (mt_rand(0, 4) > 3) {
        $toUserId = mt_rand(1, $i);
        $queryString = "MATCH (lft { id: {$i} }),(rgt { id: {$toUserId} })
                    CREATE UNIQUE (lft)-[r:requestFriendship]->(rgt)
                    RETURN r";

        $query = new Cypher\Query($client, $queryString);
        $query->getResultSet();
    }
}
