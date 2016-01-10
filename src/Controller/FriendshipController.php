<?php

namespace App\Controller;

use \Slim\Http\Request as Request;
use \Slim\Http\Response as Response;

final class FriendshipController extends AbstractController
{
    /**
     * Called for routes of type class:method.
     * Routes of type class::method are called statically.
     * @param \Slim\Container $container
     */
    public function __construct($container)
    {
        parent::__construct($container);
    }

    /**
     * action is route handler, and in this example $this->log uses the getter
     * from AbstractController to locate 'log' in our DI container.
     * @param Request $req http request
     * @param Response $res http response
     * @throws \InvalidArgumentException
     * @return Response
     */
    public function getRequestsList(Request $req, Response $res, $args)
    {
        $userId = (int)$args['userId'];
        $requests = $this->friendship->getFriendshipRequests($userId);

        return $this->view->render($res, $requests);
    }

    /**
     * @param Request $req
     * @param Response $res
     * @param $args
     * @throws \BadMethodCallException
     * @return Response
     */
    public function requestFriendship(Request $req, Response $res, $args)
    {
        $toUserId = (int)$args['userId'];
        $fromUserId = (int)$req->getParam('fromUserId');

        if (empty($fromUserId)) {
            throw new \BadMethodCallException("POST param 'fromUserId' is required");
        }

        $this->friendship->requestFriendship($fromUserId, $toUserId);

        return $res->withStatus(200);
    }

    /**
     * @param Request $req
     * @param Response $res
     * @param $args
     * @throws \BadMethodCallException
     * @return Response
     */
    public function acceptFriendshipRequest(Request $req, Response $res, $args)
    {
        $toUserId = (int)$args['userId'];
        $fromUserId = (int)$req->getParam('fromUserId');

        if (empty($fromUserId)) {
            throw new \BadMethodCallException("POST param 'fromUserId' is required");
        }

        $this->friendship->acceptFriendshipRequest($fromUserId, $toUserId);

        return $res->withStatus(200);
    }

    /**
     * @param Request $req
     * @param Response $res
     * @param $args
     * @throws \BadMethodCallException
     * @return Response
     */
    public function declineFriendshipRequest(Request $req, Response $res, $args)
    {
        $toUserId = (int)$args['userId'];
        $fromUserId = (int)$req->getParam('fromUserId');

        if (empty($fromUserId)) {
            throw new \BadMethodCallException("POST param 'fromUserId' is required");
        }

        $this->friendship->declineFriendshipRequest($fromUserId, $toUserId);

        return $res->withStatus(200);
    }

    /**
     * @param Request $req
     * @param Response $res
     * @param $args
     * @throws \InvalidArgumentException
     * @return Response
     */
    public function createUser(Request $req, Response $res, $args)
    {
        $newUser = [
            'id' => (int)$req->getParam('id'),
            'name' => $req->getParam('name'),
        ];

        //simple validation
        if (empty($newUser['id'])
            || !is_int($newUser['id'])
            || empty($newUser['name'])
        ) {
            throw new \InvalidArgumentException('Id and name are required');
        }

        $user = $this->friendship->createUser($newUser);

        return $this->view->render($res, $user);
    }

    /**
     * @param Request $req
     * @param Response $res
     * @param $args
     * @return Response
     */
    public function getFriendsList(Request $req, Response $res, $args)
    {
        $userId = (int)$args['userId'];

        $friendsList = $this->friendship->getFriendsList($userId);

        return $this->view->render($res, $friendsList);
    }

    /**
     * @param Request $req
     * @param Response $res
     * @param $args
     * @return Response
     */
    public function getFriendsOfFriendsList(Request $req, Response $res, $args)
    {
        $userId = (int)$args['userId'];
        $depth = !empty($args['depth']) ? (int)$args['depth'] : 1;

        $friendsList = $this->friendship->getFriendsOfFriendsList($userId, $depth);

        return $this->view->render($res, $friendsList);
    }
}
