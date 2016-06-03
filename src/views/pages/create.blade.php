@extends('cms::layouts/default')

{{-- Page title --}}
@section('title')
Adicionar Página ::
@parent
@stop

{{-- Page content --}}
@section('content')

	<div class="row">
      <div class="col-lg-12">
          <ul class="breadcrumb pull-left">
              <li><a href="{{ route('cms') }}"><i class="icon-home"></i> Home</a></li>
              <li><a href="{{ route('pages') }}">Páginas</a></li>
              <li><span class="active">Adicionar</span></li>
          </ul>

          <a href="{{ URL::previous() }}" class="btn btn-small btn-info pull-right"><i class="icon-circle-arrow-left icon-white"></i> Voltar</a>
      </div>
  </div>

  <hr class="top-line" />

  <div class="row">
        @if(!Input::get('pageType'))

        <div class="col-lg-12">
			<section class="panel">
				<header class="panel-heading form-group">Nova Página</header>
				<div class="panel-body">
              	
	              	<form class="form-horizontal tasi-form" method="get" action="" autocomplete="off">
						<div class="form-group">
							<label for="pageType" class="col-lg-2 control-label">Tipo de Página</label>
							<div class="col-lg-6">
								<select name="pageType" id="pageType" class="form-control">
									<option value="0">Selecione..</option>
									@foreach($pageTypes as $pageType)
									<option value="{{ $pageType->id }}">{{ $pageType->name }}</option>
									@endforeach
								</select>
							</div>
						</div>
						@if(Input::get('group'))<input type="hidden" name="group" value="{{ Input::get('group') }}"></input>@endif
						<div class="form-group">
							<div class="col-lg-offset-2 col-lg-10">
								<button class="btn btn-danger" type="submit">Seguinte</button>
								<a class="btn btn-default" href="{{ route('pages') }}{{ Input::get('group')?'?group='.Input::get('group'):null }}">Cancelar</a>
							</div>
						</div>
					</form>
				</div>
			</section>
		</div>

		@elseif(Input::get('pageType'))
		<form method="post" action="" autocomplete="off">
		<div class="col-lg-{{ @count($pageTypeSel->config()->settings)?'9':'12' }}">
			<section class="panel">
				<header class="panel-heading form-group">Nova Página</header>
				<div class="panel-body form-horizontal tasi-form">		

					<input type="hidden" name="_token" value="{{ csrf_token() }}" />

					<div class="form-group">
						<label for="pageType" class="col-lg-2 control-label">Tipo de Página</label>
						<div class="col-lg-6">
							<h4>{{ $pageTypeSel->name }}</h4>
						</div>
					</div>

					@if(@$datasource->options()->subitems)
						<div class="form-group">
							<label for="id_parent" class="col-lg-2 control-label">Ascendente</label>
							<div class="col-lg-6">

								<div class='easy-tree'>
									<?php
								    	$dsItems = ModelBuilder::fromTable($datasource->table)->get();
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
					@else
						<input type="hidden" name="id_parent" id="id_parent" value="{{ Input::get('group') }}" />
					@endif

					<div class="form-group {{ $errors->has('title') ? 'has-error' : '' }}">
						<label for="title" class="col-lg-2 control-label">Título</label>
						<div class="col-lg-7">
							<input type="text" class="form-control" name="title" id="title" value="{{ Input::old('title') }}" />
	                     	{{ $errors->first('title', '<p class="help-block">:message</p>') }}
						</div>
					</div>

					@foreach($pageTypeSel->config()->areas as $area)
						<div class="form-group {{ $errors->has($area->name) ? 'has-error' : '' }}" @if(isset($area->field->admin)&&!Sentry::getUser()->hasAccess('admin'))) style="display: none;" @endif>
							<label for="{{ $area->name }}" class="col-lg-2 control-label">{{ $area->field->name }}</label>
							<div class="col-lg-{{ $area->field->size }}">
							@if($datasourceFieldtypes->find($area->field->datatype)->config()->field == 'textarea')
								<textarea class="form-control ckeditor" name="{{ $area->name }}" id="{{ $area->name }}" value="content" rows="10">{{ Input::old($area->name) }}</textarea>
							@elseif($datasourceFieldtypes->find($area->field->datatype)->config()->field == 'text')
								<input type="text" class="form-control" name="{{ $area->name }}" id="{{ $area->name }}" value="{{ Input::old($area->name) }}" />
							@elseif($datasourceFieldtypes->find($area->field->datatype)->config()->field == 'image')
								<input class="form-control inline image" type="text" name="{{ $area->name }}" id="{{ $area->name }}" data-limit="{{ @$area->field->parameters->limit }}" value="{{ Input::old($area->name) }}" readonly />
							@elseif($datasourceFieldtypes->find($area->field->datatype)->config()->field == 'document')
								<input class="form-control inline document" type="text" name="{{ $area->name }}" id="{{ $area->name }}" data-limit="{{ @$area->field->parameters->limit }}" value="{{ Input::old($area->name) }}" readonly />
							@endif
		                     	{{ $errors->first($area->name, '<p class="help-block">:message</p>') }}
							</div>
						</div>
					@endforeach 
					
					<div class="form-group">
						<div class="col-lg-offset-2 col-lg-10">
							<button class="btn btn-danger" type="submit">Adicionar</button>
							<a class="btn btn-default" href="{{ route('pages') }}{{ Input::get('group')?'?group='.Input::get('group'):null }}">Cancelar</a>
						</div>
					</div>
					

				@endif
	
			</div>
		</section>
	  </div>

	  @if(Input::get('pageType') && @count($pageTypeSel->config()->settings))
	  	<div class="col-lg-3">
			<section class="panel">
				<header class="panel-heading">Definições </header>
				<div class="panel-body form-horizontal tasi-form">
					@foreach($pageTypeSel->config()->settings as $setting)
						<div class="form-group {{ $errors->has($setting->name) ? 'has-error' : '' }}">
							<label for="{{ $setting->name }}" class="col-lg-2 control-label">{{ $setting->field->name }}</label>
							<div class="col-lg-{{ $setting->field->size }}">
							@if($datasourceFieldtypes->find($setting->field->datatype)->config()->field == 'textarea')
								<textarea class="form-control ckeditor" name="{{ $setting->name }}" id="{{ $setting->name }}" value="content" rows="10">{{ Input::old($setting->name) }}</textarea>
							@elseif($datasourceFieldtypes->find($setting->field->datatype)->config()->field == 'text')
								<input type="text" class="form-control" name="{{ $setting->name }}" id="{{ $setting->name }}" value="{{ Input::old($setting->name) }}" />
							@elseif($datasourceFieldtypes->find($setting->field->datatype)->config()->field == 'image')
								<input class="form-control inline image" type="text" name="{{ $setting->name }}" id="{{ $setting->name }}" data-limit="{{ @$setting->field->parameters->limit }}" value="{{ Input::old($setting->name) }}" readonly />
							@elseif($datasourceFieldtypes->find($setting->field->datatype)->config()->field == 'document')
								<input class="form-control inline document" type="text" name="{{ $setting->name }}" id="{{ $setting->name }}" data-limit="{{ @$setting->field->parameters->limit }}" value="{{ Input::old($setting->name) }}" readonly />
							@elseif($datasourceFieldtypes->find($setting->field->datatype)->config()->field == 'date')
								<div class="input-append date col-lg-10" style="padding: 0" data-date-format="yyyy-mm-dd" data-date="{{ date('Y-m-d') }}">
									<input type="text" class="form-control" name="{{ $setting->name }}" id="{{ $setting->name }}" value="{{ Input::old($setting->name) }}" readonly />
									<span class="add-on"><i class="fa fa-calendar"></i></span>
								</div>
							@elseif($datasourceFieldtypes->find($setting->field->datatype)->config()->field == 'combobox')
								<select class="form-control" name="{{ $setting->name }}" id="{{ $setting->name }}">
									@foreach(explode(';', @$setting->parameters->values) as $fieldOption)
										<option value="{{ @explode(',', $fieldOption)[0] }}" {{ Input::old($setting->name)==@explode(',', $fieldOption)[0]?'selected':'' }}>{{ @explode(',', $fieldOption)[1] }}</option>
									@endforeach
								</select>
							@endif
		                     	{{ $errors->first($setting->name, '<p class="help-block">:message</p>') }}
							</div>
						</div>
					@endforeach 
				</div>
			</section>
		</div>
		@endif
		</form>
	</div>

