@extends('cms::layouts/default')

{{-- Page title --}}
@section('title')
Gestão de Páginas ::
@parent
@stop

{{-- Page content --}}
@section('content')
	<div class="row">
      	<div class="col-lg-12">
	          <ul class="breadcrumb pull-left">
	              <li><a href="{{ URL::route('cms') }}"><i class="icon-home"></i> Home</a></li>
	              <li><span class="active">Páginas</span></li>
	          </ul>
	          @if(array_key_exists('pages.create', $_groupPermissions))
	          <a href="{{ route('pages/create') }}{{ Input::get('group')?'?group='.Input::get('group'):null }}" class="btn btn-small btn-info pull-right"><i class="icon-plus-sign icon-white"></i> Adicionar</a>
	          @endif
	      </div>
  	</div>

  	<hr class="top-line" />

  	<div class="row">
	    <div class="col-lg-12">
	    @if(@$datasource->options()->subitems)
	        @include('cms::pages.index-treeview')
	    @else
			@include('cms::pages.index-tableview')
	    @endif
	    </div>
	</div>

@stop



@section('scripts')
	<script type="text/javascript" src="{{ asset(Config::get('cms::config.assets_path').'/assets/plugins/data-tables/jquery.dataTables.js') }}"></script>
    <script type="text/javascript" src="{{ asset(Config::get('cms::config.assets_path').'/assets/plugins/data-tables/DT_bootstrap.js') }}"></script>
    <script type="text/javascript" src="{{ asset(Config::get('cms::config.assets_path').'/assets/js/dynamic-table.js') }}"></script>

    @yield('subscripts')
@stop