<?php

namespace App\Service;

use App\Exception\FriendshipException;
use App\Exception\NotFoundException;
use \Everyman\Neo4j\Client;
use \Everyman\Neo4j\Cypher;
use App\entity\User;
use Everyman\Neo4j\Exception as Neo4jException;

class Friendship
{
    /**
     * @var Client
     */
    protected $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @param $user
     * @return \Everyman\Neo4j\Query\ResultSet
     * @throws FriendshipException
     */
    public function createUser($user)
    {
        $queryString = 'CREATE (user:User { id : {id}, name : {name}}) RETURN user';

        $result = $this->getQueryResult($queryString, $user);

        $result = $result[0];
        $user = new User();
        $user->setId($result->getProperty('id'));
        $user->setName($result->getProperty('name'));

        return $user;
    }

    /**
     * @param $fromId
     * @param $toId
     * @return bool
     * @throws FriendshipException
     */
    public function requestFriendship($fromId, $toId)
    {
        $relations = compact('fromId', 'toId');

        $queryString = "MATCH (lft { id: {fromId} }),(rgt { id: {toId} })
                    CREATE UNIQUE (lft)-[r:requestFriendship]->(rgt)
                    RETURN r";

        $result = $this->getQueryResult($queryString, $relations);
        return !empty($result);
    }

    /**
     * @param $fromId
     * @param $toId
     * @return bool
     * @throws FriendshipException
     */
    public function acceptFriendshipRequest($fromId, $toId)
    {
        $relations = compact('fromId', 'toId');

        $queryString = "MATCH (n:User {id: {fromId}})-[r:requestFriendship]->(m:User {id: {toId}})
                    CREATE UNIQUE (n)-[r2:friend]->(m)
                    DELETE r RETURN r2";

        $result = $this->getQueryResult($queryString, $relations);
        return !empty($result);
    }

    /**
     * @param $fromId
     * @param $toId
     * @return bool
     * @throws FriendshipException
     */
    public function declineFriendshipRequest($fromId, $toId)
    {
        $relations = compact('fromId', 'toId');

        $queryString = "MATCH (lft { id: {fromId} })-[r:requestFriendship]->(rgt { id: {toId} })
                        DELETE r RETURN lft";

        $result = $this->getQueryResult($queryString, $relations);
        return !empty($result);
    }

    /**
     * @param $userId
     * @return array
     * @throws FriendshipException
     */
    public function getFriendshipRequests($userId)
    {
        $queryString = "MATCH (user { id:{userId} })<-[:requestFriendship]-(users)
                        RETURN users";

        $rows = $this->getQueryResult($queryString, ['userId' => $userId]);

        $result = [];
        foreach ($rows as $row) {
            $user = new User();
            $user->setId($row[0]->getProperty('id'));
            $user->setName($row[0]->getProperty('name'));

            $result[] = $user;
        }

        return $result;
    }

    /**
     * @param $userId
     * @return array
     * @throws FriendshipException
     */
    public function getFriendsList($userId)
    {
        return $this->getFriendsOfFriendsList($userId, 1);
    }

    /**
     * @param $userId
     * @param $depth
     * @return array
     * @throws FriendshipException
     */
    public function getFriendsOfFriendsList($userId, $depth)
    {
        //hack with depth as php variable because Query has incorrect handling "*{something}"
        $queryString = "MATCH (user { id: {userId} })-[:friend*{$depth}]-(users)
                    RETURN DISTINCT(users)";

        $rows = $this->getQueryResult($queryString, ['userId' => $userId]);

        $result = [];
        foreach ($rows as $row) {
            $user = new User();
            $user->setId($row->getProperty('id'));
            $user->setName($row->getProperty('name'));

            $result[] = $user;
        }

        return $result;
    }

    /**
     * @param $queryString
     * @param $vars
     * @return array
     * @throws FriendshipException
     */
    public function getQueryResult($queryString, $vars)
    {
        $client = $this->getClient();

        $query = new Cypher\Query($client, $queryString, $vars);

        try {
            $rows = $query->getResultSet();
        } catch (Neo4jException $e) {
            throw new FriendshipException('Cannot execute Neo4j query: ' . $e->getMessage());
        }

        $result = [];
        foreach ($rows as $row) {
            $result[] = $row[0];
        }
        return $result;
    }

    /**
     * @return Client
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @param Client $client
     */
    public function setClient($client)
    {
        $this->client = $client;
    }
}
