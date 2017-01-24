@if ($errors->any())
var n = noty({
	text:'<strong>Erro</strong><br />Verifique os erros no formulário', 
 	type:'error'
});
@endif

@if ($message = Session::get('success'))
var n = noty({
	text:'<strong>Sucesso</strong><br />{{ $message }}', 
	type:'success'
});
@endif

@if ($message = Session::get('error'))
var n = noty({
	text:'<strong>Erro</strong><br />{{ $message }}', 
 	type:'error'
});
@endif

@if ($message = Session::get('warning'))
var n = noty({
	text:'<strong>Aviso</strong><br />{{ $message }}', 
 	type:'warning'
});
@endif

@if ($message = Session::get('info'))
var n = noty({
	text:'<strong>Informação</strong><br />{{ $message }}', 
 	type:'information'
});
@endif
