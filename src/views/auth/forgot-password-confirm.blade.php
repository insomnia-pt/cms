@extends('cms::layouts/auth')

@section('title')
	Recuperar Password ::
	@parent
@stop

@section('content')

	<div style="text-align: center;margin: 80px 0 0 10px "><img src="{{ Helpers::asset('packages/insomnia/cms/cms-res/assets/img/logo.png') }}" ></div>

	<form action="/cms/auth/forgot-password/{{ $passwordResetCode }}" id="reset_account_form" class="form-signin" onkeypress="return event.keyCode != 13;" method="POST">

		<h2 class="form-signin-heading">Nova Password</h2>
		<div class="login-wrap">
			@if(Session::get('success'))
				{{ Session::get('success') }}
			@else
				{{ $errors->first('password', '<p class="help-block">:message</p>') }}
				<input type="password" class="form-control" placeholder="Password" name="password" autofocus>
				{{ $errors->first('password_confirm', '<p class="help-block">:message</p>') }}
				<input type="password" class="form-control" placeholder="Confirmar Password" name="password_confirm" autofocus>

				<button class="btn btn-lg btn-login btn-block" type="submit">Confirmar</button>
			@endif

		</div>
		{{ Form::token() }}
	</form>

@stop
