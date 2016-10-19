@extends('cms::layouts/default')

{{-- Page title --}}
@section('title')
Editar Página ::
@parent
@stop

{{-- Page content --}}
@section('content')

	<div class="row">
      <div class="col-lg-12">
          <ul class="breadcrumb pull-left">
              <li><a href="{{ route('cms') }}"><i class="icon-home"></i> Home</a></li>
              <li><a href="{{ route('pages') }}">Páginas</a></li>
              <li><span class="active">Editar</span></li>
          </ul>

          <a href="{{ route('pages') }}{{ @$datasource->options()->group?'?group='.$page->id_parent:null }}" class="btn btn-small btn-info pull-right"><i class="icon-circle-arrow-left icon-white"></i> Voltar</a>
      </div>
  </div>

  <hr class="top-line" />

  <div class="row">
  	<form method="post" action="" autocomplete="off">
	  <div class="col-lg-{{ ($hasDatasources||@count($page->pagetype->config()->settings))?'9':'12' }}">
		<section class="panel">
			<header class="panel-heading form-group">Detalhes da Página</header>
			<div class="panel-body form-horizontal tasi-form">

				<input type="hidden" name="_token" value="{{ csrf_token() }}" />
				@if(Input::get('group'))<input type="hidden" name="group" value="{{ Input::get('group') }}"></input>@endif

				<div class="form-group">
					<label for="pageType" class="col-lg-2 control-label">Tipo de Página</label>
					<div class="col-lg-6">
						<h4>{{ $page->pagetype->name }}</h4>
					</div>
				</div>


				<div class="form-group" @if($pageGlobal) style="display:none;" @endif>
					<label for="pageType" class="col-lg-2 control-label">URL</label>
					<div class="col-lg-6">
						<h5>{{ Config::get('app.url') }}<strong>{{ $page->slug }}</strong></h5>
					</div>
				</div>


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
								<input type="hidden" name="id_parent" id="id_parent" value="{{ $page->id_parent }}" class="form-control easy-tree-selected" />
								<div class="input-group easy-tree-openlist">
							      <input value="{{ @$dsItems->find($page->id_parent)->{$datasource->config()[0]->name} }}" type="text" class="form-control easy-tree-selected-text" placeholder="Selecione.." readonly />
							      <div class="input-group-addon"><i class="fa fa-chevron-down"></i></div>
							    </div>
								<div class="easy-tree-list">
								    <ul>
							       		@foreach ($parentItems as $parentitem)
									       @include('cms::ds._treeview-combolist', array('item' => $parentitem, 'selected'=> $page->id_parent, 'editing'=>$page->id ))
									    @endforeach
								    </ul>
								</div>
							</div>

						</div>
					</div>
				@else
					<input type="hidden" name="id_parent" id="id_parent" value="{{ $page->id_parent }}" />
				@endif


        <div class="form-group {{ $errors->has('title') ? 'has-error' : '' }}" @if($pageGlobal) style="display:none;" @endif>
					<label for="title" class="col-lg-2 control-label">Título da Página</label>
					<div class="col-lg-7">
						<input type="text" class="form-control" name="title" id="title" value="{{ Input::old('title', $page->title) }}" />
                     	{{ $errors->first('title', '<p class="help-block">:message</p>') }}
					</div>
				</div>

				@if(@$page->pagetype->config()->areas)
				@foreach($page->pagetype->config()->areas as $area)

					<div class="form-group {{ $errors->has($area->name) ? 'has-error' : '' }}" @if(isset($area->field->admin)&&!Sentry::getUser()->hasAccess('admin'))) style="display: none;" @endif>
						<label for="{{ $area->name }}" class="col-lg-2 control-label">{{ $area->field->name }}</label>
						<div class="col-lg-{{ $area->field->size }}">

							@include('cms::components.'.$datasourceFieldtypes->find($area->field->datatype)->config()->field, ['component' => ['name' => $area->name, 'data' => Input::old($area->name, @$page->areas()->{$area->name}), 'limit' => @$area->field->parameters->limit, 'extensions' => @$config->parameters->extensions, 'items' => @$area->field->parameters->values]])

	            {{ $errors->first($area->name, '<p class="help-block">:message</p>') }}
						</div>
					</div>

				@endforeach
				@endif


				<div class="form-group">
					<div class="col-lg-offset-2 col-lg-10">
						@if($page->editable)
							@if(array_key_exists('pages.update', $_groupPermissions))<button class="btn btn-danger" type="submit">Guardar</button>@endif
						@endif
						<a class="btn btn-default" href="{{ route('pages') }}{{ @$datasource->options()->group?'?group='.$page->id_parent:null }}">Cancelar</a>
					</div>
				</div>

			</div>
		</section>
	  </div>

	  @if($hasDatasources || @count($page->pagetype->config()->settings))
	  	<div class="col-lg-3">
	  		@if(@$page->pagetype->config()->settings)
	  		<section class="panel">
				<header class="panel-heading">Definições </header>
				<div class="panel-body form-horizontal tasi-form">

					@foreach($page->pagetype->config()->settings as $setting)
						<div class="form-group {{ $errors->has($setting->name) ? 'has-error' : '' }}">
							<label for="{{ $setting->name }}" class="col-lg-2 control-label">{{ $setting->field->name }}</label>
							<div class="col-lg-{{ $setting->field->size }}">
							@if($datasourceFieldtypes->find($setting->field->datatype)->config()->field == 'textarea')
								<textarea class="form-control ckeditor" name="{{ $setting->name }}" id="{{ $setting->name }}" value="content" rows="10">{{ Input::old($setting->name, @$page->areas()->{$setting->name}) }}</textarea>
							@elseif($datasourceFieldtypes->find($setting->field->datatype)->config()->field == 'text')
								<input type="text" class="form-control" name="{{ $setting->name }}" id="{{ $setting->name }}" value="{{ Input::old($setting->name, @$page->areas()->{$setting->name}) }}" />
							@elseif($datasourceFieldtypes->find($setting->field->datatype)->config()->field == 'image')
								<input class="form-control inline image" type="text" name="{{ $setting->name }}" id="{{ $setting->name }}" data-limit="{{ @$setting->field->parameters->limit }}" value="{{ Input::old($setting->name, @$page->areas()->{$setting->name}) }}" readonly />
							@elseif($datasourceFieldtypes->find($setting->field->datatype)->config()->field == 'combobox')
								<select class="form-control" name="{{ $setting->name }}" id="{{ $setting->name }}">
									@foreach(explode(';', @$setting->parameters->values) as $fieldOption)
										<option value="{{ @explode(',', $fieldOption)[0] }}" {{ Input::old($setting->name, @$page->areas()->{$setting->name})==@explode(',', $fieldOption)[0]?'selected':'' }}>{{ @explode(',', $fieldOption)[1] }}</option>
									@endforeach
								</select>
							@endif
		                     	{{ $errors->first($setting->name, '<p class="help-block">:message</p>') }}
							</div>
						</div>
					@endforeach

				</div>
			</section>
			@endif

			@if($hasDatasources)
			<section class="panel">
				<header class="panel-heading">Componentes <label data-original-title="Informação" data-content="Lista de componentes existentes na página que são geridos de forma isolada." data-placement="left" data-trigger="hover" class="pull-right popovers"><i class="fa fa-info-circle"></i></label></header>

					@foreach($page->datasources as $component)
					<div class="list-group">
                      <a class="list-group-item" target="_blank" href="{{ @$component->options()->url?URL::to(Config::get('cms::config.uri').$component->options()->url):URL::to(Config::get('cms::config.uri').'/ds/'.$component->id) }}">
                          <i class="pull-right fa fa-external-link"></i>
                          <h4 class="list-group-item-heading">{{ $component->name }}</h4>
                          <p class="text-muted list-group-item-text">{{ $component->description }}</p>
                      </a>
                  	</div>
					@endforeach

			</section>
			@endif
		</div>
		@endif

	  </form>
	</div>

