<!DOCTYPE html>
<html lang="pt">
	<head>
		<meta charset="utf-8" />
		<title>
			@section('title')
			Administração
			@show
		</title>
		<meta name="keywords" content="" />
		<meta name="author" content="Miguel Pereira (insomnia.pt)" />
		<meta name="description" content="" />
		<meta name="viewport" content="width=device-width, initial-scale=1">
	
	    <link href="{{ Helpers::asset(Config::get('cms::config.assets_path').'/assets/css/bootstrap.min.css') }}" rel="stylesheet">
	    <link href="{{ Helpers::asset(Config::get('cms::config.assets_path').'/assets/css/bootstrap-reset.css') }}" rel="stylesheet">
	    <link href="{{ Helpers::asset(Config::get('cms::config.assets_path').'/assets/plugins/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet" />
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

	<body class="login-body">
    	<div class="container">

		    @yield('content')

		</div>

		<script src="{{ Helpers::asset(Config::get('cms::config.assets_path').'/assets/js/jquery.js') }}"></script>
	    <script src="{{ Helpers::asset(Config::get('cms::config.assets_path').'/assets/js/bootstrap.min.js') }}"></script>
	    <script src="{{ Helpers::asset(Config::get('cms::config.assets_path').'/assets/js/jquery.scrollTo.min.js') }}"></script>
	    <script src="{{ Helpers::asset(Config::get('cms::config.assets_path').'/assets/js/jquery.nicescroll.js') }}" type="text/javascript"></script>
		<script src="{{ Helpers::asset(Config::get('cms::config.assets_path').'/assets/js/common-scripts.js') }}"></script>

		@section('scripts')
		@show
	</body>
</html>
