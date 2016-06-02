@extends('ocms::layouts/default')

{{-- Page title --}}
@section('title')
Gestão de Data Sources ::
@parent
@stop

{{-- Page content --}}
@section('content')
	<div class="row">
      	<div class="col-lg-12">
	          <ul class="breadcrumb pull-left">
	              <li><a href="{{ URL::to('ocms') }}"><i class="icon-home"></i> Home</a></li>
	              <li><span class="active">Data Sources</span></li>
	          </ul>
	          <a href="{{ route('create/datasource') }}" class="btn btn-small btn-info pull-right"><i class="icon-plus-sign icon-white"></i> Adicionar</a>
	    </div>
  	</div>

  	<hr class="top-line" />

  	<div class="row">
	      <div class="col-lg-12">
	          <section class="panel panel-primary">
	              <header class="panel-heading">
	                  Lista de Data Sources
	                  <input class="form-control pull-right input-smmm" type="text" id="dataTable1filter" placeholder="Procurar.." style="width: 200px">
	              </header>
	              <table class="table table-striped border-top table-hover table-no-top-border" id="main_table">
	              <thead>
		              <tr>
		                	<th>Nome</th>
		                  	<th class="hidden-phone">Criado em</th>
		                  	<th class="hidden-phone"></th>
		              </tr>
	              </thead>
	              <tbody>
	              	@foreach ($datasources as $datasource)
					<tr class="odd gradeX">
						<td>{{ $datasource->name }}</td>
						<td class="hidden-phone">{{ $datasource->created_at }}</td>
						<td class="text-right">
							@if(!$datasource->system)
							<a href="{{ route('update/datasource', $datasource->id) }}" class="btn btn-xs btn-default">@lang('button.edit')</a>
							<a class="btn btn-xs btn-danger" data-msg="Confirma eliminar o datasource?" data-reply="" data-toggle="modal" data-descr="{{ $datasource->name }}" data-url="{{ route('delete/datasource', $datasource->id) }}" href="#modal-confirm">@lang('button.delete')</a>
							@endif
						</td>
					</tr>
					@endforeach

	              </tbody>
	              </table>
	          </section>
	      </div>
	  	</div>

@stop

@section('scripts')
	<script type="text/javascript" src="{{ asset('ocms-res/assets/plugins/data-tables/jquery.dataTables.js') }}"></script>
    <script type="text/javascript" src="{{ asset('ocms-res/assets/plugins/data-tables/DT_bootstrap.js') }}"></script>
    <script type="text/javascript" src="{{ asset('ocms-res/assets/js/dynamic-table.js') }}"></script>
    <script type="text/javascript">

    	var oTable = $('#main_table').dataTable();
    	oTable.fnSort( [[2,'desc'] ] );

    </script>
@stop