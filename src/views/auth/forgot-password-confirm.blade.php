@extends('frontend/layouts/default')

{{-- Page title --}}
@section('title')
Recuperar Password ::
@parent
@stop

{{-- Page content --}}
@section('content')

	<div id="page-account" class="page-inside">
		<div class="page-title"><h1 class="title">RECUPERAR PASSWORD</h1></div>
		<form action="/ocms/auth/forgot-password/{{ $passwordResetCode }}" id="reset_account_form" class="general-form" onkeypress="return event.keyCode != 13;">

			<div class='form_item'>{{ Form::label('password','Password') }}{{ Form::password('password','',array('style'=>'width:150px')) }}<div class='field_error'></div><span class="required">*</span></div>
				<div class='form_item'>{{ Form::label('password_confirm','Confirme Password') }}{{ Form::password('password_confirm','',array('style'=>'width:150px')) }}<div class='field_error'></div><span class="required">*</span></div>

			{{ Form::token() }}
			<span class="bt button-blue unselectable">Confirmar</span><span class="msg-form"></span>
			<div class="clear"></div>
		</form>
	</div>

@stop
