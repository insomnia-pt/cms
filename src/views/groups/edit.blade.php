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
                    <li><a href="#tab-menus" data-toggle="tab">Menu</a></li>
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
                                if(\Sentry::getUser()->hasAccess(base64_decode($permission['permission'])) || \Sentry::getUser()->getGroups()[0]->id == 1) $hasAreaPermission = 1;
                            }
                            ?>
                            @if($hasAreaPermission)
                            <p style="height: 1px;"></p>
                            <div class="row">
                                <div class="col-md-2"><strong>{{ $area }}</strong></div>
                                @foreach ($permissions as $permission)
                                    @if(\Sentry::getUser()->hasAccess(base64_decode($permission['permission']))  || \Sentry::getUser()->getGroups()[0]->id == 1)
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
                                    if(\Sentry::getUser()->hasAccess($datasource->table.'.'.$permission)  || \Sentry::getUser()->getGroups()[0]->id == 1) $hasDatasourcePermission = 1;
                                }
                                ?>
                                @if($hasDatasourcePermission)
                                    <p style="height: 1px;"></p>
                                    <div class="row">
                                        <div class="col-md-2"><strong>{{ $datasource->name }}</strong></div>
                                        @foreach ($datasource->permissions() as $permission)
                                            @if(\Sentry::getUser()->hasAccess($datasource->table.'.'.$permission)  || \Sentry::getUser()->getGroups()[0]->id == 1)
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
                    <div id="tab-menus" class="tab-pane">

                        <div class="row">
                            <div class="col-lg-7">

                                <section>
                                    <header class="panel-heading"><strong>LISTA DE MENUS ACTIVOS</strong></header>
                                    <input type="hidden" name="menuconfig" id="menuconfig" />
                                    <div id="menulist" class="dd ">
                                        <ol class="list-group dd-list menulists">
                                            @foreach($menulist as $menuitem)
                                                <li class="dd-item" data-id="{{ $menuitem->id }}" data-name="{{ $menuitem->name }}" data-icon="{{ $menuitem->icon }}" data-url="{{ $menuitem->url }}" data-datasource_id="{{ $menuitem->datasource_id }}" data-system="{{ $menuitem->system }}">
                                                    <div class="dd-handle"></div>
                                                    <div class="dd-content">
                                                        @if(!$menuitem->system&&!$menuitem->datasource_id)<button class="menu-remove btn btn-xs btn-danger" type="button"><i class="fa fa-trash"></i></button>@endif
                                                        <button class="menu-edit btn btn-xs btn-info" type="button"><i class="fa fa-pencil"></i></button>
                                                        <span class="menu-label-text">{{ $menuitem->name?$menuitem->name:$menuitem->datasource->name }}</span>
                                                        <span class="menu-label-info"> {{ $menuitem->datasource_id?'<small class="text-muted"></small>':'' }}</span>
                                                        <input type="text" class="menu-input-text form-control" />
                                                    </div>
                                                    @if(count($menuitem->children))
                                                        <ol class="dd-list dd-collapsed">
                                                            @foreach($menuitem->children as $submenuitem)
                                                                <li class="dd-item" data-id="{{ $submenuitem->id }}" data-name="{{ $submenuitem->name }}" data-icon="{{ $submenuitem->icon }}" data-url="{{ $submenuitem->url }}" data-datasource_id="{{ $submenuitem->datasource_id }}" data-system="{{ $submenuitem->system }}">
                                                                    <div class="dd-handle"></div>
                                                                    <div class="dd-content">
                                                                        @if(!$submenuitem->system&&!$submenuitem->datasource_id)<button class="menu-remove btn btn-xs btn-danger" type="button"><i class="fa fa-trash"></i></button>@endif
                                                                        <button class="menu-edit btn btn-xs btn-info" type="button"><i class="fa fa-pencil"></i></button>
                                                                        <span class="menu-label-text">{{ $submenuitem->name?$submenuitem->name:$submenuitem->datasource->name }}</span>
                                                                        <span class="menu-label-info"> {{ $submenuitem->datasource_id?'<small class="text-muted"></small>':'' }}</span>
                                                                        <input type="text" class="menu-input-text form-control" />
                                                                    </div>
                                                                </li>
                                                            @endforeach
                                                        </ol>
                                                    @endif
                                                </li>
                                            @endforeach
                                        </ol>
                                    </div>

                                </section>
                            </div>
                            <div class="col-lg-5">
                                <section>
                                    <header class="panel-heading">
                                        OPÇÕES DE MENU
                                        <button class="btn btn-info btn-xs pull-right bt-add-group" type="button">Novo Agrupamento</button>
                                    </header>
                                    <input type="hidden" name="menuoutconfig" id="menuoutconfig" />
                                    <div id="menuoutlist" class="dd">
                                        @if(count($allmenuoutlist))
                                            <ol class="list-group dd-list menulists">
                                                @foreach($allmenuoutlist as $menuoutitem)
                                                    <li class="dd-item" data-id="{{ $menuoutitem->id }}" data-name="{{ $menuoutitem->name }}" data-icon="{{ $menuoutitem->icon }}" data-url="{{ $menuoutitem->url }}" data-datasource_id="{{ $menuoutitem->table?$menuoutitem->id:null }}" data-system="{{ $menuoutitem->system }}">
                                                        <div class="dd-handle"></div>
                                                        <div class="dd-content">
                                                            @if(!$menuoutitem->system&&!$menuoutitem->table)<button class="menu-remove btn btn-xs btn-danger" type="button"><i class="fa fa-trash"></i></button>@endif
                                                            <button class="menu-edit btn btn-xs btn-info" type="button"><i class="fa fa-pencil"></i></button>
                                                            <span class="menu-label-text">{{ $menuoutitem->name?$menuoutitem->name:$menuoutitem->datasource->name }}</span>
                                                            <span class="menu-label-info"> {{ $menuoutitem->datasource_id?'<small class="text-muted"></small>':'' }}</span>
                                                            <input type="text" class="menu-input-text form-control" />
                                                        </div>
                                                        @if(count($menuoutitem->children))
                                                            <ol class="dd-list dd-collapsed">
                                                                @foreach($menuoutitem->children as $submenuitem)
                                                                    <li class="dd-item" data-id="{{ $submenuitem->id }}" data-name="{{ $submenuitem->name }}" data-icon="{{ $submenuitem->icon }}" data-url="{{ $submenuitem->url }}" data-datasource_id="{{ $submenuitem->datasource_id }}" data-system="{{ $submenuitem->system }}">
                                                                        <div class="dd-handle"></div>
                                                                        <div class="dd-content">
                                                                            @if(!$submenuitem->system&&!$submenuitem->table)<button class="menu-remove btn btn-xs btn-danger" type="button"><i class="fa fa-trash"></i></button>@endif
                                                                            <button class="menu-edit btn btn-xs btn-info" type="button"><i class="fa fa-pencil"></i></button>
                                                                            <span class="menu-label-text">{{ $submenuitem->name?$submenuitem->name:$submenuitem->datasource->name }}</span>
                                                                            <span class="menu-label-info"> {{ $submenuitem->datasource_id?'<small class="text-muted"></small>':'' }}</span>
                                                                            <input type="text" class="menu-input-text form-control" />
                                                                        </div>
                                                                    </li>
                                                                @endforeach
                                                            </ol>
                                                        @endif
                                                    </li>
                                                @endforeach
                                            </ol>
                                        @else
                                            <div class="dd-empty"></div>
                                        @endif
                                    </div>
                                </section>
                            </div>

                        </div>


                        <div class="form-group"></div>
                        <div class="form-group"></div>

                    </div>
				</div>

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



