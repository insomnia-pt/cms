<!DOCTYPE html>
<html lang="pt">
	<head>
		<!-- Basic Page Needs
		================================================== -->
		<meta charset="utf-8" />
		<title>
			@section('title')
			Administração
			@show
		</title>
		<meta name="keywords" content="" />
		<meta name="author" content="Miguel Pereira (insomnia.pt)" />
		<meta name="description" content="" />

		<!-- Mobile Specific Metas
		================================================== -->
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<!-- CSS
		================================================== -->
		<!-- Bootstrap core CSS -->
	    <link href="{{ asset('packages/insomnia/cms/cms-res/assets/css/bootstrap.min.css') }}" rel="stylesheet">
	    <link href="{{ asset('packages/insomnia/cms/cms-res/assets/css/bootstrap-reset.css') }}" rel="stylesheet">
	    <!--external css-->
	    <link href="{{ asset('packages/insomnia/cms/cms-res/assets/plugins/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet" />
	    <!-- Custom styles for this template -->
	    <link href="{{ asset('packages/insomnia/cms/cms-res/assets/css/style.css') }}" rel="stylesheet">
	    <link href="{{ asset('packages/insomnia/cms/cms-res/assets/css/style-responsive.css') }}" rel="stylesheet" />
	    <link href="{{ asset('packages/insomnia/cms/cms-res/assets/css/_ext/theme.css') }}" rel="stylesheet" />

	    <!-- HTML5 shim and Respond.js IE8 support of HTML5 tooltipss and media queries -->
	    <!--[if lt IE 9]>
	      <script src="{{ asset('packages/insomnia/cms/cms-res/assets/js/html5shiv.js') }}"></script>
	      <script src="{{ asset('packages/insomnia/cms/cms-res/assets/js/respond.min.js') }}"></script>
	    <![endif]-->


		@section('styles')
		@show

		<!-- start: Favicon -->
		<link rel="shortcut icon" href="{{ asset('packages/insomnia/cms/cms-res/assets/img/favicon.png') }}">
		<!-- end: Favicon -->
	</head>

	<body class="login-body">

    	<div class="container">

		    @yield('content')
		
		</div>
		
		<!-- Javascripts
		================================================== -->
		<script src="{{ asset('packages/insomnia/cms/cms-res/assets/js/jquery.js') }}"></script>
	    <script src="{{ asset('packages/insomnia/cms/cms-res/assets/js/bootstrap.min.js') }}"></script>
	    <script src="{{ asset('packages/insomnia/cms/cms-res/assets/js/jquery.scrollTo.min.js') }}"></script>
	    <script src="{{ asset('packages/insomnia/cms/cms-res/assets/js/jquery.nicescroll.js') }}" type="text/javascript"></script>

	    <!--common script for all pages-->
	    <script src="{{ asset('packages/insomnia/cms/cms-res/assets/js/common-scripts.js') }}"></script>

		@section('scripts')
		@show
	</body>
</html>
