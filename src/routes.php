<?php


$app->group('/users/{userId:[0-9]+}', function () {
    $this->get('/friends', '\App\Controller\FriendshipController:getFriendsList');
    $this->get('/friendsOfFriends', '\App\Controller\FriendshipController:getFriendsOfFriendsList');
    $this->get('/friendsOfFriends/{depth:[0-9]+}', '\App\Controller\FriendshipController:getFriendsOfFriendsList');

    $this->get('/friendshipRequests', '\App\Controller\FriendshipController:getRequestsList');

    $this->post('/friendshipRequest', '\App\Controller\FriendshipController:requestFriendship');
    $this->post('/acceptFriendshipRequest', '\App\Controller\FriendshipController:acceptFriendshipRequest');
    $this->post('/declineFriendshipRequest', '\App\Controller\FriendshipController:declineFriendshipRequest');
});

$app->post('/users', '\App\Controller\FriendshipController:createUser');
