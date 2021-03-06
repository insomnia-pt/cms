<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<title>File Manager</title>

		<!-- jQuery and jQuery UI (REQUIRED) -->
		<link rel="stylesheet" type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.23/themes/smoothness/jquery-ui.css">
	    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.0/jquery.min.js"></script>
	    <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.23/jquery-ui.min.js"></script>

		<!-- elFinder CSS (REQUIRED) -->
		<link rel="stylesheet" type="text/css" media="screen" href="{{ asset(Config::get('cms::config.packages_path').'/tsf/elfinder-laravel/css/elfinder.min.css') }}">
		<link rel="stylesheet" type="text/css" media="screen" href="{{ asset(Config::get('cms::config.packages_path').'/tsf/elfinder-laravel/themes/moono/css/theme.css') }}">

		<style type="text/css">
			html, body {
				margin: 0;
				padding:0;
				overflow: hidden;
			}
		</style>

		<!-- elFinder JS (REQUIRED) -->
		<script type="text/javascript" src="{{ asset(Config::get('cms::config.packages_path').'/tsf/elfinder-laravel/js/elfinder.min.js') }}"></script>
		<script src="{{ asset(Config::get('cms::config.packages_path').'/tsf/elfinder-laravel/js/i18n/elfinder.pt_BR.js') }}"></script>

		<!-- elFinder initialization (REQUIRED) -->
		<script type="text/javascript" charset="utf-8">	
			$().ready(function() {

				var mode = getUrlParam('mode');
				var element = getUrlParam('elementId');

				var $elfinder = $('#elfinder').elfinder({
					url : '<?= URL::action('Insomnia\Cms\Controllers\FileBrowserController@showConnector') ?>', 
					uiOptions : {
					    // toolbar configuration
					    toolbar : [
					        ['back', 'forward'],
					        // ['reload'],
					        // ['home', 'up'],
					        ['mkdir', 'mkfile', 'upload'],
					        ['open', 'download'],
					        ['info'],
					        ['quicklook'],
					        ['copy', 'cut', 'paste'],
					        ['rm'],
					        ['duplicate', 'rename', 'edit', 'resize'],
					        ['extract', 'archive'],
					        ['search'],
					        ['view']
					    ]
					},
					defaultView : 'list',
					customData : { '_token' : '<?php echo csrf_token(); ?>'},
					lang: 'pt_BR',
					resizable: false,
                	height: $(window).height()
				});

				var $window = $(window);

	            $window.resize(function(){
	                var win_height = $window.height();
	                if( $elfinder.height() != win_height ){
	                    $elfinder.height(win_height).resize();
	                }
	            });

			});

			function getUrlParam(paramName) {
	            var reParam = new RegExp('(?:[\?&]|&amp;)' + paramName + '=([^&]+)', 'i') ;
	            var match = window.location.search.match(reParam) ;
	            
	            return (match && match.length > 1) ? match[1] : '' ;
	        }

	</script>
	</head>
	<body>
		<div id="elfinder"></div>
	</body>
</html>