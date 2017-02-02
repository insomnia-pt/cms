@extends('cms::layouts/default')

{{-- Page title --}}
@section('title')
Dashboard ::
@parent
@stop

{{-- Page content --}}
@section('content')
	
	<div class="row state-overview">
		<div class="col-lg-5">
			<section class="panel">
				<div class="panel-body">
					<a class="task-thumb" href="{{ route('users/edit', Sentry::getUser()->id ) }}">
						<img alt="" src="{{ Sentry::getUser()->thumbnail(90,83) }}">
					</a>
					<div class="task-thumb-details">
						<h5>Olá,</h5>
						<h1>
							<a href="{{ route('users/edit', Sentry::getUser()->id ) }}">{{ Sentry::getUser()->fullName() }}</a>
						</h1>
					</div>
				</div>
				<table class="table table-hover personal-task">
					<tbody class="text-muted">
					<tr>
						<td><i class="fa fa-clock-o"></i> &nbsp; <small>ÚLTIMO ACESSO</small></td>
						<td>{{ Sentry::getUser()->last_login }}</td>
					</tr>
					<tr>
						<td><i class="fa fa-envelope"></i> &nbsp; <small>CONTACTO</small></td>
						<td>{{ Sentry::getUser()->email }}</td>
					</tr>
					</tbody>
				</table>
			</section>

		</div>

		@if($ga_access_token)
		<div class="col-md-7">
			<section class="panel">
				<div class="panel-body">
					<div id="chart-container"></div>
				</div>
			</section>
		</div>
		@endif
	</div>

	

@stop

@section('scripts')
@if($ga_access_token)
	<script>
	(function(w,d,s,g,js,fs){
	  g=w.gapi||(w.gapi={});g.analytics={q:[],ready:function(f){this.q.push(f);}};
	  js=d.createElement(s);fs=d.getElementsByTagName(s)[0];
	  js.src='https://apis.google.com/js/platform.js';
	  fs.parentNode.insertBefore(js,fs);js.onload=function(){g.load('analytics');};
	}(window,document,'script'));
	</script>

	<script>

	gapi.analytics.ready(function() {

	  /**
	   * Authorize the user with an access token obtained server side.
	   */
	  gapi.analytics.auth.authorize({
		'serverAuth': {
		  'access_token': '{{ $ga_access_token->access_token }}'
		}
	  });


		var dataChart = new gapi.analytics.googleCharts.DataChart({
			query: {
				'ids': 'ga:84965092',
				metrics: 'ga:sessions',
				dimensions: 'ga:date',
				'start-date': '30daysAgo',
				'end-date': 'yesterday'
			},
			chart: {
			  container: 'chart-container',
			  type: 'LINE',
			  options: {
				width: '100%'
			  }
			}
		});

		dataChart.execute();



	  // var dataChart1 = new gapi.analytics.googleCharts.DataChart({
	  //   query: {
	  //     'ids': 'ga:84965092', // The Demos & Tools website view.
	  //     'start-date': '30daysAgo',
	  //     'end-date': 'yesterday',
	  //     'metrics': 'ga:sessions,ga:users',
	  //     'dimensions': 'ga:date'
	  //   },
	  //   chart: {
	  //     'container': 'chart-1-container',
	  //     'type': 'LINE',
	  //     'options': {
	  //       'width': '100%'
	  //     }
	  //   }
	  // });
	  // dataChart1.execute();



	});
@endif
</script>

@stop