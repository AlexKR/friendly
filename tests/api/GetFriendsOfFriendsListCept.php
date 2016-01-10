<?php
$I = new ApiTester($scenario);
$I->wantTo('Get friends of friends list');
$I->haveHttpHeader('Content-Type','application/json');
$I->sendGET('/users/14/friendsOfFriends/10', array());
$I->seeResponseCodeIs(200);
$I->seeResponseIsJson();