@extends('cms::layouts/auth')

@section('title')
	Registar ::
	@parent
@stop

{{-- Page content --}}
@section('content')

	<div id="page-account" class="page-inside">
		<div class="page-title"><h1 class="title">REGISTO</h1></div>
			<form id="new_account_form" class="general-form">
				
				<h2>DADOS DE ACESSO</h2>

				<div class='form_item'>{{ Form::label('username','Username') }}{{ Form::text('username','',array('style'=>'width:180px')) }}<div class='field_error'></div><span class="required">*</span></div>
				<div class='form_item'>{{ Form::label('password','Password') }}{{ Form::password('password','',array('style'=>'width:150px')) }}<div class='field_error'></div><span class="required">*</span></div>
				<div class='form_item'>{{ Form::label('password_confirm','Confirme Password') }}{{ Form::password('password_confirm','',array('style'=>'width:150px')) }}<div class='field_error'></div><span class="required">*</span></div>
				<div class='form_item'>{{ Form::label('email','Email') }}{{ Form::email('email','',array('style'=>'width:250px')) }}<div class='field_error'></div><span class="required">*</span></div>
				
			   	<br /><br /><h2>DADOS PESSOAIS</h2>

				<div class='form_item'>{{ Form::label('first_name','Nome') }}{{ Form::text('first_name','',array('style'=>'width:300px')) }}<div class='field_error'></div><span class="required">*</span></div>
				<div class='form_item'>{{ Form::label('last_name','Apelido') }}{{ Form::text('last_name','',array('style'=>'width:300px')) }}<div class='field_error'></div><span class="required">*</span></div>

			    {{ Form::token() }}
				<span class="bt button-blue unselectable">Criar Conta</span><span class="msg-form"></span>
				<div class="clear"></div>
			</form>
	</div>

@stop
