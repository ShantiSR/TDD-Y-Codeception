<?php
$I = new AcceptanceTester($scenario);
$I->wantTo('verificar que la ruta home funciona');
$I->amOnPage('/');
$I->see('You have arrived');