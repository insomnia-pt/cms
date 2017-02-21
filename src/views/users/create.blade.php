@extends('cms::layouts/default')

{{-- Page title --}}
@section('title')
Adicionar Utilizador ::
@parent
@stop

{{-- Page content --}}
@section('content')

	<div class="row">
      <div class="col-lg-12">
          <ul class="breadcrumb pull-left">
              <li><a href="{{ route('cms') }}"><i class="icon-home"></i> Home</a></li>
              <li><a href="{{ route('users') }}">Utilizadores</a></li>
              <li><span class="active">Adicionar</span></li>
          </ul>

          <a href="{{ route('users') }}" class="btn btn-small btn-default pull-right"><i class="icon-circle-arrow-left icon-white"></i> Voltar</a>
      </div>
  </div>

<hr class="top-line" />

  <div class="row">
	  <div class="col-lg-12">
		<section class="panel">
			<header class="panel-heading tab-bg-dark-navy-blue ">
				<ul class="nav nav-tabs">
					<li class="active"><a href="#tab-general" data-toggle="tab">Geral</a></li>
          <li><a href="#tab-photo" data-toggle="tab">Foto</a></li>
				</ul>
			</header>
			<div class="panel-body">

				<form class="form-horizontal tasi-form" method="post" action="" enctype="multipart/form-data" autocomplete="off">
				<input type="hidden" name="_token" value="{{ csrf_token() }}" />

				<div class="tab-content">
					<header class="panel-heading form-group">Novo Utilizador</header>
					<div id="tab-general" class="tab-pane active">

            <div class="form-group {{ $errors->has('first_name') ? 'has-error' : '' }}">
                <label for="first_name" class="col-lg-2 control-label">Nome</label>
                <div class="col-lg-10">
                    <input type="text" class="form-control" name="first_name" id="first_name" value="{{ Input::old('first_name') }}" />
                  {{ $errors->first('first_name', '<p class="help-block">:message</p>') }}
                </div>
            </div>

            <div class="form-group {{ $errors->has('last_name') ? 'has-error' : '' }}">
                <label for="last_name" class="col-lg-2 control-label">Apelido</label>
                <div class="col-lg-10">
                    <input type="text" class="form-control" name="last_name" id="last_name" value="{{ Input::old('last_name') }}" />
                  {{ $errors->first('last_name', '<p class="help-block">:message</p>') }}
                </div>
            </div>

						<div class="form-group {{ $errors->has('username') ? 'has-error' : '' }}">
              	<label for="username" class="col-lg-2 control-label">Username</label>
              	<div class="col-lg-3">
                  	<input type="text" class="form-control" name="username" id="username" value="{{ Input::old('username') }}" />
                 	{{ $errors->first('username', '<p class="help-block">:message</p>') }}
              	</div>
          	</div>

          	<div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
              	<label for="email" class="col-lg-2 control-label">Email</label>
              	<div class="col-lg-8">
                  	<input type="email" class="form-control" name="email" id="email" value="{{ Input::old('email') }}" />
                 	{{ $errors->first('email', '<p class="help-block">:message</p>') }}
              	</div>
          	</div>

          	<div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">
              	<label for="password" class="col-lg-2 control-label">Senha</label>
              	<div class="col-lg-5">
                  	<input type="password" class="form-control" name="password" id="password" />
                 	{{ $errors->first('password', '<p class="help-block">:message</p>') }}
              	</div>
          	</div>

          	<div class="form-group {{ $errors->has('password_confirm') ? 'has-error' : '' }}">
              	<label for="password_confirm" class="col-lg-2 control-label">Confirm. Senha</label>
              	<div class="col-lg-5">
                  	<input type="password" class="form-control" name="password_confirm" id="password_confirm" />
                 	{{ $errors->first('password_confirm', '<p class="help-block">:message</p>') }}
              	</div>
          	</div>

            @if(Sentry::getUser()->hasAccess('users.group'))
  						<div class="form-group {{ $errors->has('groups') ? 'has-error' : '' }}">
  							<label class="col-lg-2 control-label" for="groups">Grupo</label>
  							<div class="col-lg-4">
  								<select class="form-control" name="groups[]" id="groups">
  									@foreach ($groups as $group)
                      @if(Session::get('settings_super_user') && $group->id == 1)
                      @else
  									   <option value="{{ $group->id }}"{{ (in_array($group->id, $selectedGroups) ? ' selected="selected"' : '') }}>{{ $group->name }}</option>
                      @endif
  									@endforeach
  								</select>
  							</div>
  						</div>
            @endif

						<div class="form-group {{ $errors->has('activated') ? 'has-error' : '' }}">
							<label class="col-lg-2 control-label" for="activated">Activo</label>
							<div class="col-lg-2">
								<select class="form-control" name="activated" id="activated">
									<option value="1"{{ (Input::old('activated', 0) === 1 ? ' selected="selected"' : '') }}>Sim</option>
									<option value="0"{{ (Input::old('activated', 0) === 0 ? ' selected="selected"' : '') }}>Não</option>
								</select>
								{{ $errors->first('activated', '<p class="help-block">:message</p>') }}
							</div>
						</div>
						<div class="form-group"></div>

					</div>

          <div id="tab-photo" class="tab-pane">

            <div class="form-group {{ $errors->has('photo') ? 'has-error' : '' }}">
                <div class="col-lg-2">
                  <span class="task-thumb">
                    <img alt="" width="120" height="120" src="{{ Helpers::asset(Config::get('cms::config.assets_path').'/assets/img/default_avatar.jpg') }}">
                  </span>
                </div>
                <div class="col-lg-3">
                  <br /><br /><br />
                  <input type="file" id="photo" name="photo" />
                  {{ $errors->first('photo', '<p class="help-block">:message</p>') }}
                </div>
            </div>
            <div class="form-group"></div>

          </div>

				</div>

				<div class="form-group">
					<div class="col-lg-12 text-right">
						<button class="btn btn-danger" type="submit">Adicionar</button>
						<a class="btn btn-default" href="{{ route('users') }}">Cancelar</a>
					</div>
				</div>
	
				</form>
			</div>
		</section>
	  <div>
	</div>

@stop