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
		<form id="recover_account_form" class="general-form" onkeypress="return event.keyCode != 13;">

			<div class='form_item'>{{ Form::label('email','Email') }}{{ Form::email('email','',array('style'=>'width:250px')) }}<div class='field_error'></div><span class="required">*</span></div>

			{{ Form::token() }}
			<span class="bt button-blue unselectable">Confirmar</span><span class="msg-form"></span>
			<div class="clear"></div>
		</form>
	</div>

@stop