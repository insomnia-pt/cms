@section('substyles')
    <link href="{{ Helpers::asset(Config::get('cms::config.assets_path').'/assets/plugins/data-tables/jquery.dataTables.css') }}" rel="stylesheet">
@stop

@section('subscripts')
    <script type="text/javascript" src="{{ Helpers::asset(Config::get('cms::config.assets_path').'/assets/plugins/data-tables/jquery.dataTables.js') }}"></script>
    <script type="text/javascript" src="{{ Helpers::asset(Config::get('cms::config.assets_path').'/assets/plugins/data-tables/dataTables.rowReorder.min.js') }}"></script>
    <script type="text/javascript" src="{{ Helpers::asset(Config::get('cms::config.assets_path').'/assets/plugins/data-tables/DT_bootstrap.js') }}"></script>
    <script type="text/javascript" src="{{ Helpers::asset(Config::get('cms::config.assets_path').'/assets/js/ds-table.js') }}"></script>
    <script type="text/javascript" src="{{ Helpers::asset(Config::get('cms::config.assets_path').'/assets/js/bootstrap-switch.js') }}"></script>
    <script>
        $(function() {
            component_switch(1);

            $('#main_table').dataTable().fnSettings().aoDrawCallback.push({
                "fn": function () {
                    component_switch(0);
                }
            });
        });


        function component_switch(dataTableFirstLoad) {
            var elmts;
            if(dataTableFirstLoad){
                elmts = $('.switch_cmp');
            }
            elmts =  elmts?elmts:$('.switch_cmp:not(.has-switch)');

            elmts.on('switch-change', function (e, data) {
                var updatedata = {
                    id : data.el.data('entryid')
                };
                updatedata[data.el.data('field')] = data.value?1:0;
                $.post("{{ route('cms/ds/edit/fromcomponent', $datasource->id) }}", updatedata, function(data) {});
            });

            $('.switch_cmp:not(.has-switch)').bootstrapSwitch();

        }

    </script>
@stop


<section class="panel panel-primary">
      <header class="panel-heading">
        Lista de Registos
        @if($parameters['pds'])
          - <b>{{ $parentDatasource->config()[0]->multilang ? @json_decode($parentDatasourceItem->{$parentDatasource->config()[0]->name})->{$settings->language} : $parentDatasourceItem[$parentDatasource->config()[0]->name] }}</b>
        @endif
        <input class="form-control pull-right input-smmm" type="text" id="dataTable1filter" placeholder="Procurar.." style="width: 200px; margin-left: 10px;">
				@if(@!$datasource->options()->orderby)
				<button type="button" class="btn btn-xs btn-default pull-right" data-status-on="Cancelar" data-status-off="Reordenar" id="bt-tableorder" style="margin-top:-2px"><span>Reordenar</span> <i class="fa fa-sort-amount-asc"></i></button>
				<form class="pull-right" action="{{ route('cms/ds/order', $datasource->id) }}@if($parameters['pds'])?pds={{$parameters['pds']}}&item={{$parameters['item']}} @endif" id="form-savetableorder" method="post" style="margin-top:-2px;margin-right:5px;display:none;">
					<input type="hidden" name="ds-orderlist" id="ds-orderlist" value="">
					<button type="submit" class="btn btn-xs btn-danger">Guardar Alterações</button>
				</form>
				@endif
      </header>


      <table class="table table-striped border-top table-hover table-no-top-border" id="main_table">
      <thead>
          <tr>
            <td>
                #
            </td>
        	@foreach ($datasource->config() as $config)
        		@if($config->show_in_table)<th @if(@$config->parameters->mini_cmp) class="nosort text-center" @endif>{{ $config->description }}</th>@endif
        	@endforeach
              <th class="nosort"></th>
          </tr>
      </thead>

      <tbody>
      	@foreach ($dsItems as $index=>$dsItem)
    		<tr class="odd gradeX" id="row-{{ $dsItem->id }}">
					<td>{{ $index }}</td>
    			<?php $firstTableField = null; ?>
    			@foreach ($datasource->config() as $config)
        		@if($config->show_in_table)
                <td @if(@$config->parameters->mini_cmp) class="nosort text-center" @endif>
                    @if(!$firstTableField)<div class="dd-handle"></div> @endif
                    @if(@$config->parameters->mini_cmp)
                            @include('cms::components.'.$datasourceFieldtypes->find($config->datatype)->config()->field.'_mini', ['component' => ['entry_id' => $dsItem->id, 'name' => $config->name, 'data' => Input::old($config->name, $dsItem[$config->name]), 'limit' => @$config->parameters->limit, 'extensions' => @$config->parameters->extensions, 'items' => @$config->parameters->values, 'folder' => @$config->parameters->folder ]])
                        @else
                        
                        {{ $config->multilang ? @json_decode($dsItem->{$config->name})->{$settings->language} : $dsItem[$config->name] }}
                 @endif
                 <?php $firstTableField = $firstTableField?$firstTableField:$dsItem[$config->name]; ?>

                </td>
                @endif
        	@endforeach

    			<td class="text-right">
    				@foreach($datasource->relations as $relation)
              <?php
                $relationTable = Insomnia\Cms\Models\Datasource::find($relation->relation_datasource_id)->table;
              ?>
    					@if($relation->relation_type=="hasMany")<a href="{{ route('cms/ds', $relation->relation_datasource_id).'?pds='.$datasource->id.'&item='.$dsItem->id }}" class="btn btn-xs btn-info">{{ $relation->relation_description }}</a>@endif
              @if($relation->relation_type=="hasOne")
                @if($relationTable == 'pages' && $dsItem->{'pages_id'})
                <a href="{{ route('pages/edit', $dsItem->{'pages_id'}) }}" target="_blank" class="btn btn-xs"><i class="fa fa-external-link"></i> Editar {{ $relation->relation_description }}</a>
                @endif
              @endif
    				@endforeach
    				<a href="{{ route('cms/ds/edit', array($datasource->id, $dsItem->id)) }}@if($parameters['pds'])?pds={{$parameters['pds']}}&item={{$parameters['item']}} @endif" class="btn btn-xs btn-default">
              @if(CMS_Helper::checkPermission($datasource->table.'.update'))
                @lang('cms::button.edit')
              @else
                @lang('cms::button.view')
              @endif
            </a>
            @if(CMS_Helper::checkPermission($datasource->table.'.delete'))
    				<a class="btn btn-xs btn-danger" data-msg="Confirma eliminar o registo?" data-reply="" data-toggle="modal" data-descr="{{ $firstTableField }}" data-url="{{ route('cms/ds/delete', array($datasource->id, $dsItem->id)) }}" href="#modal-confirm">@lang('cms::button.delete')</a>
            @endif
    			</td>
    		</tr>
    		@endforeach

      </tbody>
      </table>
  	</section>


