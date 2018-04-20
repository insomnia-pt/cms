@extends('cms::layouts/default')

{{-- Page title --}}
@section('title')
Editar Data Source ::
@parent
@stop

{{-- Page content --}}
@section('content')

	<div class="row">
      <div class="col-lg-12">
          <ul class="breadcrumb pull-left">
              <li><a href="{{ route('cms') }}"><i class="icon-home"></i> Home</a></li>
              <li><a href="{{ route('datasources') }}">Data Sources</a></li>
              <li><span class="active">Editar</span></li>
          </ul>
          <a href="{{ route('datasources') }}" class="btn btn-small btn-default pull-right"><i class="icon-circle-arrow-left icon-white"></i> Voltar</a>
      </div>
  </div>

  <hr class="top-line" />

  <div class="row">
	  <div class="col-lg-12">
		<section class="panel">
			<header class="panel-heading tab-bg-dark-navy-blue ">
				<ul class="nav nav-tabs">
					<li class="active"><a href="#tab-general" data-toggle="tab">Geral</a></li>
          			<li><a href="#tab-fields" data-toggle="tab">Campos</a></li>
          			<li><a href="#tab-relations" data-toggle="tab">Relações</a></li>
				</ul>
			</header>


			<div class="panel-body">
				<header class="panel-heading form-group">Detalhes do Data Source - <b>{{ $datasource->name }}</b></header>
				<div class="tab-content">
					<div id="tab-general" class="tab-pane active">
						<form class="form-horizontal tasi-form" method="post" action="" autocomplete="off">

							<input type="hidden" name="_token" value="{{ csrf_token() }}" />

			                <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
								<label for="name" class="col-lg-2 control-label">Nome</label>
								<div class="col-lg-5">
									<input type="text" class="form-control" name="name" id="name" value="{{ Input::old('name', $datasource->name) }}" />
			                     	{{ $errors->first('name', '<p class="help-block">:message</p>') }}
								</div>
							</div>

							<div class="form-group {{ $errors->has('table') ? 'has-error' : '' }}">
								<label for="table" class="col-lg-2 control-label">Tabela</label>
								<div class="col-lg-4">
									<input type="text" class="form-control" name="table" id="table" value="{{ Input::old('table', $datasource->table) }}" disabled />
			                     	{{ $errors->first('table', '<p class="help-block">:message</p>') }}
								</div>
							</div>

				            <div class="form-group">
								<div class="col-lg-12 text-right">
									<button class="btn btn-danger" type="submit">Guardar</button>
									<a class="btn btn-default" href="{{ route('datasources') }}">Cancelar</a>
								</div>
							</div>


						</form>
			        </div>

			        <div id="tab-fields" class="tab-pane">

			        	<button class="pull-right btn btn-xs btn-info" data-toggle="modal" data-target="#modal-new_field">Adicionar Campo</button>
			        	<br /><br />
			        	<table class="table">
			        		<tr>
				        		<th>Descrição</th>
				        		<th>Coluna</th>
				        		<th>Tipo</th>
				        		<th class="text-center">Listagem</th>
				        		<th class="text-center">Traduzir</th>
				        		<th class="text-center">Largura</th>
				        		<th></th>
				        	</tr>
			        		@foreach($datasource->config() as $configs)
				        	<tr>
				        		<td>{{ $configs->description }}</td>
				        		<td>{{ $configs->name }}</td>
				        		<td>
				        			@foreach($datasourceFieldtypes as $fieldtype)
		                                @if($configs->datatype==$fieldtype->id) {{ $fieldtype->name }} @endif
		                           @endforeach
		                        </td>
				        		<td class="text-center"><i class="fa {{ @$configs->show_in_table?'fa-check':'fa-close' }}"></i></td>
								<td class="text-center"><i class="fa {{ @$configs->multilang?'fa-check':'fa-close' }}"></i></td>
								<td class="text-center">{{ @$configs->size }}</td>
								<td class="text-right">
									<button class="btn btn-xs btn-default bt-edit_field" data-toggle="modal" data-target="#modal-edit_field"
										data-description="{{ $configs->description }}"
										data-name="{{ $configs->name }}"
										data-datatype="{{ $configs->datatype }}"
										data-showintable="{{ @$configs->show_in_table }}"
										data-multilang="{{ @$configs->multilang }}"
										data-size="{{ @$configs->size }}"
										@if(@count($configs->parameters))
											@foreach($configs->parameters as $parameter => $value)
											data-{{ $parameter }}="{{ $value }}"
											@endforeach
										@endif
									>Editar</button>
									<a class="btn btn-xs btn-danger" data-msg="Confirma eliminar o campo?" data-reply="" data-toggle="modal" data-descr="{{ $configs->description }}" data-url="{{ route('delete/datasource/field', array($datasource->id, $configs->name)) }}" href="#modal-confirm">Eliminar</a>
								</td>
							</tr>
							@endforeach

			        	</table>



			        	<div class="form-group"></div>
			        </div>

			        <div id="tab-relations" class="tab-pane">

			        	<button type="button" class="pull-right btn btn-xs btn-info" data-toggle="modal" data-target="#modal-new_relation">Adicionar Relação</button>
			        	<br /><br />
			        	<table class="table">
			        		<tr>
			        			<th>Descrição</th>
			        			<th>Tipo</th>
			        			<th>Data Source</th>
			        			<th></th>
			        		</tr>

		        			@foreach($datasource->relations as $relation)
		        			<tr>
								<td>{{ $relation->relation_description }}</td>
								<td>@if($relation->relation_type=='hasOne') 1 - 1 @else {{ $relation->relation_type }} @endif</td>
								<td>{{ $relation->relationdatasource->table }}</td>
								<td class="text-right">
									<a class="btn btn-xs btn-danger" data-msg="Confirma eliminar a relação?" data-reply="" data-toggle="modal" data-descr="{{ $relation->relation_description }}" data-url="{{ route('delete/datasource/relation', array($datasource->id, $relation->id)) }}" href="#modal-confirm">Eliminar</a>
								</td>
							</tr>
                  			@endforeach
                  			@if(!count($datasource->relations))<td colspan="4">Não existem relações</td>@endif

			        	</table>

			        </div>
		        </div>

			</div>
		</section>
	  <div>
	</div>


	<div class="modal fade" id="modal-new_field" tabindex="-1" role="dialog" aria-hidden="true">
	  <div class="modal-dialog">
	      <div class="modal-content">
	      	<form class="form-horizontal" method="post" action="edit/field/create">
		          <div class="modal-header">
		              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		              <h4 class="modal-title">Novo Campo</h4>
		          </div>
		          <div class="modal-body">
		          	<input type="hidden" name="_token" value="{{ csrf_token() }}" />
		          	<div class="row panel-body">
		          		<div class="form-group">
							<label for="name" class="col-lg-2 control-label">Nome</label>
							<div class="col-lg-10">
								<input type="text" class="form-control" name="description" />
							</div>
						</div>

						<hr />

						<div class="form-group">
							<label for="datatype" class="col-lg-2 control-label">Tipo</label>
							<div class="col-lg-8">
								<select class="form-control" name="datatype" id="modal-new_field-datatype">
	                          	@foreach($datasourceFieldtypes as $fieldtype)
	                          		<option value="{{ $fieldtype->id }}">{{ $fieldtype->name }}</option>
	                          	@endforeach
	                          	</select>
							</div>
						</div>

						<div id="modal-new_field-datatype_parameters_area"></div>

                     	@foreach($datasourceFieldtypes as $fieldtype)
                  			<?php $fieldTypeParametersList = []; ?>
                  			@if(@$fieldtype->config()->parameters)
                      			@foreach($fieldtype->config()->parameters as $parameter)
                      				<?php array_push($fieldTypeParametersList, $parameter); ?>
                      			@endforeach
                      			<input id="modal-new_field-fieldtype_parameters_{{ $fieldtype->id }}" type="hidden" value="{{ implode(',',$fieldTypeParametersList) }}" />
                  			@endif
                  		@endforeach

						<hr />

						<div class="form-group">
							<label for="" class="col-lg-2 control-label">Listagem</label>
							<div class="col-lg-8">
								<div class="switch switch-square" data-on-label="<i class=' fa fa-check'></i>" data-off-label="<i class='fa fa-remove'></i>">
	                            	<input type="checkbox" name="show_in_table" class="" />
	                          	</div>
							</div>
						</div>

						<div class="form-group">
							<label for="" class="col-lg-2 control-label">Traduzir</label>
							<div class="col-lg-8">
								<div class="switch switch-square" data-on-label="<i class=' fa fa-check'></i>" data-off-label="<i class='fa fa-remove'></i>">
	                            	<input type="checkbox" name="multilang" class="" />
	                          	</div>
							</div>
						</div>

						<div class="form-group">
							<label for="size" class="col-lg-2 control-label">Largura</label>
							<div class="col-lg-8">
								<select class="form-control" name="size" id="modal-new_field-size">
	                          		<option value="1">1</option>
	                          		<option value="2">2</option>
	                          		<option value="3">3</option>
	                          		<option value="4">4</option>
	                          		<option value="5">5</option>
	                          		<option value="6">6</option>
	                          		<option value="7">7</option>
	                          		<option value="8">8</option>
	                          		<option value="9">9</option>
	                          		<option value="10">10</option>
	                          	</select>
							</div>
						</div>

		          	</div>

		          </div>
		          <div class="modal-footer">
		              <button data-dismiss="modal" class="btn btn-default" type="button">Cancelar</button>
		              <button type="submit" class="btn btn-danger"> Adicionar</button>
		          </div>
	          </form>
	      </div>
	  </div>
	</div>

	<div class="modal fade" id="modal-edit_field" tabindex="-1" role="dialog" aria-hidden="true">
	  <div class="modal-dialog">
	      <div class="modal-content">
	      	<form class="form-horizontal" method="post" action="">
		          <div class="modal-header">
		              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		              <h4 class="modal-title">Edição de Campo</h4>
		          </div>
		          <div class="modal-body">
		          	<input type="hidden" name="_token" value="{{ csrf_token() }}" />
		          	<div class="row table_relation_definition_item panel-body">
		          		<div class="form-group">
							<label for="description" class="col-lg-2 control-label">Descrição</label>
							<div class="col-lg-10">
								<input type="text" class="form-control" name="description" id="modal-edit_field-description" />
							</div>
						</div>

						<hr />

						<div class="form-group">
							<label for="name" class="col-lg-2 control-label">Campo</label>
							<div class="col-lg-8">
								<input type="text" class="form-control" name="name" id="modal-edit_field-name" />
							</div>
						</div>

						<hr />

						<div class="form-group">
							<label for="datatype" class="col-lg-2 control-label">Tipo</label>
							<div class="col-lg-6">
								<select class="form-control" name="datatype" id="modal-edit_field-datatype">
                              	@foreach($datasourceFieldtypes as $fieldtype)
                              		<option value="{{ $fieldtype->id }}">{{ $fieldtype->name }} </option>
                              	@endforeach
                              	</select>
							</div>
						</div>

						<div id="modal-edit_field-datatype_parameters_area"></div>

                     	@foreach($datasourceFieldtypes as $fieldtype)
                  			<?php $fieldTypeParametersList = []; ?>
                  			@if(@$fieldtype->config()->parameters)
                      			@foreach($fieldtype->config()->parameters as $parameter)
                      				<?php array_push($fieldTypeParametersList, $parameter); ?>
                      			@endforeach
                      			<input id="modal-edit_field-fieldtype_parameters_{{ $fieldtype->id }}" type="hidden" value="{{ implode(',',$fieldTypeParametersList) }}" />
                  			@endif
                  		@endforeach

						<hr />

						<div class="form-group">
							<label for="" class="col-lg-2 control-label">Listagem</label>
							<div class="col-lg-8">
								<div class="switch switch-square" data-on-label="<i class=' fa fa-check'></i>" data-off-label="<i class='fa fa-remove'></i>">
	                            	<input type="checkbox" name="show_in_table" id="modal-edit_field-show_in_table" class="" />
	                          	</div>
							</div>
						</div>

						<div class="form-group">
							<label for="" class="col-lg-2 control-label">Traduzir</label>
							<div class="col-lg-8">
								<div class="switch switch-square" data-on-label="<i class=' fa fa-check'></i>" data-off-label="<i class='fa fa-remove'></i>">
	                            	<input type="checkbox" name="multilang" id="modal-edit_field-multilang" class="" />
	                          	</div>
							</div>
						</div>

						<div class="form-group">
							<label for="size" class="col-lg-2 control-label">Largura</label>
							<div class="col-lg-3">
								<select class="form-control" name="size" id="modal-edit_field-size">
	                          		<option value="1">1</option>
	                          		<option value="2">2</option>
	                          		<option value="3">3</option>
	                          		<option value="4">4</option>
	                          		<option value="5">5</option>
	                          		<option value="6">6</option>
	                          		<option value="7">7</option>
	                          		<option value="8">8</option>
	                          		<option value="9">9</option>
	                          		<option value="10">10</option>
	                          	</select>
							</div>
						</div>
		          	</div>

		          </div>
		          <div class="modal-footer">
		              <button data-dismiss="modal" class="btn btn-default" type="button">Cancelar</button>
		              <button type="submit" class="btn btn-danger"> Guardar Alterações</button>
		          </div>
	          </form>
	      </div>
	  </div>
	</div>


	<div class="modal fade" id="modal-new_relation" tabindex="-1" role="dialog" aria-hidden="true">
	  <div class="modal-dialog">
	      <div class="modal-content">
	      	<form class="form-horizontal" method="post" action="edit/relation/create">
		          <div class="modal-header">
		              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		              <h4 class="modal-title">Nova Relação</h4>
		          </div>
		          <div class="modal-body table_relation_definition">
		          	<input type="hidden" name="_token" value="{{ csrf_token() }}" />
		          	<div class="row table_relation_definition_item panel-body">
		          		<div class="form-group">
							<label for="name" class="col-lg-2 control-label">Descrição</label>
							<div class="col-lg-10">
								<input type="text" class="form-control" name="description" />
							</div>
						</div>

						<hr />

						<div class="form-group">
							<label for="type" class="col-lg-2 control-label">Tipo</label>
							<div class="col-lg-4">
								<select class="form-control" name="type">
	                          		<option value="hasOne">1 - 1</option>
	                          		<option value="hasMany">1 - *</option>
	                         		<!-- <option value="belongsToMany">* - *</option> -->
	                         	</select>
							</div>
						</div>

						<hr />

						<div class="form-group">
							<label for="datasource" class="col-lg-2 control-label">Datasource</label>
							<div class="col-lg-6">
								<select class="form-control relation_datasource" name="datasource">
	                          		<?php $datasourceRelationsIds = []; ?>
	                          		@foreach($datasource->relations as $relationItem)
	                         			<?php array_push($datasourceRelationsIds, $relationItem->relation_datasource_id); ?>
	                         		@endforeach

	                          		@foreach($datasources as $datasourcesItem)
	                          			@if($datasourcesItem->id!=$datasource->id && !in_array($datasourcesItem->id, $datasourceRelationsIds))
	                          				<option value="{{ $datasourcesItem->id }}">{{ $datasourcesItem->name }}</option>
	                          			@endif
	                          		@endforeach
	                          	</select>
							</div>
						</div>

						<hr />

						<div class="form-group">
							<label for="identify" class="col-lg-2 control-label">Identificador</label>
							<div class="col-lg-5">
								<select class="form-control relation_identify" name="identify">

	                         	</select>
	                         	@foreach($datasources as $datasourcesItem)
	                      			@if($datasourcesItem->id!=$datasource->id)
	                          			<?php $datasourceConfigsList = []; ?>
	                          			@if($datasourcesItem->config())
		                          			@foreach($datasourcesItem->config() as $datasourcesItemConfig)
		                          				<?php array_push($datasourceConfigsList, $datasourcesItemConfig->name); ?>
		                          			@endforeach
		                          			<input id="datasource_identify_{{ $datasourcesItem->id }}" type="hidden" value="{{ implode(',',$datasourceConfigsList) }}" />
	                          			@endif
	                      			@endif
	                      		@endforeach
							</div>
						</div>
		          	</div>

		          </div>
		          <div class="modal-footer">
		              <button data-dismiss="modal" class="btn btn-default" type="button">Cancelar</button>
		              <button type="submit" class="btn btn-danger"> Adicionar</button>
		          </div>
	          </form>
	      </div>
	  </div>
	</div>



