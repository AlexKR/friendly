<?php
$I = new ApiTester($scenario);
$I->wantTo('Get friends list');
$I->haveHttpHeader('Content-Type','application/json');
$I->sendGET('/users/14/friends', array());
$I->seeResponseCodeIs(200);
$I->seeResponseIsJson();