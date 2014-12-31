<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Login</title>
</head>
<body>
	<h1>Login</h1>
	 {{ Form::open(['route' => 'sessions.store']) }}
		 {{Form::label('email', 'Email: ')}}
		 {{Form::text('email')}} </br>
		 {{Form::label('password', 'Password: ')}}
		 {{Form::password('password')}}</br>
		 {{Form::submit('Login')}}
	 {{ Form::close() }}
</body>
</html>