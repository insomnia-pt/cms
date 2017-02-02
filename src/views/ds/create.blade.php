@extends('cms::layouts/default')

{{-- Page title --}}
@section('title')
Adicionar Registo ::
@parent
@stop

{{-- Page content --}}
@section('content')

	<div class="row">
      <div class="col-lg-12">
          <ul class="breadcrumb pull-left">
              <li><a href="{{ route('cms') }}"><i class="icon-home"></i> Home</a></li>
              <li><a href="{{ route('cms/ds', $datasource->id) }}">{{ $datasource->name }}</a></li>
              <li><span class="active">Adicionar</span></li>
          </ul>

          <a href="{{ route('cms/ds', $datasource->id) }}@if($parameters['pds'])?pds={{$parameters['pds']}}&item={{$parameters['item']}} @endif" class="btn btn-small btn-info pull-right"><i class="icon-circle-arrow-left icon-white"></i> Voltar</a>
      </div>
  </div>

  <hr class="top-line" />

  <div class="row">
	  <div class="col-lg-12">
		<section class="panel">
			<header class="panel-heading form-group">Novo Registo</header>
			<div class="panel-body">
				<form class="form-horizontal tasi-form" method="post" action="" autocomplete="off">
					<input type="hidden" name="_token" value="{{ csrf_token() }}" />

					@if(@$datasource->options()->subitems)
						<div class="form-group">
							<label for="id_parent" class="col-lg-2 control-label">Ascendente</label>
							<div class="col-lg-6">

								<div class='easy-tree'>
									<?php
								    	$dsItems = CMS_ModelBuilder::fromTable($datasource->table)->get();
										$parentItems = $dsItems->filter(function($item) {
										    return $item->id_parent == 0;
										})->values();
									?>
									<input type="hidden" name="id_parent" id="id_parent" class="form-control easy-tree-selected" />
									<div class="input-group easy-tree-openlist">
								      <input type="text" class="form-control easy-tree-selected-text" placeholder="Selecione.." readonly />
								      <div class="input-group-addon"><i class="fa fa-chevron-down"></i></div>
								    </div>
									<div class="easy-tree-list">
									    <ul>
								       		@foreach ($parentItems as $parentitem)
										       @include('cms::ds._treeview-combolist', array('item' => $parentitem))
										    @endforeach

									    </ul>
									</div>
								</div>

							</div>
						</div>
					@endif

					@foreach($datasource->relations as $relation)
						@if($relation->relation_type=="hasOne")
							<?php
								$relationTable = Insomnia\Cms\Models\Datasource::find($relation->relation_datasource_id)->table;
							?>
							<div class="form-group">
								<label for="{{ $relationTable }}_id" class="col-lg-2 control-label">{{ $relation->relation_description }}</label>
								<div class="col-lg-5">

									<div class='easy-tree'>
										<?php
									    	$dsItems = CMS_ModelBuilder::fromTable($relationTable)->get();
											$parentItems = $dsItems->filter(function($item) { return $item->id_parent == 0; })->values();
										?>
										<input type="hidden" name="{{ $relationTable }}_id" id="{{ $relationTable }}_id" class="form-control easy-tree-selected"  />
										<div class="input-group easy-tree-openlist">
									      <input type="text" class="form-control easy-tree-selected-text" placeholder="Selecione.." readonly />
									      <div class="input-group-addon"><i class="fa fa-chevron-down"></i></div>
									    </div>
										<div class="easy-tree-list">
										    <ul>
									       		@foreach ($parentItems as $parentitem)
											       @include('cms::ds._treeview-combolist', array('item' => $parentitem, 'relation' => $relation))
											    @endforeach
										    </ul>
									    </div>
									</div>

								</div>
							</div>
						@endif
					@endforeach

					@foreach($datasource->config() as $config)
						<div class="form-group {{ $errors->has($config->name) ? 'has-error' : '' }}">
							<label for="{{ $config->name }}" class="col-lg-2 control-label">{{ $config->description }}</label>
							<div class="col-lg-7">

								@include('cms::components.'.$datasourceFieldtypes->find($config->datatype)->config()->field, ['component' => ['name' => $config->name, 'data' => Input::old($config->name), 'limit' => @$config->parameters->limit, 'extensions' => @$config->parameters->extensions, 'items' => @$config->parameters->values, 'folder' => @$config->parameters->folder]])

		            {{ $errors->first($config->name, '<p class="help-block">:message</p>') }}
							</div>
						</div>
					@endforeach

					<div class="form-group">
						<div class="col-lg-12 text-right">
							<button class="btn btn-danger" type="submit">Adicionar</button>
							<a class="btn btn-default" href="{{ route('cms/ds', $datasource->id) }}@if($parameters['pds'])?pds={{$parameters['pds']}}&item={{$parameters['item']}} @endif">Cancelar</a>
						</div>
					</div>

				</form>
			</div>
		</section>
	  <div>
	</div>

@stop

@section('styles')
	<link href="{{ Helpers::asset(Config::get('cms::config.assets_path').'/assets/plugins/bootstrap-datepicker/css/datepicker.css') }}" rel="stylesheet">
	<link href="{{ Helpers::asset(Config::get('cms::config.assets_path').'/assets/plugins/bootstrap-daterangepicker/daterangepicker.css') }}" rel="stylesheet">
	<link href="{{ Helpers::asset(Config::get('cms::config.assets_path').'/assets/css/easyTree.css') }}" rel="stylesheet">
	@yield('substyles')
@stop

@section('scripts')
    <script type="text/javascript" src="{{ Helpers::asset(Config::get('cms::config.assets_path').'/assets/js/jquery.tagsinput.js') }}"></script>
    <script src="{{ Helpers::asset(Config::get('cms::config.assets_path').'/assets/js/jquery.popupWindow.js') }}"></script>
    <script type="text/javascript" src="{{ Helpers::asset(Config::get('cms::config.assets_path').'/assets/js/bootstrap-switch.js') }}"></script>
    <script src="{{ Helpers::asset(Config::get('cms::config.assets_path').'/assets/plugins/ckeditor/ckeditor.js') }}"></script>
		<script type="text/javascript" src="{{ Helpers::asset(Config::get('cms::config.assets_path').'/assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js') }}"></script>
    <script type="text/javascript" src="{{ Helpers::asset(Config::get('cms::config.assets_path').'/assets/plugins/bootstrap-daterangepicker/date.js') }}"></script>
    <script type="text/javascript" src="{{ Helpers::asset(Config::get('cms::config.assets_path').'/assets/js/easyTree.js') }}"></script>
		@yield('subscripts')

    <script type="text/javascript">
    	$('.easy-tree').EasyTree();
    </script>
@stop
