@extends('cms::layouts/default')

{{-- Page title --}}
@section('title')
Editar Grupo ::
@parent
@stop

{{-- Page content --}}
@section('content')

	<div class="row">
      <div class="col-lg-12">
          <ul class="breadcrumb pull-left">
              <li><a href="{{ route('cms') }}"><i class="icon-home"></i> Home</a></li>
              <li><a href="{{ route('groups') }}">Grupos</a></li>
              <li><span class="active">Editar</span></li>
          </ul>
          <a href="{{ route('groups') }}" class="btn btn-small btn-default pull-right"><i class="icon-circle-arrow-left icon-white"></i> Voltar</a>
      </div>
  </div>

  <hr class="top-line" />

  <div class="row">
	  <div class="col-lg-12">
		<section class="panel">
			<header class="panel-heading tab-bg-dark-navy-blue ">
				<ul class="nav nav-tabs">
					<li class="active"><a href="#tab-general" data-toggle="tab">Geral</a></li>
                    <li><a href="#tab-permissions" data-toggle="tab">Permissões</a></li>
				</ul>
			</header>
			<div class="panel-body">

				<form class="form-horizontal tasi-form" id="form-save" method="post" action="" autocomplete="off">
				<input type="hidden" name="_token" value="{{ csrf_token() }}" />

				<div class="tab-content">
					<header class="panel-heading form-group">Detalhes do Grupo</header>
					<div id="tab-general" class="tab-pane active">

						<div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                          	<label for="name" class="col-lg-2 control-label">Nome</label>
                          	<div class="col-lg-6">
                              	<input type="text" class="form-control" name="name" id="name" value="{{ Input::old('name', $group->name) }}" />
                             	{{ $errors->first('name', '<p class="help-block">:message</p>') }}
                          	</div>
                      	</div>
						<div class="form-group"></div>

					</div>

					<div id="tab-permissions" class="tab-pane">
                        <br />

						@foreach ($permissions as $area => $permissions)
                            <?php $hasAreaPermission=0; foreach ($permissions as $permission) {
                                if(CMS_Helper::checkPermission(base64_decode($permission['permission'])) || $CMS_USER->getGroups()[0]->id == 1) $hasAreaPermission = 1;
                            }
                            ?>
                            @if($hasAreaPermission)
                            <p style="height: 1px;"></p>
                            <div class="row">
                                <div class="col-md-2"><strong>{{ $area }}</strong></div>
                                @foreach ($permissions as $permission)
                                    @if(CMS_Helper::checkPermission(base64_decode($permission['permission']))  || $CMS_USER->getGroups()[0]->id == 1)
                                    <div class="text-left text-muted col-md-2">
                                        <input type="hidden" name="permissions[{{ $permission['permission'] }}]" value="0" />
                                        <label class="label_check" for="{{ $permission['permission'] }}" style="font-weight: normal">
                                            <input type="checkbox" id="{{ $permission['permission'] }}" name="permissions[{{ $permission['permission'] }}]" {{ (array_get($groupPermissions, $permission['permission']) === 1 ? ' checked="checked"' : '') }} value="1" /> {{ $permission['label'] }}
                                        </label>
                                    </div>
                                    @endif
                                @endforeach
                            </div>
                            <hr />
                            @endif
						@endforeach

                        @foreach ($datasources as $datasource)
                            @if($datasource->permissions())
                                
                                <?php $hasDatasourcePermission=0; foreach ($datasource->permissions() as $permission) {
                                    if(CMS_Helper::checkPermission($datasource->table.'.'.$permission)  || $CMS_USER->getGroups()[0]->id == 1) $hasDatasourcePermission = 1;
                                }
                                ?>
                                @if($hasDatasourcePermission)
                                    <p style="height: 1px;"></p>
                                    <div class="row">
                                        <div class="col-md-2"><strong>{{ $datasource->name }}</strong></div>
                                        @foreach ($datasource->permissions() as $permission)
                                            @if(CMS_Helper::checkPermission($datasource->table.'.'.$permission)  || $CMS_USER->getGroups()[0]->id == 1)
                                            <div class="text-left text-muted col-md-2">
                                                <input type="hidden" name="permissions[{{ base64_encode($datasource->table.'.'.$permission) }}]" value="0" />
                                                <label class="label_check" for="{{ base64_encode($datasource->table.'.'.$permission) }}" style="font-weight: normal">
                                                    <input type="checkbox" id="{{ base64_encode($datasource->table.'.'.$permission) }}" name="permissions[{{ base64_encode($datasource->table.'.'.$permission) }}]" {{ (array_get($groupPermissions, base64_encode($datasource->table.'.'.$permission)) === 1 ? ' checked="checked"' : '') }} value="1" /> @lang('cms::permissions.'.$permission)
                                                </label>
                                            </div>
                                            @endif
                                        @endforeach
                                    </div>
                                    <hr />
                                @endif
                            @endif
                        @endforeach

						<div class="form-group"></div>
					</div>
                    <!--  -->

				<div class="form-group">
                    <div class="col-lg-12 text-right">
						<button class="btn btn-danger" type="submit">Guardar</button>
						<a class="btn btn-default" href="{{ route('groups') }}">Cancelar</a>
					</div>
				</div>
	
				</form>
			</div>
		</section>
	  <div>
	</div>

@stop