@stop

@section('styles')
	<link href="{{ asset(Config::get('cms::config.assets_path').'/assets/plugins/bootstrap-datepicker/css/datepicker.css') }}" rel="stylesheet">
	<link href="{{ asset(Config::get('cms::config.assets_path').'/assets/plugins/bootstrap-daterangepicker/daterangepicker.css') }}" rel="stylesheet">
	<link href="{{ asset(Config::get('cms::config.assets_path').'/assets/css/easyTree.css') }}" rel="stylesheet">
@stop

@section('scripts')
	<script type="text/javascript" src="{{ asset(Config::get('cms::config.assets_path').'/assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js') }}"></script>
    <script type="text/javascript" src="{{ asset(Config::get('cms::config.assets_path').'/assets/plugins/bootstrap-daterangepicker/date.js') }}"></script>
    <script type="text/javascript" src="{{ asset(Config::get('cms::config.assets_path').'/assets/plugins/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
    <script type="text/javascript" src="{{ asset(Config::get('cms::config.assets_path').'/assets/js/jquery.tagsinput.js') }}"></script>
    <script type="text/javascript" src="{{ asset(Config::get('cms::config.assets_path').'/assets/js/easyTree.js') }}"></script>
    <script src="{{ asset(Config::get('cms::config.assets_path').'/assets/plugins/ckeditor/ckeditor.js') }}"></script>
	<script src="http://swip.codylindley.com/jquery.popupWindow.js"></script>

	<script type="text/javascript">
    	$('.easy-tree').EasyTree();
    </script>

@stop
