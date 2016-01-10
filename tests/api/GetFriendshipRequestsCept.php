<?php
$I = new ApiTester($scenario);
$I->wantTo('Get friendships requests');
$I->haveHttpHeader('Content-Type','application/json');
$I->sendGET('/users/14/friendshipRequests', array());
$I->seeResponseCodeIs(200);
$I->seeResponseIsJson();