@extends('ocms::layouts/default')

{{-- Page title --}}
@section('title')
Gestão de Páginas ::
@parent
@stop

{{-- Page content --}}
@section('content')

<form id="form-saveMenu" action="" method="post">
	<div class="row">
      	<div class="col-lg-12">
			<ul class="breadcrumb pull-left">
          		<li><a href="{{ URL::to('ocms') }}"><i class="icon-home"></i> Home</a></li>
          		<li><span class="active">Menu</span></li>
      		</ul>
  			<button type="submit" class="btn btn-small btn-danger pull-right"><i class="icon-plus-sign icon-white"></i> Guardar Alterações</button>
	    </div>
  	</div>

  	<hr class="top-line" />
  	
  	<div class="panel">

	  	<div class="row panel-body">
	  		<div class="col-md-3">
	  			
	  			<label>Menu Grupo</label>&nbsp;&nbsp;
	  			<div class="btn-group">
	              <button class="btn btn-white" type="button">
	              	@foreach($groups as $group)
						@if($group->id==$groupId){{ $group->name }}@endif
					@endforeach
	              </button>
	              <button data-toggle="dropdown" class="btn btn-white dropdown-toggle" type="button"><span class="caret"></span></button>
	              <ul role="menu" class="dropdown-menu">
	                @foreach($groups as $group)
						@if($group->id!=$groupId)<li><a href="{{ URL::to('ocms/menu/'.$group->id) }}">{{ $group->name }}</a></li>@endif
					@endforeach
	              </ul>
          		</div>
	          	
	  		</div>
	  	</div>
	 </div>
  

  	<div class="row">
	    <div class="col-lg-7">

	    	

	    	<section class="panel panel-primary">
        		<header class="panel-heading">
            		MENU

         		</header>
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
	         					<span class="menu-label-info"> {{ $menuitem->datasource_id?'<small class="text-muted">(DATASOURCE)</small>':'' }}</span>
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
			         					<span class="menu-label-info"> {{ $submenuitem->datasource_id?'<small class="text-muted">(DATASOURCE)</small>':'' }}</span>
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
		    <section class="panel ">
		    	<header class="panel-heading">
            		MENUS DISPONÍVEIS
            		<button class="btn btn-info btn-xs pull-right bt-add-group" type="button">Novo Grupo</button>
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
	         					<span class="menu-label-info"> {{ $menuoutitem->datasource_id?'<small class="text-muted">(DATASOURCE)</small>':'' }}</span>
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
			         					<span class="menu-label-info"> {{ $submenuitem->datasource_id?'<small class="text-muted">(DATASOURCE)</small>':'' }}</span>
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
</form>

@stop

@section('styles')
	<link href="{{ asset('ocms-res/assets/css/jquery.nestable.css') }}" rel="stylesheet">

@stop

@section('scripts')
	<script type="text/javascript" src="{{ asset('ocms-res/assets/js/jquery.nestable.js') }}"></script>

    <script type="text/javascript">

    	$('#form-saveMenu').on('keyup keypress', function(e) {
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