@section('styles')
  <link href="{{ Helpers::asset(Config::get('cms::config.assets_path').'/assets/css/jquery.nestable.css') }}" rel="stylesheet">
@stop

@section('scripts')

  <script type="text/javascript" src="{{ Helpers::asset(Config::get('cms::config.assets_path').'/assets/js/jquery.nestable.js') }}"></script>

  <script type="text/javascript">

      $('#form-save').on('keyup keypress', function(e) {
          var keyCode = e.keyCode || e.which;
          if (keyCode === 13) {
              e.preventDefault();
              return false;
          }
      });

      $('.bt-add-group').click(function(){
          if(!$("#menuoutlist ol").length) { $("#menuoutlist div").remove('div.dd-empty'); $("#menuoutlist").append('<ol class="dd-list"></ol>'); }
          $("#menuoutlist ol").append('<li class="dd-item" data-name="Novo Grupo.." data-icon="" data-url="" data-datasource_id=""><div class="dd-handle"></div><div class="dd-content"><button class="menu-remove btn btn-xs btn-danger" type="button"><i class="fa fa-trash"></i></button><button class="menu-edit btn btn-xs btn-info" type="button"><i class="fa fa-pencil"></i></button><span class="menu-label-text">Novo Grupo..</span><span class="menu-label-info"></span><input type="text" class="menu-input-text form-control" /></div>');
          updateLists();
      });

      $('.menulists, #menuoutlist').on('click','.menu-edit .fa-pencil', function(){
          var element =$(this).closest('.dd-item');
          element.find('.menu-label-text').eq(0).toggle();
          element.find('.menu-label-info').eq(0).toggle();
          element.find('.menu-input-text').eq(0).val(element.find('.menu-label-text').eq(0).text()).toggle();
          element.find('.menu-edit i').eq(0).toggleClass('fa-check fa-pencil');
      });

      $('.menulists, #menuoutlist').on('click','.menu-edit .fa-check', function(){
          var element =$(this).closest('.dd-item');
          element.data('name', element.find('.menu-input-text').eq(0).val());
          element.find('.menu-label-info').eq(0).toggle();
          element.find('.menu-label-text').eq(0).text(element.find('.menu-input-text').eq(0).val()).toggle();
          element.find('.menu-input-text').eq(0).toggle();
          element.find('.menu-edit i').eq(0).toggleClass('fa-check fa-pencil');
          updateLists();
      });

      $('.menulists, #menuoutlist').on('click','.menu-remove', function(e){
          var element = $(this).closest('.dd-item');
          var isMenuOutList = element.closest('.dd').is('#menuoutlist');
          if(element.find('li').length){
              element.parent().append(element.find('ol').html());
          }
          element.remove();
          if(!$('#menuoutlist ol li').length && isMenuOutList) { $("#menuoutlist ol").remove('.dd-list'); $("#menuoutlist").append('<div class="dd-empty"></div>'); }
          updateLists();
      });

      $('#menulist').nestable({
          maxDepth: 2,
          callback: function(l,e){
              updateLists();
          }
      });

      $('#menuoutlist').nestable({
          maxDepth: 2,
          callback: function(l,e){
              updateLists();
          }
      });

      //init
      updateLists();

      function updateLists() {
          $("#menuconfig").val(JSON.stringify($('#menulist').nestable('serialize')));
          $("#menuoutconfig").val(JSON.stringify($('#menuoutlist').nestable('serialize')));
      }
  </script>
@stop