@stop

@section('styles')
@stop

@section('scripts')
	<script type="text/javascript" src="{{ Helpers::asset(Config::get('cms::config.assets_path').'/assets/js/bootstrap-switch.js') }}"></script>
	<script type="text/javascript" charset="utf-8">

		var fieldEditing;
		$('.bt-edit_field').click(function(){
			fieldEditing = $(this);
			$('#modal-edit_field form').attr('action', 'edit/field/'+fieldEditing.data('name')+'/edit');
			$('#modal-edit_field-description').val(fieldEditing.data('description'));
			$('#modal-edit_field-name').val(fieldEditing.data('name'));
			$('#modal-edit_field-datatype').val(fieldEditing.data('datatype'));
			$('#modal-edit_field-show_in_table').parent().bootstrapSwitch('setState', fieldEditing.data('showintable'));
			$('#modal-edit_field-multilang').parent().bootstrapSwitch('setState', fieldEditing.data('multilang'));
			$('#modal-edit_field-size').val(fieldEditing.data('size'));

			$('#modal-edit_field-datatype').change();
		});


		$('.relation_datasource').change(function(){
			var thisDatasource = $(this);
			var thisDatasourceParent = $(this).closest('.table_relation_definition_item');
			var thisDatasourceIdentify = thisDatasourceParent.find('.relation_identify');

			var thisIndentifyOptions = ($("#datasource_identify_"+thisDatasource.val()).val()).split(',');

			thisDatasourceIdentify.find('option').remove();
			$.each(thisIndentifyOptions, function (i, item) {
			    thisDatasourceIdentify.append($('<option>', {
			        value: item,
			        text : item
			    }));
			});
		});
		$('.relation_datasource').change();


		$('#modal-new_field-datatype').change(function(){
			var thisDataType = $(this);
			var thisDataTypeParametersArea = $('#modal-new_field-datatype_parameters_area');
			thisDataTypeParametersArea.html('');

			if($("#modal-new_field-fieldtype_parameters_"+thisDataType.val()).val()){
				var thisParameters = ($("#modal-new_field-fieldtype_parameters_"+thisDataType.val()).val()).split(',');

				$.each(thisParameters, function (i, item) {
				    thisDataTypeParametersArea.append('<div class="form-group"><label for="'+item+'" class="col-lg-2 control-label">'+item+'</label><div class="col-lg-8"><input type="text" class="form-control" name="parameters['+item+']" /></div></div>');
				});
			}
		});

		$('#modal-edit_field-datatype').change(function(){
			var thisDataType = $(this);
			var thisDataTypeParametersArea = $('#modal-edit_field-datatype_parameters_area');
			thisDataTypeParametersArea.html('');

			if($("#modal-edit_field-fieldtype_parameters_"+thisDataType.val()).val()){
				var thisParameters = ($("#modal-edit_field-fieldtype_parameters_"+thisDataType.val()).val()).split(',');

				$.each(thisParameters, function (i, item) {
				    thisDataTypeParametersArea.append('<div class="form-group"><label for="'+item+'" class="col-lg-2 control-label">'+item+'</label><div class="col-lg-8"><input type="text" class="form-control" name="parameters['+item+']" value="'+(fieldEditing.data(item)?fieldEditing.data(item):'')+'" /></div></div>');
				});
			}
		});




		$(window).on('hashchange',function(){
			var hash = window.location.hash;
			$('.nav-tabs a[href="#tab-' + hash.substr(1) + '"]').tab('show');
		});

		var hash = window.location.hash;
		$('.nav-tabs a[href="#tab-' + hash.substr(1) + '"]').tab('show');

		$("ul.nav-tabs > li > a").on("shown.bs.tab", function(e) {
			var id = $(e.target).attr("href").substr(5);
		  	window.location.hash = id;
		});

    </script>
@stop
