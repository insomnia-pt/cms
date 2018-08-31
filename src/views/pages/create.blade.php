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

          <a href="{{ URL::previous() }}" class="btn btn-small btn-default pull-right"><i class="icon-circle-arrow-left icon-white"></i> Voltar</a>
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
							<div class="col-lg-12 text-right">
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

		<div class="col-lg-{{ @count($pageTypeSel->config()->settings) || $hasSettings ?'9':'12' }}">
			<section class="panel">
				<header class="panel-heading">Nova Página</header>

				<ul class="pull-right nav nav-tabs" style="margin: -40px 10px 0 0">
					<li><i title="Idiomas" class="fa fa-language" style="padding:14px 12px 0 0"></i> </li>
					@foreach($languages as $langkey => $language)
					<li><a class="lang-selection" data-toggle="tab" data-langselection="{{ $langkey }}" href="#" >{{ $language }}</a></li>
					@endforeach
				</ul>
			
			
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
					@else
						<input type="hidden" name="id_parent" id="id_parent" value="{{ Input::get('group') }}" />
					@endif



					@foreach($datasource->relations as $relation)
								
						<?php
							$relationDatasource = Insomnia\Cms\Models\Datasource::find($relation->relation_datasource_id);
							$relationTable = $relationDatasource->table;
						?>
						
						@if($relation->relation_type=="belongsToMany" && $relation->config()->area == "form")
							<?php 
								$relationDatasourceItems = CMS_ModelBuilder::fromTable($relationTable)->get(); 
							?>

							<div class="form-group">
								<label for="" class="col-lg-2 control-label">{{ $relation->relation_description }}</label>
								<div class="col-lg-6">
					
									<select name="{{ $relationTable }}[]" class="multiselect" multiple="multiple">
										@foreach($relationDatasourceItems as $relationDatasourceItem)
										<option value="{{ $relationDatasourceItem->id }}">{{ $relationDatasourceItem[$relation->config()->fields[0]] }}</option>
										@endforeach
									</select>
								</div>
							</div>
						@endif

						@if($relation->relation_type == "hasOne" && $relation->config()->area == "form")
							<?php

								$field = null;
								foreach($relationDatasource->config() as $struct) {
									if ($relation->config()->fields[0] == $struct->name) {
										$field = $struct;
										break;
									}
								}
								
							?>
							<div class="form-group">
								<label for="{{ $relationTable }}_id" class="col-lg-2 control-label">{{ $relation->relation_description }}</label>
								<div class="col-lg-6">
								
									<div class='easy-tree'>
										<?php
											$relationTableModel = $relationDatasource;
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




					<div class="form-group {{ $errors->has('title') ? 'has-error' : '' }}">
						<label for="title" class="col-lg-2 control-label">Nome</label>
						<div class="col-lg-7">
							<input type="text" class="form-control" name="title" id="title" value="{{ Input::old('title') }}" />
							{{ $errors->first('title', '<p class="help-block">:message</p>') }}
						</div>
					</div>

					<div class="form-group {{ $errors->has('slug') ? 'has-error' : '' }}">
						<label for="slug" class="col-lg-2 control-label">URL</label>
						<div class="col-lg-6">
							<div class="input-group">
							  <span class="input-group-addon">{{ Config::get('app.url') }}/</span>
							  <input type="text" class="form-control" name="slug" id="slug" value="{{ Input::old('slug') }}">
							</div>
							{{ $errors->first('slug', '<p class="help-block">:message</p>') }}
						</div>
					</div>

					@foreach($pageTypeSel->config()->areas as $area)

						@if(@$area->field->multilang)
							
							@if(@$area->title)<br />
							<div class="form-group">
								<label for="" class="col-md-12">{{ $area->title }}</label><br />
							</div>
							@endif

							@foreach($languages as $langkey => $language)
							<div data-lang="lang-{{ $langkey }}" class="form-group lang-field {{ $errors->has($area->name.'['.$langkey.']') ? 'has-error' : '' }}" @if(isset($area->field->admin)&&@$CMS_USER->getGroups()[0]->id != 1)) style="display: none;" @endif>
								<label for="{{ $area->name }}[{{ $langkey }}]" class="col-lg-2 control-label">{{ $area->field->name }} &nbsp;<i title="Campo com possibilidade de tradução" class="fa fa-language"></i> <small class="lang-active text-muted"></small></label>
								<div class="col-lg-{{ $area->field->size }}">

									@include('cms::components.'.$datasourceFieldtypes->find($area->field->datatype)->config()->field, ['component' => ['name' => $area->name.'['.$langkey.']', 'data' => Input::old($area->name.'.'.$langkey), 'limit' => @$area->field->parameters->limit, 'extensions' => @$area->field->parameters->extensions, 'items' => @$area->field->parameters->values, 'folder' => @$area->field->parameters->folder]])

									{{ $errors->first($area->name.'['.$langkey.']', '<p class="help-block">:message</p>') }}
								</div>
							</div>
							@endforeach
						@else

							@if(@$area->title)<br />
							<div class="form-group">
								<label for="" class="col-md-12">{{ $area->title }}</label><br />
							</div>
							@endif

							<div class="form-group {{ $errors->has($area->name) ? 'has-error' : '' }}" @if(isset($area->field->admin)&&@$CMS_USER->getGroups()[0]->id != 1)) style="display: none;" @endif>
								<label for="{{ $area->name }}" class="col-lg-2 control-label">{{ $area->field->name }} </label>
								<div class="col-lg-{{ $area->field->size }}">

									@include('cms::components.'.$datasourceFieldtypes->find($area->field->datatype)->config()->field, ['component' => ['name' => $area->name, 'data' => Input::old($area->name), 'limit' => @$area->field->parameters->limit, 'extensions' => @$area->field->parameters->extensions, 'items' => @$area->field->parameters->values, 'folder' => @$area->field->parameters->folder]])

									{{ $errors->first($area->name, '<p class="help-block">:message</p>') }}
								</div>
							</div>

						@endif

					@endforeach

					<div class="form-group">
						<div class="col-lg-12 text-right">
							<button class="btn btn-danger" type="submit">Adicionar</button>
							<a class="btn btn-default" href="{{ route('pages') }}{{ Input::get('group')?'?group='.Input::get('group'):null }}">Cancelar</a>
						</div>
					</div>


				@endif

			</div>
		</section>
	  </div>

	  @if((Input::get('pageType') && @count($pageTypeSel->config()->settings)) || $hasSettings )
	  	<div class="col-lg-3">
		
			@if((Input::get('pageType') && @count($pageTypeSel->config()->settings)))

				@foreach($pageTypeSel->config()->settings as $setting)
				<section class="panel">
					<header class="panel-heading">{{ $setting->field->name }} </header>
					<div class="panel-body form-horizontal tasi-form">

						<div class="form-group {{ $errors->has($setting->name) ? 'has-error' : '' }}" @if(isset($setting->field->admin)&&@$CMS_USER->getGroups()[0]->id != 1)) style="display: none;" @endif>
							<div class="col-lg-{{ $setting->field->size }}">

								@include('cms::components.'.$datasourceFieldtypes->find($setting->field->datatype)->config()->field, ['component' => ['name' => $setting->name, 'data' => Input::old($setting->name), 'limit' => @$setting->field->parameters->limit, 'extensions' => @$setting->field->parameters->extensions, 'items' => @$setting->field->parameters->values, 'folder' => @$setting->field->parameters->folder]])

								{{ $errors->first($setting->name, '<p class="help-block">:message</p>') }}
							</div>
						</div>
					</div>
				</section>
				@endforeach
			@endif
			
			@foreach($datasource->relations as $relation)
						
				<?php
					$relationDatasource = Insomnia\Cms\Models\Datasource::find($relation->relation_datasource_id);
					$relationTable = $relationDatasource->table;
				?>
				
				@if($relation->relation_type=="belongsToMany" && $relation->config()->area == "settings")
					<?php 
						$relationDatasourceItems = CMS_ModelBuilder::fromTable($relationTable)->get(); 
					?>

					<section class="panel">
						<header class="panel-heading">{{ $relation->relation_description }} </header>
						<div class="panel-body form-horizontal tasi-form">

							<div class="form-group">
								<div class="col-lg-12">
					
									<select name="{{ $relationTable }}[]" class="multiselect" multiple="multiple">
										@foreach($relationDatasourceItems as $relationDatasourceItem)
										<option value="{{ $relationDatasourceItem->id }}">{{ $relationDatasourceItem[$relation->config()->fields[0]] }}</option>
										@endforeach
									</select>
								</div>
							</div>
						</div>
					</section>
				@endif

				@if($relation->relation_type == "hasOne" && $relation->config()->area == "settings")
					<?php

						$field = null;
						foreach($relationDatasource->config() as $struct) {
							if ($relation->config()->fields[0] == $struct->name) {
								$field = $struct;
								break;
							}
						}
						
					?>
					<section class="panel">
						<header class="panel-heading">{{ $relation->relation_description }} </header>
						<div class="panel-body form-horizontal tasi-form">
							<div class="form-group">
								<div class="col-lg-12">
								
									<div class='easy-tree'>
										<?php
											$relationTableModel = $relationDatasource;
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
						</div>
					</section>
				@endif
			@endforeach

		</div>
		@endif
		</form>
	</div>

