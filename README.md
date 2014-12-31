Laravel, TDD Y Codeception
=================

## Instalación de Codecepcion

Nos paramos en el directorio raíz de nuestra aplicación y ejecutamos:

*composer require --dev "codeception/codeception:2.0.*”*

Este comando nos traerá las dependencias necesarias. Codeception quedará en nuestra carpeta vendor y el ejecutable principal se encontrará en vendor/bin/codecept.

Una vez que se ha completado la descarga, ejecutamos el siguiente comando para crear la estructura necesaria para trabajar con codeception. En nuestro directorio raíz ejecutamos

*./vendor/bin/codecept bootstrap*

Este comando nos creará un archivo de configuración llamado codeception.yml y un directorio tests en cual estarán nuestras distintas pruebas, ya sean de aceptación o unitarias.

## Test de prueba

Para verificar que todo esté funcionando correctamente crearemos un test de ejemplo. El siguiente comando nos creará un test llamado Welcome y será de aceptación (suite acceptance).

*./vendor/bin/codecept generate:cept acceptance Welcome*

Este test será creado en la ruta *tests/acceptance/WelcomeCept.php*

Tendremos que editarlo para verificar el funcionamiento correcto de codeception. Añadir el siguiente código en el archivo:

<?php
$I = new WebGuy($scenario);
$I->wantTo('verificar que la ruta home funciona');
$I->amOnPage('/');
$I->see('Home');

Este es nuestro primer test de aceptación, bastante simple. Estamos creando una instancia de la clase AcceptanceTester, la cual nos entregará métodos para hacer pruebas que nos servirán para  la parte web de nuestra aplicación. El método *wantTo* nos sirve para declarar el propósito de la prueba. Luego el método amOnPage nos sirve para simular una petición hacía la ruta que le entregamos como parámetro. En este test estamos pidiendo que simule una petición a la ruta home y luego verificar que se muestre el string ‘Home’ en alguna parte en la página (método WebGuy::see).

Ahora debemos configurar la suite de aceptación para indicarles donde se encuentra nuestro servidor, hacia el cual se realizarán las peticiones. Lo más simple es ejecutar nuestra aplicación laravel usando php artisan serve y luego configurar la suite para que apunte a este. Como sabemos este servidor corre en localhost en el puerto 8000 (por defecto). La configuración de la suite de aceptación se encuentra en tests/acceptance.suite.yml y debe quedar de la siguiente manera:

class_name: WebGuy
modules:
    enabled:
        - PhpBrowser
        - WebHelper
    config:
        PhpBrowser:
            url: 'http://localhost:8000/'

Como vez hemos configurado la propiedad PhpBrowser para que apunte a nuestro servidor. Para ejecutar la prueba, desde el directorio raíz ejecutamos:

*./vendor/bin/codecept run*

Luego de ejecutarlo veremos que funciona correctamente:

*OK (1 test, 1 assertion)*

Así funciona la lógica de TDD, vamos creando primero las pruebas, luego vamos construyendo nuestro código para que las pruebas pasen. Una vez que la prueba pasa correctamente, pasamos a refactorizarla.

## Primera prueba real, página de login

Usando la metodología TDD vamos a construir una simple página de login. Para generar la prueba usamos el mismo comando anterior y ejecutamos:

*./vendor/bin/codecept generate:cept acceptance Login*

Esto nos generará el archivo LoginCept.php en el directorio tests/acceptance.
Vamos a editar este archivo y colocar algunas funciones para testear una página de login.

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

Vamos por parte, en esta prueba lo primero que hacemos (y que es típico en las pruebas de aceptación con codeception) es crear una instancia de AcceptanceTester, la clase que nos entrega las funciones para este tipo de pruebas. Luego declaramos el propósito del test. Luego usando la función ::amOnPage, nos dirigimos la url ‘/login’ de nuestro sitio. Usando ::see declaramos que vemos el string ‘Login’ en alguna parte del sitio. Luego usando los métodos ::fillField y ::click podemos llenar fácilmente el formulario de login (aún inexistente) y hacer click en el botón de Login. Cuando usamos fillField debemos entregar como parámetro el nombre del campo de texto, similar con el botón de Login.

Una vez apretado el botón de login, la aplicación debe redirigirnos  a la ruta ‘/welcome’ y para eso usamos la función ::seeInCurrentUrl y además debemos mostrar el mensaje ‘Bienvenido testuser’.

Vamos a crear la ruta, el controlador y la vista para manejar el login.

En routes.php creamos un resource para manejar las sesiones y además una ruta login que nos mostrará el formulario

En controllers/SessionController.php creamos las 2 acciones necesarias, una para mostrar el fomulario de login, y otra para guardar la sesión:

<?php

class SessionsController extends BaseController{

	 public function create()
	 {
	 	//muestra el form de login
	 	return View::make('login');
	 }

	public function store()
	 {
	 	//crea la sessión
	 }
 }

En views/login.blade.php creamos un formulario simple que envíe la información a la ruta store del controlador de sesiones y que además muestre un string que diga ‘Login':

<h1>Login</h1>
	 {{ Form::open(['route' => 'sessions.store']) }}
		 {{Form::label('email', 'Email: ')}}
		 {{Form::text('email')}} </br>
		 {{Form::label('password', 'Password: ')}}
		 {{Form::password('password')}}</br>
		 {{Form::submit('Login')}}
	{{ Form::close() }}

Si ahora ejecutamos nuevamente la prueba (./vendor/bin/codecept run) veremos el siguiente resultado:

Scenario Steps:
6. I see in current url "/welcome"
5. I click "Login"
4. I fill field "password","secret"
3. I fill field "email","testuser@mail.com"
2. I see "Login"
1. I am on page "/login"

La parte en rojo nos indica donde está el error. Del paso 1 al 5 todo está correcto. La prueba falla ya que no estamos redirigiendo a la url ‘/welcome’. Arreglamos eso haciendo un simple redirect en la acción login. Para hacerlo más realista, vamos también a crear una sesión real ocupando la clase Auth de Laravel. Como esta clase está bien testeada en el core del framework, podemos saltarnos el test que verifique su funcionalidad y centrarnos en lo que nos interesa a nosotros, que es redirigir a la página welcome con el nombre del usuario que se está logueando.

Primero vamos a crear un usuario con el cual podamos ingresar.



