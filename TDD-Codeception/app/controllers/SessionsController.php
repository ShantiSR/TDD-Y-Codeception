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
        $email = Input::get('email');
        $password = Input::get('password');
        if(Auth::attempt(['email' => $email, 'password' => $password])){
            return Redirect::route('welcome');
        }

        return Redirect::route('login');
    }
    //crea la sessión
    public function welcome()
    {
        return View::make('welcome')->withUser(Auth::user());
    }
 }