@extends('cms::layouts/auth')

@section('title')
	CMS LOGIN ::
@parent
@stop

@section('content')

	<div style="text-align: center;margin: 80px 0 0 10px "><img src="{{ Helpers::asset('packages/insomnia/cms/cms-res/assets/img/logo.png') }}" ></div>

	<form class="form-signin" action="{{ URL::to('cms/auth/signin') }}" method="POST">

	    <h2 class="form-signin-heading">Autenticação</h2>
	    <div class="login-wrap">
	    	{{ $errors->first('username', '<p class="help-block">:message</p>') }}
	        <input type="text" class="form-control" placeholder="ID Utilizador" name="username" autofocus>

	        {{ $errors->first('password', '<p class="help-block">:message</p>') }}
	        <input type="password" class="form-control" placeholder="Password" name="password">
	        <label class="checkbox">
	            <input type="checkbox" name="remember" value=""> Lembrar-me
	            <span class="pull-right"> <a href="/cms/auth/forgot-password"> Esqueceu-se da password?</a></span>
	        </label>
	        <button class="btn btn-lg btn-login btn-block" type="submit">Entrar</button>

	    </div>
	    {{ Form::token() }}
	  </form>

@stop
