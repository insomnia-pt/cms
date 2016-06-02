@extends('ocms::layouts/default')

{{-- Page title --}}
@section('title')
Gestão de Utilizadores ::
@parent
@stop

{{-- Page content --}}
@section('content')
	
	<div class="row">
      <div class="col-lg-12">
          <ul class="breadcrumb pull-left">
              <li><a href="{{ URL::to('ocms') }}"><i class="icon-home"></i> Home</a></li>
              <li><span class="active">Utilizadores</span></li>
          </ul>
          <a href="{{ route('create/user') }}" class="btn btn-small btn-info pull-right"><i class="icon-plus-sign icon-white"></i> Adicionar</a>
      </div>
  </div>

  <hr class="top-line" />

	<div class="row">
      <div class="col-lg-12">
          <section class="panel panel-primary">
              <header class="panel-heading">
                  Lista de Utilizadores
                  <input class="form-control pull-right input-smmm" type="text" id="dataTable1filter" placeholder="Procurar.." style="width: 200px">
              </header>
              <table class="table table-striped border-top table-hover table-no-top-border" id="main_table">
              <thead>
              <tr>
                  <th>Username</th>
                  <th class="hidden-phone">Email</th>
                  <th class="hidden-phone">Criado em</th>
                  <th class="hidden-phone">Estado</th>
                  <th class="hidden-phone"></th>
              </tr>
              </thead>
              <tbody>

              	@foreach ($users as $user)
              		<tr class="odd gradeX">
	                  <td>{{ $user->username }}</td>
	                  <td class="hidden-phone"><a href="mailto:{{ $user->email }}">{{ $user->email }}</a></td>
	                  <td class="center hidden-phone">{{ $user->created_at }}</td>
	                  <td class="hidden-phone"><span class="label label-success">Activo</span></td>
	                  <td class="text-right">
        						<a href="{{ route('update/user', $user->id) }}" class="btn btn-xs btn-default">@lang('button.edit')</a>

        						@if ( ! is_null($user->deleted_at))
        						<a href="{{ route('restore/user', $user->id) }}" class="btn btn-xs btn-warning">@lang('button.restore')</a>
        						@else
        						@if (Sentry::getUser()->id !== $user->id)
        						<a href="{{ route('delete/user', $user->id) }}" class="btn btn-xs btn-danger">@lang('button.delete')</a>
        						@else
        						<span class="btn btn-xs btn-danger disabled">@lang('button.delete')</span>
        						@endif
        						@endif
        					   </td>
	                </tr>
				        @endforeach

              </tbody>
              </table>
          </section>
      </div>
  	</div>


<a class="btn btn-medium" href="{{ URL::to('ocms/users?withTrashed=true') }}">Incluir Eliminados</a>
<a class="btn btn-medium" href="{{ URL::to('ocms/users?onlyTrashed=true') }}">Ver Apenas Eliminados</a>

@stop

@section('scripts')
	<script type="text/javascript" src="{{ asset('ocms-res/assets/plugins/data-tables/jquery.dataTables.js') }}"></script>
    <script type="text/javascript" src="{{ asset('ocms-res/assets/plugins/data-tables/DT_bootstrap.js') }}"></script>
    <script type="text/javascript" src="{{ asset('ocms-res/assets/js/dynamic-table.js') }}"></script>
    <script type="text/javascript">

      var oTable = $('#main_table').dataTable();
      oTable.fnSort( [[3,'desc'] ] );

    </script>
@stop