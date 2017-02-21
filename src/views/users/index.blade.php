@extends('cms::layouts/default')

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
              <li><a href="{{ route('cms') }}"><i class="icon-home"></i> Home</a></li>
              <li><span class="active">Utilizadores</span></li>
          </ul>
          <a href="{{ route('users/create') }}" class="btn btn-small btn-info pull-right"><i class="icon-plus-sign icon-white"></i> Adicionar</a>
          <a class="btn btn-default pull-right" href="{{ @Input::get('onlyTrashed')?URL::to('cms/users'):URL::to('cms/users?onlyTrashed=true') }}" style="margin-right: 5px">
              Mostrar {{ @Input::get('onlyTrashed')?'Activos / Inactivos':'Eliminados' }}
          </a>
      </div>
  </div>

  <hr class="top-line" />

	<div class="row">
      <div class="col-lg-12">
          <section class="panel panel-primary">
              <header class="panel-heading">
                  Lista de Utilizadores @if(@Input::get('onlyTrashed'))Eliminados @endif
                  <input class="form-control pull-right input-smmm" type="text" id="dataTable1filter" placeholder="Procurar.." style="width: 200px">
              </header>
              <table class="table table-striped border-top table-hover table-no-top-border" id="main_table">
              <thead>
              <tr>
                  <th>Username</th>
                  <th class="hidden-phone">Email</th>
                  <th class="hidden-phone">Grupo</th>
                  <th class="hidden-phone text-center">Estado</th>
                  <th class="hidden-phone nosort"></th>
              </tr>
              </thead>
              <tbody>

              	@foreach ($users as $user)
              		<tr class="odd gradeX">
	                  <td>{{ $user->username }}</td>
	                  <td class="hidden-phone"><a href="mailto:{{ $user->email }}">{{ $user->email }}</a></td>
	                  <td class="center hidden-phone">{{ @$user->getGroups()[0]?$user->getGroups()[0]->name:'(não definido)' }}</td>
	                  <td class="hidden-phone text-center">
                        @if($user->activated==1)<span class="label label-inverse">Activo</span>
                        @else <span class="label label-default">Inactivo</span> @endif
                      </td>
	                  <td class="text-right">
                        @if(!@Input::get('onlyTrashed'))
                        <a href="{{ route('users/edit', $user->id) }}" class="btn btn-xs btn-default">@lang('cms::button.edit')</a>
                        @endif

                        @if ( ! is_null($user->deleted_at))
                        <a href="{{ route('users/restore', $user->id) }}" class="btn btn-xs btn-danger">@lang('cms::button.restore')</a>
                        @else
                        @if (Sentry::getUser()->id !== $user->id)
                        <a href="{{ route('users/delete', $user->id) }}" class="btn btn-xs btn-danger">@lang('cms::button.delete')</a>
                        @else
                        <span class="btn btn-xs btn-danger disabled">@lang('cms::button.delete')</span>
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




@stop

@section('styles')
	<link href="{{ Helpers::asset(Config::get('cms::config.assets_path').'/assets/plugins/data-tables/jquery.dataTables.css') }}" rel="stylesheet">
@stop

@section('scripts')
	<script type="text/javascript" src="{{ Helpers::asset(Config::get('cms::config.assets_path').'/assets/plugins/data-tables/jquery.dataTables.js') }}"></script>
	<script type="text/javascript" src="{{ Helpers::asset(Config::get('cms::config.assets_path').'/assets/plugins/data-tables/DT_bootstrap.js') }}"></script>
	<script type="text/javascript" src="{{ Helpers::asset(Config::get('cms::config.assets_path').'/assets/js/dynamic-table.js') }}"></script>
@stop
