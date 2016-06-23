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

		<!-- Mobile Specific Metas -->
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<!-- CSS -->
	    <link href="{{ Helpers::asset(Config::get('cms::config.assets_path').'/assets/css/bootstrap.min.css') }}" rel="stylesheet">
	    <link href="{{ Helpers::asset(Config::get('cms::config.assets_path').'/assets/css/animate.css') }}" rel="stylesheet" />
	    <link href="{{ Helpers::asset(Config::get('cms::config.assets_path').'/assets/plugins/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet" />
	    <link href="{{ Helpers::asset(Config::get('cms::config.assets_path').'/assets/css/multi-select.css') }}" rel="stylesheet" />

	    <link href="{{ Helpers::asset(Config::get('cms::config.assets_path').'/assets/css/style.css') }}" rel="stylesheet">
	    <link href="{{ Helpers::asset(Config::get('cms::config.assets_path').'/assets/css/style-responsive.css') }}" rel="stylesheet" />


	    <!-- HTML5 shim and Respond.js IE8 support of HTML5 tooltipss and media queries -->
	    <!--[if lt IE 9]>
	      <script src="{{ Helpers::asset(Config::get('cms::config.assets_path').'/assets/js/html5shiv.js') }}"></script>
	      <script src="{{ Helpers::asset(Config::get('cms::config.assets_path').'/assets/js/respond.min.js') }}"></script>
	    <![endif]-->


		@section('styles')
		@show

		<!-- start: Favicon -->
		<link rel="shortcut icon" href="{{ Helpers::asset(Config::get('cms::config.assets_path').'/assets/img/favicon.png') }}">
		<!-- end: Favicon -->
	</head>

	<body>

		<section id="container" class="">
		      <!--header start-->
		      <header class="header white-bg">
		            <!-- <div class="sidebar-toggle-box">
		                <div data-original-title="Toggle Navigation" data-placement="right" class="fa fa-reorder tooltips"></div>
		            </div> -->
		            <!--logo start-->
		            <a href="/" class="logo">{{ $settings->title }} <span>{{ $settings->subtitle }}</span></a>
		            <!--logo end-->
		            <div class="top-nav ">
		                <!--user info start-->
		                <ul class="nav pull-right top-menu">
		                	@if(count(Config::get('app.languages'))>1)
		                	<li class="dropdown">
		                        <a data-toggle="dropdown" class="dropdown-toggle" href="#">
		                            <span class="username">Edição em <b style="text-transform:uppercase">{{ Config::get('app.languages.'.Session::get('language')) }}</b></span>
		                            <b class="caret"></b>
		                        </a>
		                        <ul class="dropdown-menu">
		                            <div class="log-arrow-up"></div>
		                            @foreach (Config::get('app.languages') as $key => $language)
		                            	<li><a href="{{ route('admin/setlang', $key) }}">{{ $language }}</a></li>
		                            @endforeach
		                        </ul>
		                    </li>
		                    @endif

		                    <!-- user login dropdown start-->
		                    <li class="dropdown">
		                        <a data-toggle="dropdown" class="dropdown-toggle" href="#">
		                            <img alt="" src="{{ Sentry::getUser()->thumbnail(29,29) }}">
		                            <span class="username">{{ Sentry::getUser()->fullName() }}</span>
		                            <b class="caret"></b>
		                        </a>
		                        <ul class="dropdown-menu extended logout">
		                            <div class="log-arrow-up"></div>
		                            <li><a href="{{ route('users/edit', Sentry::getUser()->id ) }}"><i class=" fa fa-male"></i>Perfil</a></li>
		                            <li><a href="{{ route('admin/programador') }}"><i class="fa fa-code"></i>Modo Prog</a></li>
		                            <li><a href="#"><i class="fa fa-cog"></i> Definições</a></li>
		                            <li><a href="{{ route('logout') }}"><i class="fa fa-key"></i> Log Out</a></li>
		                        </ul>
		                    </li>
		                    <!-- user login dropdown end -->
		                </ul>
		                <!--search & user info end-->
		            </div>
		        </header>
		      <!--header end-->
		      <!--sidebar start-->
		      <aside>
		          <div id="sidebar"  class="nav-collapse ">
		              <!-- sidebar menu start-->
		              <ul class="sidebar-menu">

						@include('cms::layouts/menu')

		              </ul>
		              <!-- sidebar menu end-->
		          </div>
		      </aside>
		      <!--sidebar end-->
		      <!--main content start-->
		      <section id="main-content">
		          <section class="wrapper">

		            @yield('content')

		          </section>
		      </section>
		      <!--main content end-->
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


		<!-- Javascripts
		================================================== -->
		<script type="text/javascript">

			var $cms_url = "{{ Config::get('app.url') }}/{{ Config::get('cms::config.uri') }}";
			var $cms_ckeditor_toolbar = @if(array_key_exists('component.ckeditor.adv', $_groupPermissions))"Full"@else"Mini"@endif;


		</script>
		<script src="{{ Helpers::asset(Config::get('cms::config.assets_path').'/assets/js/jquery.js') }}"></script>
	    <script src="{{ Helpers::asset(Config::get('cms::config.assets_path').'/assets/js/bootstrap.min.js') }}"></script>
	    <script src="{{ Helpers::asset(Config::get('cms::config.assets_path').'/assets/js/jquery.scrollTo.min.js') }}"></script>
	    <script src="{{ Helpers::asset(Config::get('cms::config.assets_path').'/assets/js/jquery.nicescroll.js') }}" type="text/javascript"></script>
	    <script src="{{ Helpers::asset(Config::get('cms::config.assets_path').'/assets/plugins/noty/jquery.noty.packaged.min.js') }}" type="text/javascript"></script>

	    <script src="{{ Helpers::asset(Config::get('cms::config.assets_path').'/assets/js/jquery.multi-select.js') }}" type="text/javascript"></script>
	    <script src="{{ Helpers::asset(Config::get('cms::config.assets_path').'/assets/js/jquery.quicksearch.js') }}" type="text/javascript"></script>
	    <script src="{{ Helpers::asset(Config::get('cms::config.assets_path').'/assets/js/js.cookie.js') }}" type="text/javascript"></script>

	    <!--common script for all pages-->
	    <script src="{{ Helpers::asset(Config::get('cms::config.assets_path').'/assets/js/common-scripts.js') }}"></script>

		@section('scripts')
		@show

		<script type="text/javascript" charset="utf-8">

		@include('cms::notifications')

		$('#modal-confirm').on('show.bs.modal', function(e) {
		    $(e.currentTarget).find('#modal-bt-confirm').attr("href", $(e.relatedTarget).data('url'));
		    $(e.currentTarget).find('#modal-descr').html("# "+$(e.relatedTarget).data('descr'));
		    $(e.currentTarget).find('#modal-msg').html($(e.relatedTarget).data('msg'));
		});


		if($('.date').length) $('.date').datepicker();

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
