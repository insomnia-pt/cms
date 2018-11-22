@extends('cms::layouts/'.($parameters['modal']?'modal':'default'))

{{-- Page title --}}
@section('title')
Gestão de {{ $datasource->name }} ::
@parent
@stop

{{-- Page content --}}
@section('content')
	<div class="row">
      	<div class="col-lg-12">

		  	@if(!$parameters['modal'])
	          <ul class="breadcrumb pull-left">
	              <li><a href="{{ route('cms') }}"><i class="icon-home"></i> Home </a></li>
	              @if($parameters['pds'])
			      <li><a href="{{ route('cms/ds', $parameters['pds']) }}@if($parameters['modal'])?modal=true @endif">{{ $parentDatasource->name }}</a></li>
			      @endif
	              <li><span class="active">{{ $datasource->name }}</span></li>
	          </ul>
			@endif

			  

	        @if(CMS_Helper::checkPermission($datasource->table.'.create'))
				<a href="{{ route('cms/ds/create', $datasource->id) }}@if($parameters['pds'])?pds={{$parameters['pds']}}&item={{$parameters['item']}}@if($parameters['modal'])&modal=true @endif @elseif($parameters['modal'])?modal=true @endif" class="btn btn-small btn-info pull-right" @if($parameters['modal']) style="margin-bottom: 10px" @endif ><i class="icon-plus-sign icon-white"></i> Adicionar</a>
			@endif

	          	@if($parameters['pds'])
		      		<a href="{{ route('cms/ds', $parameters['pds']) }}@if($parameters['modal'])?modal=true @endif" class="btn btn-small btn-info pull-right" style="margin-right:5px"><i class="icon-circle-arrow-left icon-white"></i> Voltar</a>
		      	@endif
	      </div>
  	</div>

  	<hr class="top-line" />

  	<div class="row">
	    <div class="col-lg-12">
	    @if(@$datasource->options()->subitems)
	        @include('cms::ds.index-treeview')
	    @else
			@include('cms::ds.index-tableview')
	    @endif
	    </div>
	</div>


@stop

@section('styles')
	<link href="{{ Helpers::asset(Config::get('cms::config.assets_path').'/assets/css/jquery.nestable.css') }}" rel="stylesheet">
	@yield('substyles')
@stop

@section('scripts')
		<script type="text/javascript" src="{{ Helpers::asset(Config::get('cms::config.assets_path').'/assets/js/jquery.nestable.js') }}"></script>
    @yield('subscripts')
@stop
