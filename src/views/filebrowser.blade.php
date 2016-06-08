@extends('cms::layouts/default')

{{-- Page title --}}
@section('title')
Gestor de Ficheiros ::
@parent
@stop

@section('content')

	<div class="row">
      	<div class="col-lg-12">
	          <ul class="breadcrumb pull-left">
	              <li><a href="{{ route('cms') }}"><i class="icon-home"></i> Home</a></li>
	              <li><span class="active">Gestor de Ficheiros</span></li>
	          </ul>
	      </div>
  	</div>

  	<hr class="top-line" />

  	<div style="position: absolute; top: 140px; left: 216px; right: 16px; bottom: 16px; overflow: hidden;">
		<iframe src="{{ route('cms/elfinder') }}" width="100%" height="100%" style="border:none"></iframe>
	</div>

@stop