@stop

@section('styles')
	<link href="{{ Helpers::asset(Config::get('cms::config.assets_path').'/assets/plugins/bootstrap-datepicker/css/datepicker.css') }}" rel="stylesheet">
	<link href="{{ Helpers::asset(Config::get('cms::config.assets_path').'/assets/plugins/bootstrap-daterangepicker/daterangepicker.css') }}" rel="stylesheet">
	<link href="{{ Helpers::asset(Config::get('cms::config.assets_path').'/assets/css/easyTree.css') }}" rel="stylesheet">
	@yield('substyles')
@stop

@section('scripts')
	<script type="text/javascript" src="{{ Helpers::asset(Config::get('cms::config.assets_path').'/assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js') }}"></script>
    <script type="text/javascript" src="{{ Helpers::asset(Config::get('cms::config.assets_path').'/assets/plugins/bootstrap-daterangepicker/date.js') }}"></script>
    <script type="text/javascript" src="{{ Helpers::asset(Config::get('cms::config.assets_path').'/assets/plugins/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
    <script type="text/javascript" src="{{ Helpers::asset(Config::get('cms::config.assets_path').'/assets/js/jquery.tagsinput.js') }}"></script>
	<script type="text/javascript" src="{{ Helpers::asset(Config::get('cms::config.assets_path').'/assets/js/easyTree.js') }}"></script>
    <script src="{{ Helpers::asset(Config::get('cms::config.assets_path').'/assets/plugins/ckeditor/ckeditor.js') }}"></script>
	<script src="{{ Helpers::asset(Config::get('cms::config.assets_path').'/assets/js/jquery.popupWindow.js') }}"></script>
	@yield('subscripts')

    <script type="text/javascript">
    	$('.easy-tree').EasyTree();
    </script>

@stop
