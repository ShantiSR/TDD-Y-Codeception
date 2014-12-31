<?php
 $I = new AcceptanceTester($scenario);
 $I->wantTo('verificar que el login del sitio funciona correctamente');
 $I->amOnPage('/login');
 $I->see('Login');
 $I->fillField('email', 'testuser@mail.com');
 $I->fillField('password', 'secret');
 $I->click('Login');
 $I->seeInCurrentUrl('/welcome');
 $I->see('Bienvenido testuser@mail.com');