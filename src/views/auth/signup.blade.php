@extends('frontend/layouts/default')

{{-- Page title --}}
@section('title')
Registo ::
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
				<div class='form_item'>{{ Form::label('city','Localidade') }}{{ Form::text('city','',array('style'=>'width:150px')) }}<div class='field_error'></div><span class="required">*</span></div>
				<div class='form_item'>{{ Form::label('phone','Telefone') }}{{ Form::text('phone','',array('style'=>'width:120px')) }}<div class='field_error'></div><span class="required">*</span></div>
				<div class='form_item'>{{ Form::label('nif','NIF') }}{{ Form::text('nif','',array('style'=>'width:120px')) }}<div class='field_error'></div></div>

			    <br /><br /><h2>OUTROS DADOS</h2>

			    <div class='form_item'>{{ Form::label('user_ref','Código do Amigo') }}{{ Form::text('user_ref',$refCode,array('style'=>'width:100px')) }}<div class='field_error'></div></div>
			    <div class='form_item'>{{ Form::label('from_info','Onde tomei conhecimento do Clube dos Umbigos') }}{{ Form::text('from_info','',array('style'=>'width:330px')) }}<div class='field_error'></div></div>
			

			    <div class='form_item'>{{ Form::label('vousermae','Vou ser mãe / pai') }}{{ Form::radio('vouFuiMae','','',array('id'=>'vousermae')) }} {{ Form::label('fuimae','Fui mãe / pai',array('style'=>'width:120px')) }}{{ Form::radio('vouFuiMae','','',array('id'=>'fuimae')) }}</div>
			    
			    <div style='display:none;' id='datapartoline' class='form_item'>{{ Form::label('dataparto','Data do parto') }}{{ Form::text('dataparto','',array('readonly'=>'','style'=>'width:120px')) }}</div>

			    {{ Form::token() }}
				<span class="bt button-blue unselectable">Criar Conta</span><span class="msg-form"></span>
				<div class="clear"></div>
			</form>
	</div>

@stop
