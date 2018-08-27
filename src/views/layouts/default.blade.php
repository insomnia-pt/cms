<!DOCTYPE html>
<html lang="pt">
	<head>
		<meta charset="utf-8" />
		<title>
			@section('title')
			{{ $settings->title }}
			@show
		</title>
		<meta name="keywords" content="" />
		<meta name="author" content="Miguel Pereira [insomnia.pt]" />
		<meta name="description" content="" />
		<meta name="viewport" content="width=device-width, initial-scale=1">

	    <link href="{{ Helpers::asset(Config::get('cms::config.assets_path').'/assets/css/bootstrap.min.css') }}" rel="stylesheet">
	    <link href="{{ Helpers::asset(Config::get('cms::config.assets_path').'/assets/css/animate.css') }}" rel="stylesheet" />
	    <link href="{{ Helpers::asset(Config::get('cms::config.assets_path').'/assets/plugins/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet" />
	    <link href="{{ Helpers::asset(Config::get('cms::config.assets_path').'/assets/css/multi-select.css') }}" rel="stylesheet" />
	    <link href="{{ Helpers::asset(Config::get('cms::config.assets_path').'/assets/css/style.css') }}" rel="stylesheet">
	    <link href="{{ Helpers::asset(Config::get('cms::config.assets_path').'/assets/css/style-responsive.css') }}" rel="stylesheet" />
		<link rel="shortcut icon" href="{{ Helpers::asset(Config::get('cms::config.assets_path').'/assets/img/favicon.png') }}">

	    <!--[if lt IE 9]>
	      <script src="{{ Helpers::asset(Config::get('cms::config.assets_path').'/assets/js/html5shiv.js') }}"></script>
	      <script src="{{ Helpers::asset(Config::get('cms::config.assets_path').'/assets/js/respond.min.js') }}"></script>
	    <![endif]-->

		@section('styles')
		@show
	</head>

	<body>
		<section id="container" class="">
		    <header class="header white-bg">
		        <a href="/" class="logo">{{ $settings->title }} <span>{{ $settings->subtitle }}</span></a>
		        <div class="top-nav ">
		            <ul class="nav pull-right top-menu">
		                <li class="dropdown">
		                    <a data-toggle="dropdown" class="dropdown-toggle" href="#">
		                	    <img alt="" src="{{ $CMS_USER->thumbnail(29,29) }}">
		                        <span class="username">{{ $CMS_USER->fullName() }}</span>
		                        <b class="caret"></b>
		                    </a>
		                    <ul class="dropdown-menu extended logout text-center">
		                        <div class="log-arrow-up"></div>
		                        <li class="text-center"><a href="{{ route('users/edit', $CMS_USER->id ) }}"><i class=" fa fa-user"></i>Editar Perfil</a></li>
		                        <li><a href="{{ route('logout') }}"><i class="fa fa-sign-out"></i> Terminar Sessão</a></li>
		                    </ul>
		                </li>    
		            </ul>    
		        </div>
		    </header>

		    <aside>
		       	<div id="sidebar"  class="nav-collapse ">
		    	   	<ul class="sidebar-menu">
						@include('cms::layouts/menu')
			        </ul>
					@if(@Config::get('app.version'))<div class="appversion">{{ Config::get('app.version') }}</div>@endif
				</div>
		    </aside>

		    <section id="main-content">
		        <section class="wrapper">

		            @yield('content')

		        </section>
		    </section>
		</section>

		<div class="modal fade" id="modal-confirm" tabindex="-1" role="dialog" aria-hidden="true">
			<div class="modal-dialog">
		    	<div class="modal-content">
		        	<div class="modal-header">
		            	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		              	<h4 class="modal-title">Confirmação</h4>
		          	</div>
		          	<div class="modal-body">
		          		<p class="muted" id="modal-descr"></p>
		          		<strong id="modal-msg"></strong>
		          	</div>
		          	<div class="modal-footer">
		        		<button data-dismiss="modal" class="btn btn-default" type="button">Cancelar</button>
		              	<a id="modal-bt-confirm" href="" class="btn btn-danger"> Confirmar</a>
		          	</div>
		      	</div>
		  	</div>
		</div>

		<script type="text/javascript">

			var $cms_url = "{{ Config::get('app.url') }}/{{ Config::get('cms::config.uri') }}";
			var $cms_ckeditor_toolbar = @if(CMS_Helper::checkPermission('component.ckeditor.adv'))"Full"@else"Mini"@endif;

		</script>
		<script src="{{ Helpers::asset(Config::get('cms::config.assets_path').'/assets/js/jquery.js') }}"></script>
	    <script src="{{ Helpers::asset(Config::get('cms::config.assets_path').'/assets/js/bootstrap.min.js') }}"></script>
	    <script src="{{ Helpers::asset(Config::get('cms::config.assets_path').'/assets/js/jquery.scrollTo.min.js') }}"></script>
	    <script src="{{ Helpers::asset(Config::get('cms::config.assets_path').'/assets/js/jquery.nicescroll.js') }}" type="text/javascript"></script>
	    <script src="{{ Helpers::asset(Config::get('cms::config.assets_path').'/assets/plugins/noty/jquery.noty.packaged.min.js') }}" type="text/javascript"></script>
	    <script src="{{ Helpers::asset(Config::get('cms::config.assets_path').'/assets/js/jquery.multi-select.js') }}" type="text/javascript"></script>
	    <script src="{{ Helpers::asset(Config::get('cms::config.assets_path').'/assets/js/jquery.quicksearch.js') }}" type="text/javascript"></script>
	    <script src="{{ Helpers::asset(Config::get('cms::config.assets_path').'/assets/js/js.cookie.js') }}" type="text/javascript"></script>
        <script src="{{ Helpers::asset(Config::get('cms::config.assets_path').'/assets/js/ga.js') }}" type="text/javascript" ></script>

	    <script src="{{ Helpers::asset(Config::get('cms::config.assets_path').'/assets/js/common-scripts.js') }}"></script>

		@section('scripts')
		@show

		<script type="text/javascript" charset="utf-8">

			@include('cms::notifications')

			$('.mightOverflow').bind('mouseenter', function(){
				var $this = $(this);

				if(this.offsetWidth < this.scrollWidth && !$this.attr('title')){
					$this.attr('title', $this.text());
				}
			});

			$('#modal-confirm').on('show.bs.modal', function(e) {
				$(e.currentTarget).find('#modal-bt-confirm').attr("href", $(e.relatedTarget).data('url'));
				$(e.currentTarget).find('#modal-descr').html("# "+$(e.relatedTarget).data('descr'));
				$(e.currentTarget).find('#modal-msg').html($(e.relatedTarget).data('msg'));
			});


			if($('.date').length) $('.date').datepicker({
				viewMode: "months", 
				minViewMode: "days",
				maxViewMode: "days"
			});

			if($('.component-tags').length){
				$(".component-tags").tagsInput({
					interactive:true,
					onChange:function(){ maxTags('#'+$(this).attr('id'), $(this).data('limit')); },
					onAddTag:function(){ maxTags('#'+$(this).attr('id'), $(this).data('limit')); },
					onRemoveTag:function(){ maxTags('#'+$(this).attr('id'), $(this).data('limit')); }
				});
			}

			if($('.document').length){

				$(".document").tagsInput({
					interactive:false,
					onChange:function(){ maxTags('#'+$(this).attr('id'), $(this).data('limit')); },
					onAddTag:function(){ maxTags('#'+$(this).attr('id'), $(this).data('limit')); },
					onRemoveTag:function(){ maxTags('#'+$(this).attr('id'), $(this).data('limit')); }
				});

				$(".document .tagsinput-add").each(function(){
					var tagsinputElementSel = $(this).closest('.document').prev();
					var multiple = tagsinputElementSel.data('limit')>1?true:false;

					$(this).popupWindow({
						windowURL:'{{ URL::to("cms/elfinder/select?mode=selectDocuments") }}&multiple='+multiple+'&elementId='+tagsinputElementSel.attr('id'),
						windowName:'Gestão de Ficheiros',
						height:490,
						width:950,
						centerScreen:1
					});
				});
			}

			if($('.image').length){

				$(".image").tagsInput({
					interactive:false,
					onChange:function(){ addImagePreview('#'+$(this).attr('id')); maxTags('#'+$(this).attr('id'), $(this).data('limit')); },
					onAddTag:function(){ maxTags('#'+$(this).attr('id'), $(this).data('limit')); },
					onRemoveTag:function(){ addImagePreview('#'+$(this).attr('id')); maxTags('#'+$(this).attr('id'), $(this).data('limit')); }
				});

				$(".image .tagsinput-add").each(function(){
					var tagsinputElementSel = $(this).closest('.image').prev();
					var multiple = tagsinputElementSel.data('limit')>1?true:false;

					$(this).popupWindow({
						windowURL:'{{ URL::to("cms/elfinder/select?mode=selectImages") }}&multiple='+multiple+'&elementId='+tagsinputElementSel.attr('id'),
						windowName:'Gestão de Ficheiros',
						height:490,
						width:980,
						centerScreen:1
					});
				});
			}

			function addImagePreview(element){
				$(element+'_tagsinput .tag').each(function(){
				    $(this).addClass('popovers');
				    $(this).attr('data-html', 'true');
				    $(this).attr('data-trigger', 'hover');
				    $(this).attr('data-placement', 'top');
				    $(this).attr('data-content', '<img width="150" src="{{ URL::to(Config::get('cms::config.elfinder_dir')) }}/'+$(this).text().trim()+'" alt="" />');
				    $(this).attr('data-original-title', 'Preview');
				});
				$('.popover').remove();
				$('.popovers').popover();
			}

			function addDocument(fileUrl,filePath,fileName,element){
		    	if (!$(element).tagExist(filePath)){
		    		$(element).addTag(filePath);
		    	}
		    }

		    function addImage(fileUrl,filePath,fileName,element){
		    	if (!$(element).tagExist(filePath)){
		    		$(element).addTag(filePath);
		    		addImagePreview(element);
		    	}
		    }

		 	function maxTags(element, limit){
		    	tagcount = $(element+'_tagsinput span.tag').length;
			 	if(tagcount>=limit){ $(element+"_tagsinput .tagsinput-add").hide(); $(element+'_addTag').hide(); }
			 	else { $(element+"_tagsinput .tagsinput-add").show();  $(element+'_addTag').show();}
			}

		</script>
	</body>
</html>
