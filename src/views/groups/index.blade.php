@extends('cms::layouts/default')

{{-- Page title --}}
@section('title')
Gestão de Grupos ::
@parent
@stop

{{-- Page content --}}
@section('content')
	
	<div class="row">
      <div class="col-lg-12">
          <ul class="breadcrumb pull-left">
              <li><a href="{{ route('cms') }}"><i class="icon-home"></i> Home</a></li>
              <li><span class="active">Grupos</span></li>
          </ul>
          <a href="{{ route('groups/create') }}" class="btn btn-small btn-info pull-right"><i class="icon-plus-sign icon-white"></i> Adicionar</a>
      </div>
  </div>

  <hr class="top-line" />

	<div class="row">
      <div class="col-lg-12">
          <section class="panel panel-primary">
              <header class="panel-heading">
                  Lista de Grupos
                  <input class="form-control pull-right input-smmm" type="text" id="dataTable1filter" placeholder="Procurar.." style="width: 200px">
              </header>
              <table class="table table-striped border-top table-hover table-no-top-border" id="main_table">
              <thead>
              <tr>
                  <th>Nome</th>
                  <th class="hidden-phone"># utilizadores</th>
                  <th class="hidden-phone">Criado em</th>
                  <th></th>
              </tr>
              </thead>
              <tbody>

				@if ($groups->count() >= 1)
				@foreach ($groups as $group)
          @if(Session::get('settings_super_user') && $group->id == 1)

          @else 
            <tr class="odd gradeX">
              <td class="hidden-phone">{{ $group->name }}</td>
              <td class="hidden-phone">{{ $group->users()->count() }}</td>
              <td class="hidden-phone">{{ $group->created_at }}</td>
              <td class="text-right">
                <a href="{{ route('groups/edit', $group->id) }}" class="btn btn-xs btn-default">@lang('cms::button.edit')</a>
                <a class="btn btn-xs btn-danger" data-msg="Confirma eliminar o grupo?" data-reply="" data-toggle="modal" data-descr="{{ $group->name }}" data-url="{{ route('groups/delete', $group->id) }}" href="#modal-confirm">@lang('cms::button.delete')</a>
              </td>
            </tr>
          @endif
				@endforeach
				@else
				<tr>
					<td colspan="5">No results</td>
				</tr>
				@endif

              </tbody>
              </table>
          </section>
      </div>
  	</div>

@stop

@section('scripts')
	<script type="text/javascript" src="{{ Helpers::asset(Config::get('cms::config.assets_path').'/assets/plugins/data-tables/jquery.dataTables.js') }}"></script>
    <script type="text/javascript" src="{{ Helpers::asset(Config::get('cms::config.assets_path').'/assets/plugins/data-tables/DT_bootstrap.js') }}"></script>
    <script type="text/javascript" src="{{ Helpers::asset(Config::get('cms::config.assets_path').'/assets/js/dynamic-table.js') }}"></script>
    <script type="text/javascript">

      var oTable = $('#main_table').dataTable();
      oTable.fnSort( [[2,'desc'] ] );

    </script>
@stop