@extends('cms::layouts/auth')

@section('title')
	Recuperar Password ::
	@parent
@stop

@section('content')

	<div style="text-align: center;margin: 80px 0 0 10px "><img src="{{ Helpers::asset('packages/insomnia/cms/cms-res/assets/img/logo.png') }}" ></div>

	<form id="recover_account_form" class="form-signin" onkeypress="return event.keyCode != 13;" method="POST">

		<h2 class="form-signin-heading">Recuperar Password</h2>
		<div class="login-wrap">
			@if(Session::get('success'))
				{{ Session::get('success') }}
			@else
			{{ $errors->first('email', '<p class="help-block">:message</p>') }}
			<input type="text" class="form-control" placeholder="Email" name="email" autofocus>
			<label class="checkbox">
				<span class="pull-right"> <a href="/cms/auth/signin"><i class="fa fa-arrow-circle-left"></i> Voltar à Autenticação</a></span>
			</label>
			<button class="btn btn-lg btn-login btn-block" type="submit">Recuperar</button>
			@endif

		</div>
		{{ Form::token() }}
	</form>

@stop
