@extends('cms::layouts/default')

{{-- Page title --}}
@section('title')
Adicionar Grupo ::
@parent
@stop

{{-- Page content --}}
@section('content')

	<div class="row">
      <div class="col-lg-12">
          <ul class="breadcrumb pull-left">
              <li><a href="{{ route('cms') }}"><i class="icon-home"></i> Home</a></li>
              <li><a href="{{ route('groups') }}">Grupos</a></li>
              <li><span class="active">Adicionar</span></li>
          </ul>
          <a href="{{ route('groups') }}" class="btn btn-small btn-info pull-right"><i class="icon-circle-arrow-left icon-white"></i> Voltar</a>
      </div>
  </div>

  <hr class="top-line" />

  <div class="row">
	  <div class="col-lg-12">
		<section class="panel">
			<header class="panel-heading tab-bg-dark-navy-blue ">
				<ul class="nav nav-tabs">
					<li class="active"><a href="#tab-general" data-toggle="tab">Geral</a></li>
					{{--<li><a href="#tab-permissions" data-toggle="tab">Permissões</a></li>--}}
				</ul>
			</header>
			<div class="panel-body">

				<form class="form-horizontal tasi-form" method="post" action="" autocomplete="off">
				<input type="hidden" name="_token" value="{{ csrf_token() }}" />

				<div class="tab-content">
					<header class="panel-heading form-group">Novo Grupo</header>
					<div id="tab-general" class="tab-pane active">

						<div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                          	<label for="name" class="col-lg-2 control-label">Nome</label>
                          	<div class="col-lg-6">
                              	<input type="text" class="form-control" name="name" id="name" value="{{ Input::old('name') }}" />
                             	{{ $errors->first('name', '<p class="help-block">:message</p>') }}
                          	</div>
                      	</div>
						<div class="form-group"></div>

					</div>
					{{--<div id="tab-permissions" class="tab-pane">--}}

						{{--@foreach ($permissions as $area => $permissions)--}}
							{{--<header class="panel-heading form-group">{{ $area }}</header>--}}

							{{--@foreach ($permissions as $permission)--}}
							{{--<div class="control-group form-group">--}}
								{{--<label class="col-lg-2 control-label">{{ $permission['label'] }}</label>--}}

								{{--<div class="col-lg-10">--}}

									{{--<span class="radio-inline radio">--}}
										{{--<label for="{{ $permission['permission'] }}_allow" onclick="">--}}
											{{--<input type="radio" value="1" id="{{ $permission['permission'] }}_allow" name="permissions[{{ $permission['permission'] }}]"{{ (array_get($selectedPermissions, $permission['permission']) === 1 ? ' checked="checked"' : '') }}>--}}
											{{--Permitir--}}
										{{--</label>--}}
									{{--</span>--}}

									{{--<span class="radio-inline radio">--}}
										{{--<label for="{{ $permission['permission'] }}_deny" onclick="">--}}
											{{--<input type="radio" value="0" id="{{ $permission['permission'] }}_deny" name="permissions[{{ $permission['permission'] }}]"{{ ( ! array_get($selectedPermissions, $permission['permission']) ? ' checked="checked"' : '') }}>--}}
											{{--Negar--}}
										{{--</span>--}}
									{{--</span>--}}

								{{--</div>--}}

							{{--</div>--}}
							{{--@endforeach--}}

						{{--</fieldset>--}}
						{{--@endforeach--}}

						{{--<div class="form-group"></div>--}}
					{{--</div>--}}
				</div>

				<div class="form-group">
					<div class="col-lg-12 text-right">
						<button class="btn btn-danger" type="submit">Adicionar</button>
						<a class="btn btn-default" href="{{ route('groups') }}">Cancelar</a>
					</div>
				</div>
	
				</form>
			</div>
		</section>
	  <div>
	</div>

@stop

		

