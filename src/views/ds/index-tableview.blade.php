	<section class="panel panel-primary">
      <header class="panel-heading">
        Lista de Registos 
        @if($parameters['pds']) 
          - <b>{{ $parentDatasourceItem[Str::slug($parentDatasource->config()[0]->description, '_')] }}</b> 
        @endif
        <input class="form-control pull-right input-smmm" type="text" id="dataTable1filter" placeholder="Procurar.." style="width: 200px">
      </header>

      <table class="table table-striped border-top table-hover table-no-top-border" id="main_table">
      <thead>
          <tr>
        	@foreach ($datasource->config() as $config)
        		@if($config->show_in_table)<th>{{ $config->description }}</th>@endif
        	@endforeach
        		<th></th>
          </tr>
      </thead>
      <tbody>
      	@foreach ($dsItems as $dsItem)
		<tr class="odd gradeX">
			
			@foreach ($datasource->config() as $config)
        		@if($config->show_in_table)<td>{{ $dsItem[$config->name] }}</td>@endif
        	@endforeach

			<td class="text-right">
				@foreach($datasource->relations as $relation)
          <?php 
            $relationTable = Datasource::find($relation->relation_datasource_id)->table;
          ?>
					@if($relation->relation_type=="hasMany")<a href="{{ route('admin/ds', $relation->relation_datasource_id).'?pds='.$datasource->id.'&item='.$dsItem->id }}" class="btn btn-xs btn-info">{{ $relation->relation_description }}</a>@endif
          @if($relation->relation_type=="hasOne")
            @if($relationTable == 'pages' && $dsItem->{'pages_id'})
            <a href="{{ route('update/page', $dsItem->{'pages_id'}) }}" target="_blank" class="btn btn-xs"><i class="fa fa-external-link"></i> Editar {{ $relation->relation_description }}</a>
            @endif
          @endif
				@endforeach
				<a href="{{ route('update/ds', array($datasource->id, $dsItem->id)) }}@if($parameters['pds'])?pds={{$parameters['pds']}}&item={{$parameters['item']}} @endif" class="btn btn-xs btn-default">
          @if(array_key_exists($datasource->table.'.update', $_groupPermissions)) 
            @lang('button.edit') 
          @else 
            @lang('button.view') 
          @endif
        </a>
        @if(array_key_exists($datasource->table.'.delete', $_groupPermissions))
				<a class="btn btn-xs btn-danger" data-msg="Confirma eliminar o registo?" data-reply="" data-toggle="modal" data-descr="{{ $dsItem->id }}" data-url="{{ route('delete/ds', array($datasource->id, $dsItem->id)) }}" href="#modal-confirm">@lang('button.delete')</a>
        @endif
			</td>
		</tr>
		@endforeach

      </tbody>
      </table>
  	</section>


@section('subscripts')
	<script type="text/javascript">

    	var oTable = $('#main_table').dataTable();
    	oTable.fnSort( [[2,'desc'] ] );

    </script>
@stop