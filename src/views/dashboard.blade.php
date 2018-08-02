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
					<a class="task-thumb" href="{{ route('users/edit', $CMS_USER->id ) }}">
						<img alt="" src="{{ $CMS_USER->thumbnail(90,83) }}">
					</a>
					<div class="task-thumb-details">
						<h5>Olá,</h5>
						<h1>
							<a href="{{ route('users/edit', $CMS_USER->id ) }}">{{ $CMS_USER->fullName() }}</a>
						</h1>
					</div>
				</div>
				<table class="table table-hover personal-task">
					<tbody class="text-muted">
					<tr>
						<td><i class="fa fa-clock-o"></i> &nbsp; <small>ÚLTIMO ACESSO</small></td>
						<td>{{ $CMS_USER->last_login }}</td>
					</tr>
					<tr>
						<td><i class="fa fa-envelope"></i> &nbsp; <small>CONTACTO</small></td>
						<td>{{ $CMS_USER->email }}</td>
					</tr>
					</tbody>
				</table>
			</section>

		</div>

	
	</div>

	

@stop

@section('scripts')
@stop