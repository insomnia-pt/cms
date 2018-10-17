@extends('cms::layouts/default')

{{-- Page title --}}
@section('title')
Gestão de Utilizadores ::
@parent
@stop

{{-- Page content --}}
@section('content')

	<iframe id="keycloak" src="{{ $keycloakUsersUrl }}/admin/master/console" frameborder="0"></iframe>

@stop

@section('styles')

<style>

    iframe#keycloak {
        position: absolute;
        width: calc(100% - 200px);
        height: 100%;
        right: 0;
        top: 0;
        bottom: 0;
    }
</style>

@stop

@section('scripts')

@stop
