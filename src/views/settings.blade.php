@extends('cms::layouts/default')
@section('title')
Definições ::
@parent
@stop

@section('content')

	<div class="row">
      <div class="col-lg-12">
          <ul class="breadcrumb pull-left">
              <li><a href="{{ route('cms') }}"><i class="icon-home"></i> Home</a></li>
              <li><span class="active">Definições</span></li>
          </ul>
      </div>

  </div>

  <hr class="top-line" />

  <div class="row">
	  <div class="col-lg-12">
		<section class="panel">
			<header class="panel-heading tab-bg-dark-navy-blue ">
				<ul class="nav nav-tabs">
					<li class="active"><a href="#tab-general" data-toggle="tab">Geral</a></li>
				</ul>
			</header>
			<div class="panel-body">

				<form class="form-horizontal tasi-form" method="post" enctype="multipart/form-data" action="" autocomplete="off">
				    <input type="hidden" name="_token" value="{{ csrf_token() }}" />

				    <div class="tab-content">
					    <header class="panel-heading form-group"></header>
					    <div id="tab-general" class="tab-pane active">
        
						    <div class="form-group {{ $errors->has('title') ? 'has-error' : '' }}">
                                <label for="title" class="col-lg-2 control-label">Título</label>
                                <div class="col-lg-7">
                                    <input type="text" class="form-control" name="title" id="title" value="{{ Input::old('title', json_decode($allSettings->get('general')->value)->title) }}" />
                                    {{ $errors->first('title', '<p class="help-block">:message</p>') }}
                                </div>
                            </div>

                            <div class="form-group {{ $errors->has('subtitle') ? 'has-error' : '' }}">
                                <label for="subtitle" class="col-lg-2 control-label">Sub-Título</label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control" name="subtitle" id="subtitle" value="{{ Input::old('subtitle', json_decode($allSettings->get('general')->value)->subtitle) }}" />
                                    {{ $errors->first('subtitle', '<p class="help-block">:message</p>') }}
                                </div>
                            </div>
                        

                   
                            <div class="form-group"></div>

                        </div>
					
		            </div>

                    <div class="form-group">
                        <div class="col-lg-12 text-right">
                            <button class="btn btn-danger" type="submit">Guardar Alterações</button>
                        </div>
                    </div>
	
				</form>
			</div>
		</section>
	  <div>
	</div>

@stop
