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