@extends('cms::layouts/default')

{{-- Page title --}}
@section('title')
Adicionar Data Source ::
@parent
@stop

{{-- Page content --}}
@section('content')

	<div class="row">
      <div class="col-lg-12">
          <ul class="breadcrumb pull-left">
              <li><a href="{{ route('cms') }}"><i class="icon-home"></i> Home</a></li>
              <li><a href="{{ route('datasources') }}">Data Sources</a></li>
              <li><span class="active">Adicionar</span></li>
          </ul>
          <a href="{{ route('datasources') }}" class="btn btn-small btn-info pull-right"><i class="icon-circle-arrow-left icon-white"></i> Voltar</a>
      </div>
  </div>

  <hr class="top-line" />

  <div class="row">
	  <div class="col-lg-12">
		<section class="panel">
			<header class="panel-heading form-group">Nova Data Source</header>
			<div class="panel-body">

				<form class="form-horizontal tasi-form" method="post" action="" autocomplete="off">
					<input type="hidden" name="_token" value="{{ csrf_token() }}" />
	              		
	                <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
						<label for="name" class="col-lg-2 control-label">Nome</label>
						<div class="col-lg-5">
							<input type="text" class="form-control" name="name" id="name" value="{{ Input::old('name') }}" />
	                     	{{ $errors->first('name', '<p class="help-block">:message</p>') }}
						</div>
					</div>

					<div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
						<label for="subitems" class="col-lg-2 control-label">Sub Items</label>
						<div class="col-lg-5">
							<div class="switch switch-square subitems" data-on-label="<i class=' fa fa-check'></i>" data-off-label="<i class='fa fa-remove'></i>">
                            	<input type="checkbox" name="subitems" id="subitems" />
                          	</div>
                          	{{ $errors->first('subitems', '<p class="help-block">:message</p>') }}
						</div>
					</div>

					<div class="row">
						<label for="name" class="col-lg-2 control-label">Tabela</label>
		                <div class="col-sm-8">
		                      <section class="panel">
		                        
		                          <table class="table table-striped">
		                              <thead>
		                              <tr>
		                                  <th>Nome</th>
		                                  <th>Datatype</th>
		                                  <th>Mostrar na Listagem</th>
		                              </tr>
		                              </thead>
		                              <tbody id="table_definition">
			                              <tr class="table_definition_item">
			                                  <td> <input type="text" class="form-control col_description" value="" /> </td>
			                                  <td> 
			                                  	<select class="form-control col_datatype" >
			                                  	@foreach($datasourceFieldtypes as $fieldtype)
			                                  		<option value="{{ $fieldtype->id }}">{{ $fieldtype->name }}</option>
			                                  	@endforeach
			                                  	</select>
			                                  </td>
			                                  <td> 
			                                  	<div class="switch switch-square col_show_in_table" data-on-label="<i class=' fa fa-check'></i>" data-off-label="<i class='fa fa-remove'></i>">
				                                	<input type="checkbox" class="" />
				                              	</div>
			                                  </td>
			                              </tr>
		                              </tbody>
		                          </table>
		                          <input id="table_config" type="hidden" name="table_config">
		                      </section>
		                  </div>
		              </div>

		              <div class="form-group">
						<div class="col-lg-offset-2 col-lg-8 text-right">
		            		<button type="button" class="btn btn-default" id="bt-lessConfigs" style="display: none"><i class="fa fa-minus"></i></button>
		            		<button type="button" class="btn btn-default" id="bt-moreConfigs"><i class="fa fa-plus"></i></button>
		            	</div>
		            </div>
					<div class="form-group">
						<div class="col-lg-offset-2 col-lg-10">
							<button class="btn btn-danger" type="submit">Adicionar</button>
							<a class="btn btn-default" href="{{ route('datasources') }}">Cancelar</a>
						</div>
					</div>

					
	
				</form>
			</div>
		</section>
	  <div>
	</div>

@stop

@section('styles')

@stop

@section('scripts')
	<script type="text/javascript" src="{{ asset(Config::get('cms::config.assets_path').'/assets/js/bootstrap-switch.js') }}"></script>

	<script type="text/javascript" charset="utf-8">	

	$('form').submit(function( event ) {
		$("#table_config").val(JSON.stringify(table_config.get()));
	});

	$("#bt-moreConfigs").click(function(){
		table_config.create();
	});

	$("#bt-lessConfigs").click(function(){
		table_config.remove();
	});

	var table_config = {
		elem: $("#table_definition"),
		datatypeOptions: $('.col_datatype').clone(),
		get: function(){
			var table_config_list = [];
			table_config.elem.children().each(function(){
				if($(this).find('.col_description').val()){
					table_config_list.push({
						description: $(this).find('.col_description').val(), 
						datatype: $(this).find('.col_datatype').val(),
						show_in_table: $(this).find('.col_show_in_table').bootstrapSwitch('status') ?1:0
					});
				}
			});

			return table_config_list;
		},

		create: function(){
			var row = table_config.elem.children().size()+1;
			table_config.elem.append('<tr id="table_definition_item-'+row+'" class="table_definition_item"><td><input type="text" class="form-control col_description" value="" /></td><td><select class="form-control col_datatype" >'+table_config.datatypeOptions.html()+'</select></td><td><div class="switch switch-square col_show_in_table" data-on-label="<i class=\' fa fa-check\'></i>" data-off-label="<i class=\'fa fa-remove\'></i>"><input type="checkbox" /></div></td></tr>');

			$('#table_definition_item-'+row).find('.switch')['bootstrapSwitch']();
			
			$("#bt-lessConfigs").show(); 
		},

		remove: function(){
			if(table_config.elem.children().size()>0){
				$('.table_definition_item:last-child', table_config.elem).remove();
				if(table_config.elem.children().size()==1) { $("#bt-lessConfigs").hide(); }
			}
		}
	};

    </script>
@stop
