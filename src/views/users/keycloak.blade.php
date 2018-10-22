@extends('cms::layouts/default')

{{-- Page title --}}
@section('title')
Gestão de Utilizadores ::
@parent
@stop

{{-- Page content --}}
@section('content')

	<iframe id="keycloak" src="{{ $iframeUrl }}" frameborder="0"></iframe>

@stop

@section('styles')

<style>

    iframe#keycloak {
        position: absolute;
        width: calc(100% - 200px);
        height: calc(100% - 60px);
        right: 0;
        top: 60px;
        bottom: 0;
    }
</style>

@stop

@section('scripts')

@stop
