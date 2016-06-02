@extends('ocms::layouts/default')

{{-- Page title --}}
@section('title')
Gestão de {{ $datasource->name }} ::
@parent
@stop

{{-- Page content --}}
@section('content')
	<div class="row">
      	<div class="col-lg-12">
	          <ul class="breadcrumb pull-left">
	              <li><a href="{{ URL::to('ocms') }}"><i class="icon-home"></i> Home</a></li>
	              @if($parameters['pds']) 
			      <li><a href="{{ route('admin/ds', $parameters['pds']) }}">{{ $parentDatasource->name }}</a></li>
			      @endif
	              <li><span class="active">{{ $datasource->name }}</span></li>
	          </ul>

	        @if(array_key_exists($datasource->table.'.create', $_groupPermissions))
				<a href="{{ route('create/ds', $datasource->id) }}@if($parameters['pds'])?pds={{$parameters['pds']}}&item={{$parameters['item']}} @endif" class="btn btn-small btn-info pull-right"><i class="icon-plus-sign icon-white"></i> Adicionar</a>
			@endif

	          	@if($parameters['pds']) 
		      		<a href="{{ route('admin/ds', $parameters['pds']) }}" class="btn btn-small btn-info pull-right" style="margin-right:5px"><i class="icon-circle-arrow-left icon-white"></i> Voltar</a>
		      	@endif
	      </div>
  	</div>

  	<hr class="top-line" />

  	<div class="row">
	    <div class="col-lg-12">
	    @if(@$datasource->options()->subitems)
	        @include('ocms::ds.index-treeview')
	    @else
			@include('ocms::ds.index-tableview')
	    @endif
	    </div>
	</div>


@stop

@section('scripts')
	<script type="text/javascript" src="{{ asset('ocms-res/assets/plugins/data-tables/jquery.dataTables.js') }}"></script>
    <script type="text/javascript" src="{{ asset('ocms-res/assets/plugins/data-tables/DT_bootstrap.js') }}"></script>
    <script type="text/javascript" src="{{ asset('ocms-res/assets/js/dynamic-table.js') }}"></script>


    @yield('subscripts')
@stop