@stop

@section('styles')
	<link href="{{ Helpers::asset(Config::get('cms::config.assets_path').'/assets/plugins/bootstrap-datepicker/css/datepicker.css') }}" rel="stylesheet">
	<link href="{{ Helpers::asset(Config::get('cms::config.assets_path').'/assets/plugins/bootstrap-daterangepicker/daterangepicker.css') }}" rel="stylesheet">
	<link href="{{ Helpers::asset(Config::get('cms::config.assets_path').'/assets/css/bootstrap-multiselect.css') }}" rel="stylesheet">
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
	<script type="text/javascript" src="{{ Helpers::asset(Config::get('cms::config.assets_path').'/assets/js/bootstrap-multiselect.js') }}"></script>
		<script type="text/javascript" src="{{ Helpers::asset(Config::get('cms::config.assets_path').'/assets/js/jquery.popupWindow.js') }}"></script>
		@yield('subscripts')

	<script type="text/javascript">
		$('.easy-tree').EasyTree();
		$('.multiselect').multiselect();

		$('#title').on('keyup change', function(){
			var title = $(this).val();
			$('#slug').attr('placeholder',convertToSlug(title));
		});

		$('#slug').on('keyup change', function(){
			var slug = $(this).val();
			$('#slug').val(convertToSlug(slug));
		});

		$('.lang-selection').on('keypress click', function(e){
			e.preventDefault();
			$('.lang-selection').parent().removeClass('active');
			$(this).parent().addClass('active');
			$('.lang-field').hide();
			$('[data-lang=lang-'+$(this).data("langselection")+']').fadeIn(230);
			$('.lang-active').text($(this).data("langselection").toUpperCase());
		});

		$('.lang-selection').first().click();

    </script>

@stop
