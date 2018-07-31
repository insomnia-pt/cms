<?php


Route::group(array('prefix' => Config::get('cms::config.uri')), function () {

	Route::get('/modo-programador', array('as' => 'admin/programador', 'uses' => 'Insomnia\Cms\Controllers\DashboardController@modoProgramador'));
	Route::get('/set-lang/{lang}', array('as' => 'admin/setlang', 'uses' => 'Insomnia\Cms\Controllers\DashboardController@setLang'));

	Route::group(array('prefix' => 'auth'), function()
	{
		# Login
		Route::get('signin', array('as' => 'signin', 'uses' => 'Insomnia\Cms\Controllers\AuthController@getSignin'));
		Route::post('signin', 'Insomnia\Cms\Controllers\AuthController@postSignin');

		# Register
//		Route::get('signup', array('as' => 'signup', 'uses' => 'AuthController@getSignup'));
//		Route::get('signup/{refCode}', 'AuthController@getSignup');
//		Route::post('signup', 'AuthController@postSignup');

		# Account Activation
		Route::get('activate/{activationCode}', array('as' => 'activate', 'uses' => 'Insomnia\Cms\Controllers\AuthController@getActivate'));

		# Forgot Password
		Route::get('forgot-password', array('as' => 'forgot-password', 'uses' => 'Insomnia\Cms\Controllers\AuthController@getForgotPassword'));
		Route::post('forgot-password', 'AuthController@postForgotPassword');

		# Forgot Password Confirmation
		Route::get('forgot-password/{passwordResetCode}', array('as' => 'forgot-password-confirm', 'uses' => 'Insomnia\Cms\Controllers\AuthController@getForgotPasswordConfirm'));
		Route::post('forgot-password/{passwordResetCode}', 'Insomnia\Cms\Controllers\AuthController@postForgotPasswordConfirm');

		# Logout
		Route::get('logout', array('as' => 'logout', 'uses' => 'Insomnia\Cms\Controllers\AuthController@getLogout'));

	});



	# Upload
	Route::post('upload', array('as' => 'upload', 'uses' => 'Insomnia\Cms\Controllers\UploaderController@upload'));


	# File Management
	// Route::group(array('before' => 'auth'), function()
	// {
        Route::any('filebrowser', 'Insomnia\Cms\Controllers\FileBrowserController@getIndex');
		Route::get('elfinder', array('as' => 'cms/elfinder', function() { return View::make('cms::elfinder.elfinder'); }));
        Route::any('elfinder/connector', 'Insomnia\Cms\Controllers\FileBrowserController@showConnector');
        Route::get('elfinder/ckeditor', function() { return View::make('cms::elfinder.elfinder-cke'); });
        Route::get('elfinder/select', function() { return View::make('cms::elfinder.elfinder-select'); });
	// });

	# Pages Management
	Route::group(array('prefix' => 'pages'), function()
	{
		Route::get('/', array('as' => 'pages', 'uses' => 'Insomnia\Cms\Controllers\PagesController@getIndex'));
		Route::get('create', array('as' => 'pages/create', 'uses' => 'Insomnia\Cms\Controllers\PagesController@getCreate'));
		Route::post('create', 'Insomnia\Cms\Controllers\PagesController@postCreate');
		Route::get('{pageId}/edit', array('as' => 'pages/edit', 'uses' => 'Insomnia\Cms\Controllers\PagesController@getEdit'));
		Route::post('{pageId}/edit', 'Insomnia\Cms\Controllers\PagesController@postEdit');
		Route::get('{pageId}/delete', array('as' => 'pages/delete', 'uses' => 'Insomnia\Cms\Controllers\PagesController@getDelete'));
	});

	# Data Sources Management
	Route::group(array('prefix' => 'datasources'), function()
	{
		Route::get('/', array('as' => 'datasources', 'uses' => 'Insomnia\Cms\Controllers\DatasourcesController@getIndex'));
		Route::get('create', array('as' => 'datasources/create', 'uses' => 'Insomnia\Cms\Controllers\DatasourcesController@getCreate'));
		Route::post('create', 'Insomnia\Cms\Controllers\DatasourcesController@postCreate');
		Route::get('{datasourceId}/edit', array('as' => 'datasources/edit', 'uses' => 'Insomnia\Cms\Controllers\DatasourcesController@getEdit'));
		Route::post('{datasourceId}/edit', 'Insomnia\Cms\Controllers\DatasourcesController@postEdit');
		Route::post('{datasourceId}/edit/relation/create', 'Insomnia\Cms\Controllers\DatasourcesController@postEditRelationCreate');
		Route::post('{datasourceId}/edit/field/create', 'Insomnia\Cms\Controllers\DatasourcesController@postEditFieldCreate');
		Route::post('{datasourceId}/edit/field/{fieldName}/edit', 'Insomnia\Cms\Controllers\DatasourcesController@postEditFieldEdit');
		Route::get('{datasourceId}/relation/{relationId}/delete', array('as' => 'delete/datasource/relation', 'uses' =>'Insomnia\Cms\Controllers\DatasourcesController@getDeleteRelation'));
		Route::get('{datasourceId}/field/{fieldName}/delete', array('as' => 'delete/datasource/field', 'uses' =>'Insomnia\Cms\Controllers\DatasourcesController@getDeleteField'));
		Route::get('{datasourceId}/delete', array('as' => 'datasources/delete', 'uses' => 'Insomnia\Cms\Controllers\DatasourcesController@getDelete'));
		Route::get('{datasourceId}/delete/all', array('as' => 'delete/datasource/all', 'uses' => 'Insomnia\Cms\Controllers\DatasourcesController@getDeleteAll'));
	});

	# Menu Management
	Route::group(array('prefix' => 'menu'), function()
	{
		Route::get('/{groupId?}', array('as' => 'admin/menu', 'uses' => 'Insomnia\Cms\Controllers\MenuController@getIndex'));
		Route::post('/{groupId?}', 'Insomnia\Cms\Controllers\MenuController@postEdit');
	});



	# Data Source Table Management
	Route::group(array('prefix' => 'ds'), function()
	{
		Route::get('/{datasourceId}', array('as' => 'cms/ds', 'uses' => 'Insomnia\Cms\Controllers\DsController@getIndex'));
		Route::post('/{datasourceId}/order', array('as' => 'cms/ds/order', 'uses' => 'Insomnia\Cms\Controllers\DsController@postOrder'));
		Route::post('/{datasourceId}/edit/fromcomponent', array('as' => 'cms/ds/edit/fromcomponent', 'uses' => 'Insomnia\Cms\Controllers\DsController@postAjaxComponent'));
		Route::get('{datasourceId}/create', array('as' => 'cms/ds/create', 'uses' => 'Insomnia\Cms\Controllers\DsController@getCreate'));
		Route::post('{datasourceId}/create', 'Insomnia\Cms\Controllers\DsController@postCreate');
		Route::get('{datasourceId}/edit/{itemId}', array('as' => 'cms/ds/edit', 'uses' => 'Insomnia\Cms\Controllers\DsController@getEdit'));
		Route::post('{datasourceId}/edit/{itemId}', 'Insomnia\Cms\Controllers\DsController@postEdit');
		Route::get('{datasourceId}/delete/{itemId}', array('as' => 'cms/ds/delete', 'uses' => 'Insomnia\Cms\Controllers\DsController@getDelete'));
		Route::get('{datasourceId}/sub/{itemId}/{subDatasourceId}', array('as' => 'cms/ds/sub', 'uses' => 'Insomnia\Cms\Controllers\DsController@getSubIndex'));
		// Route::get('{datasourceId}/delete/all', array('as' => 'delete/datasource/all', 'uses' => 'Insomnia\Cms\Controllers\DatasourcesController@getDeleteAll'));
	});

	# User Management
	Route::group(array('prefix' => 'users'), function()
	{
		Route::get('/', array('as' => 'users', 'uses' => 'Insomnia\Cms\Controllers\UsersController@getIndex'));
		Route::get('import', array('as' => 'users/import', 'uses' => 'Insomnia\Cms\Controllers\UsersController@getImport'));
		Route::post('import', 'Insomnia\Cms\Controllers\UsersController@postImport');
		Route::get('create', array('as' => 'users/create', 'uses' => 'Insomnia\Cms\Controllers\UsersController@getCreate'));
		Route::post('create', 'Insomnia\Cms\Controllers\UsersController@postCreate');
		Route::get('{userId}/edit', array('as' => 'users/edit', 'uses' => 'Insomnia\Cms\Controllers\UsersController@getEdit'));
		Route::post('{userId}/edit', 'Insomnia\Cms\Controllers\UsersController@postEdit');
		Route::get('{userId}/delete', array('as' => 'users/delete', 'uses' => 'Insomnia\Cms\Controllers\UsersController@getDelete'));
		Route::get('{userId}/restore', array('as' => 'users/restore', 'uses' => 'Insomnia\Cms\Controllers\UsersController@getRestore'));
	});

	# Group Management
	Route::group(array('prefix' => 'groups'), function()
	{
		Route::get('/', array('as' => 'groups', 'uses' => 'Insomnia\Cms\Controllers\GroupsController@getIndex'));
		Route::get('create', array('as' => 'groups/create', 'uses' => 'Insomnia\Cms\Controllers\GroupsController@getCreate'));
		Route::post('create', 'Insomnia\Cms\Controllers\GroupsController@postCreate');
		Route::get('{groupId}/edit', array('as' => 'groups/edit', 'uses' => 'Insomnia\Cms\Controllers\GroupsController@getEdit'));
		Route::post('{groupId}/edit', 'Insomnia\Cms\Controllers\GroupsController@postEdit');
		Route::get('{groupId}/delete', array('as' => 'groups/delete', 'uses' => 'Insomnia\Cms\Controllers\GroupsController@getDelete'));
		Route::get('{groupId}/restore', array('as' => 'groups/restore', 'uses' => 'Insomnia\Cms\Controllers\GroupsController@getRestore'));
	});

	# Settings Management
	
	Route::get('/settings', array('as' => 'settings', 'uses' => 'Insomnia\Cms\Controllers\SettingsController@getIndex'));
	

	# Dashboard
	Route::get('/dashboard', array('as' => 'cms', 'uses' => 'Insomnia\Cms\Controllers\DashboardController@getIndex'));
	Route::get('/', function(){
		return Redirect::route('cms');
	});

	// include('_ext/routes.php');


	// Route::get('teste', function(){
	// 	return 'ok';
	// });
});
