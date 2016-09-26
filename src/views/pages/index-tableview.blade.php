	@if(@!$datasource->options()->subitems && @$datasource->options()->group)
	<div class="panel">
	  	<div class="row panel-body">
	  		<div class="col-md-5">

	  			<?php


				if(!Input::get('group')) {  }

				$parentPageId = Input::get('group');

				$pages = $pages->filter(function($page) use($parentPageId) {
				    return $page->id_parent == $parentPageId;
				})->values();
				?>

	  			<label>Grupo de Páginas</label>&nbsp;&nbsp;
	  			<div class="btn-group">
	              <button class="btn btn-white" type="button">
	              	@foreach($parentPages as $parentPage)
						@if($parentPage->id==$parentPageId){{ $parentPage->title }}@endif
					@endforeach
	              </button>
	              <button data-toggle="dropdown" class="btn btn-white dropdown-toggle" type="button"><span class="caret"></span></button>
	              <ul role="menu" class="dropdown-menu">
	                @foreach($parentPages as $parentPage)
						@if($parentPage->id!=$parentPageId)<li><a href="{{ URL::to('cms/pages?group='.$parentPage->id) }}">{{ $parentPage->title }}</a></li>@endif
					@endforeach
	              </ul>
          		</div>

	  		</div>
	  	</div>
	 </div>
	 @endif

	<section class="panel panel-primary">
      <header class="panel-heading">
        Lista de Páginas
        <input class="form-control pull-right input-smmm" type="text" id="dataTable1filter" placeholder="Procurar.." style="width: 200px">
      </header>

      <table class="table table-striped border-top table-hover table-no-top-border" id="main_table">
      <thead>
          <tr>
        	@foreach ($datasource->config() as $config)
        		@if($config->show_in_table)<th>{{ $config->description }}</th>@endif
        	@endforeach
        		<th>Tipo</th>
        		<th class="nosort"></th>
          </tr>
      </thead>
      <tbody>
      	@foreach ($pages as $page)
		<tr class="odd gradeX">

			@foreach ($datasource->config() as $config)
        		@if($config->show_in_table)<td>{{ $page[$config->name] }}</td>@endif
        	@endforeach
        	<td>{{ $page->pagetype->name }}</td>
			<td class="text-right">
				<a href="{{ URL::to($page->slug) }}" target="_blank" class="btn btn-xs" title="Abrir Página"><i class="fa fa-external-link"></i> Abrir</a>|
				<a href="{{ route('pages/edit', $page->id) }}" class="btn btn-xs btn-default">
	          @if(array_key_exists($datasource->table.'.update', $_groupPermissions))
	            @lang('cms::button.edit')
	          @else
	            @lang('cms::button.view')
	          @endif
        	</a>
	        @if(array_key_exists($datasource->table.'.delete', $_groupPermissions))
					<a class="btn btn-xs btn-danger" data-msg="Confirma eliminar a página?" data-reply="" data-toggle="modal" data-descr="{{ $page->title }}" data-url="{{ route('pages/delete', $page->id) }}{{ Input::get('group')?'?group='.Input::get('group'):null }}" href="#modal-confirm">@lang('cms::button.delete')</a>
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
    	oTable.fnSort( [[0,'asc'] ] );

    </script>
@stop
