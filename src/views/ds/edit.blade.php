@extends('cms::layouts/'.($parameters['modal']?'modal':'default'))

{{-- Page title --}}
@section('title')
Editar Registo ::
@parent
@stop

{{-- Page content --}}
@section('content')

	<div class="row">
      	<div class="col-lg-12">

		  	@if(!$parameters['modal'])
        	<ul class="breadcrumb pull-left">
            	<li><a href="{{ route('cms') }}"><i class="icon-home"></i> Home</a></li>
              	<li><a href="{{ route('cms/ds', $datasource->id) }}">{{ $datasource->name }}</a></li>
              	<li><span class="active">Editar</span></li>
          	</ul>
			@endif

	        <a href="{{ route('cms/ds', $datasource->id) }}@if($parameters['pds'])?pds={{$parameters['pds']}}&item={{$parameters['item']}}@if($parameters['modal'])&modal=true @endif @elseif($parameters['modal'])?modal=true @endif" class="btn btn-small btn-default pull-right" @if($parameters['modal']) style="margin-bottom: 10px" @endif><i class="icon-circle-arrow-left icon-white"></i> Voltar</a>
    	</div>
	</div>

  	<hr class="top-line" />

	<div class="row">
		<form method="post" action="" autocomplete="off">
			<div class="col-lg-{{ $hasSettings?'9':'12' }}">
				<section class="panel">
					<header class="panel-heading">Detalhes do Registo</header>

					<ul class="pull-right nav nav-tabs" style="margin: -40px 10px 0 0">
						<li><i title="Idiomas" class="fa fa-language" style="padding:14px 12px 0 0"></i> </li>
						@foreach($languages as $langkey => $language)
						<li><a class="lang-selection" data-toggle="tab" data-langselection="{{ $langkey }}" href="#" >{{ $language }}</a></li>
						@endforeach
					</ul>

					<div class="panel-body form-horizontal tasi-form">
					
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
										<input type="hidden" name="id_parent" id="id_parent" value="{{ $dsItem->id_parent }}" class="form-control easy-tree-selected" />
										<div class="input-group easy-tree-openlist">
											<input value="{{ @$datasource->config()[0]->multilang ? @json_decode(@$dsItems->find($dsItem->id_parent)->{$datasource->config()[0]->name})->{$settings->language} : @$dsItems->find($dsItem->id_parent)->{$datasource->config()[0]->name} }}" type="text" class="form-control easy-tree-selected-text" placeholder="Selecione.." readonly />
											<div class="input-group-addon"><i class="fa fa-chevron-down"></i></div>
										</div>
										<div class="easy-tree-list">
											<ul>

												@foreach ($parentItems as $parentitem)
												@include('cms::ds._treeview-combolist', array('item' => $parentitem, 'selected'=> $dsItem->id_parent, 'editing'=>$dsItem->id ))
												@endforeach

											</ul>
										</div>
									</div>
								</div>
							</div>
						@endif

						@foreach($datasource->relations as $relation)
							
							<?php
								$relationDatasource = Insomnia\Cms\Models\Datasource::find($relation->relation_datasource_id);
								$relationTable = $relationDatasource->table;
							?>
							
							@if($relation->relation_type=="belongsToMany" && $relation->config()->area == "form")
								<?php 
									$relationDatasourceItems = CMS_ModelBuilder::fromTable($relationTable)->get(); 
									$relationItems = CMS_ModelBuilder::fromTable($datasource->table.'_'.$relationDatasource->table);
									$relationItemsIds = $relationItems->where($datasource->table.'_id', $dsItem->id)->lists($relationTable.'_id');
								?>

								<div class="form-group">
									<label for="" class="col-lg-2 control-label">{{ $relation->relation_description }}</label>
									<div class="col-lg-6">
						
										<select name="{{ $relationTable }}[]" class="multiselect" multiple="multiple">
											@foreach($relationDatasourceItems as $relationDatasourceItem)
											<option value="{{ $relationDatasourceItem->id }}" @if(in_array($relationDatasourceItem->id, $relationItemsIds)) selected @endif>{{ $relationDatasourceItem[$relation->config()->fields[0]] }}</option>
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
											<input type="hidden" name="{{ $relationTable }}_id" id="{{ $relationTable }}_id" value="{{ $dsItem->{$relationTable.'_id'} }}" class="form-control easy-tree-selected"  />
											<div class="input-group easy-tree-openlist">
												<input value="{{ @$field->multilang ? @json_decode(@$dsItems->find($dsItem->{$relationTable.'_id'})->{$relation->config()->fields[0]})->{$settings->language} : @$dsItems->find($dsItem->{$relationTable.'_id'})->{$relation->config()->fields[0]} }}" type="text" class="form-control easy-tree-selected-text" placeholder="Selecione.." readonly />
												<div class="input-group-addon"><i class="fa fa-chevron-down"></i></div>
											</div>
											<div class="easy-tree-list">
												<ul>
													@foreach ($parentItems as $parentitem)
													@include('cms::ds._treeview-combolist', array('item' => $parentitem, 'relation' => $relation, 'selected'=> $dsItem->{$relationTable.'_id'}))
													@endforeach
												</ul>
											</div>
										</div>

									</div>
								</div>
							@endif
							
						@endforeach

						@foreach($datasource->config() as $config)

							@if(@$config->multilang)
								@foreach($languages as $langkey => $language)
								
								<div data-lang="lang-{{ $langkey }}"  class="form-group lang-field {{ $errors->has($config->name.'['.$langkey.']') ? 'has-error' : '' }}">
									<label for="{{ $config->name }}[{{ $langkey }}]" class="col-lg-2 control-label">{{ $config->description }} &nbsp;<i title="Campo com possibilidade de tradução" class="fa fa-language"></i> <small class="lang-active text-muted"></small></label>
									<div class="col-lg-{{ $config->size }}">

										@include('cms::components.'.$datasourceFieldtypes->find($config->datatype)->config()->field, ['component' => ['name' => $config->name.'['.$langkey.']', 'data' => Input::old($config->name.'.'.$langkey, @json_decode($dsItem->{$config->name})->{$langkey}), 'limit' => @$config->parameters->limit, 'extensions' => @$config->parameters->extensions, 'items' => @$config->parameters->values, 'folder' => @$config->parameters->folder, 'compress' => @$config->parameters->compress ]])

										{{ $errors->first($config->name.'['.$langkey.']', '<p class="help-block">:message</p>') }}
									</div>
								</div>

								@endforeach
							@else

								<div class="form-group {{ $errors->has($config->name) ? 'has-error' : '' }}">
									<label for="{{ $config->name }}" class="col-lg-2 control-label">{{ $config->description }}</label>
									<div class="col-lg-{{ $config->size }}">

										@include('cms::components.'.$datasourceFieldtypes->find($config->datatype)->config()->field, ['component' => ['name' => $config->name, 'data' => Input::old($config->name, $dsItem[$config->name]), 'limit' => @$config->parameters->limit, 'extensions' => @$config->parameters->extensions, 'items' => @$config->parameters->values, 'folder' => @$config->parameters->folder, 'compress' => @$config->parameters->compress ]])

										{{ $errors->first($config->name, '<p class="help-block">:message</p>') }}
									</div>
								</div>

							@endif
						@endforeach

						<div class="form-group">
							<div class="col-lg-12 text-right">
								@if(CMS_Helper::checkPermission($datasource->table.'.update'))<button class="btn btn-danger" type="submit">Guardar</button>@endif
								<a class="btn btn-default" href="{{ route('cms/ds', $datasource->id) }}@if($parameters['pds'])?pds={{$parameters['pds']}}&item={{$parameters['item']}}@if($parameters['modal'])&modal=true @endif @elseif($parameters['modal'])?modal=true @endif">Voltar</a>
							</div>
						</div>

					</div>
				</section>
			</div>

			@if($hasSettings)
			<div class="col-lg-3">
				

				@foreach($datasource->relations as $relation)
					
					<?php
						$relationDatasource = Insomnia\Cms\Models\Datasource::find($relation->relation_datasource_id);
						$relationTable = $relationDatasource->table;
					?>
					
					@if($relation->relation_type=="belongsToMany" && $relation->config()->area == "settings")
						<?php 
							$relationDatasourceItems = CMS_ModelBuilder::fromTable($relationTable)->get(); 
							$relationItems = CMS_ModelBuilder::fromTable($datasource->table.'_'.$relationDatasource->table);
							$relationItemsIds = $relationItems->where($datasource->table.'_id', $dsItem->id)->lists($relationTable.'_id');
						?>

						<section class="panel">
							<header class="panel-heading">{{ $relation->relation_description }}</header>
							<div class="panel-body form-horizontal tasi-form">

								<div class="form-group">
									<div class="col-lg-12">
						
										<select name="{{ $relationTable }}[]" class="multiselect" multiple="multiple">
											@foreach($relationDatasourceItems as $relationDatasourceItem)
											<option value="{{ $relationDatasourceItem->id }}" @if(in_array($relationDatasourceItem->id, $relationItemsIds)) selected @endif>{{ $relationDatasourceItem[$relation->config()->fields[0]] }}</option>
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
											<input type="hidden" name="{{ $relationTable }}_id" id="{{ $relationTable }}_id" value="{{ $dsItem->{$relationTable.'_id'} }}" class="form-control easy-tree-selected"  />
											<div class="input-group easy-tree-openlist">
												<input value="{{ @$field->multilang ? @json_decode(@$dsItems->find($dsItem->{$relationTable.'_id'})->{$relation->config()->fields[0]})->{$settings->language} : @$dsItems->find($dsItem->{$relationTable.'_id'})->{$relation->config()->fields[0]} }}" type="text" class="form-control easy-tree-selected-text" placeholder="Selecione.." readonly />
												<div class="input-group-addon"><i class="fa fa-chevron-down"></i></div>
											</div>
											<div class="easy-tree-list">
												<ul>
													@foreach ($parentItems as $parentitem)
													@include('cms::ds._treeview-combolist', array('item' => $parentitem, 'relation' => $relation, 'selected'=> $dsItem->{$relationTable.'_id'}))
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
    <script type="text/javascript" src="{{ Helpers::asset(Config::get('cms::config.assets_path').'/assets/js/jquery.tagsinput.js') }}"></script>
    <script src="{{ Helpers::asset(Config::get('cms::config.assets_path').'/assets/js/jquery.popupWindow.js') }}"></script>
    <script type="text/javascript" src="{{ Helpers::asset(Config::get('cms::config.assets_path').'/assets/js/bootstrap-switch.js') }}"></script>
    <script src="{{ Helpers::asset(Config::get('cms::config.assets_path').'/assets/plugins/ckeditor/ckeditor.js') }}"></script>
    <script type="text/javascript" src="{{ Helpers::asset(Config::get('cms::config.assets_path').'/assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js') }}"></script>
    <script type="text/javascript" src="{{ Helpers::asset(Config::get('cms::config.assets_path').'/assets/plugins/bootstrap-daterangepicker/date.js') }}"></script>
    <script type="text/javascript" src="{{ Helpers::asset(Config::get('cms::config.assets_path').'/assets/js/bootstrap-multiselect.js') }}"></script>
    <script type="text/javascript" src="{{ Helpers::asset(Config::get('cms::config.assets_path').'/assets/js/easyTree.js') }}"></script>
		@yield('subscripts')

    <script type="text/javascript">
		$('.easy-tree').EasyTree();
		$('.multiselect').multiselect({
			enableCaseInsensitiveFiltering: true,
			includeSelectAllOption: true,
			numberDisplayed: 1
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
