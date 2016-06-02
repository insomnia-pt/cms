<?php

Route::filter('admin-auth', function()
{
    // Check if the user is logged in
    if (!Sentry::check())
    {
        // Store the current uri in the session
        Session::put('loginRedirect', Request::url());

        // Redirect to the login page
        return Redirect::route('signin');
    }

    // Check if the user has access to the admin page
    if (!Sentry::getUser()->hasAnyAccess(array('admin','backoffice')))
    {
        return App::abort(403);
    }
});


Route::filter('idioma', function()
{
    $locale = Request::segment(1);
    if(array_key_exists($locale, Config::get('app.languages')))
    {
        \App::setLocale($locale);
        
    } else {
        return Redirect::to(Config::get('app.locale'));
        \App::setLocale(Config::get('app.locale'));
    }

});


// include('_ext/filters.php');


// Route::filter('basicAuth', function () {
//     if(!Sentry::check()) {
//         // save the attempted url
//         Session::put('attemptedUrl', URL::current());
//         return Redirect::route('getLogin');
//     }
//     View::share('currentUser', Sentry::getUser());
// });
// Route::filter('notAuth', function () {
//     if(Sentry::check()) {
//         $url = Session::get('attemptedUrl');
//         if(!isset($url)) {
//             $url = URL::route('indexDashboard');
//         }
//         Session::forget('attemptedUrl');
//         return Redirect::to($url);
//     }
// });
// Route::filter('hasPermissions', function ($route, $request, $userPermission = null) {
//     if (
//         Route::currentRouteNamed('putUser') && Sentry::getUser()->id == Request::segment(3)
//         ||
//         Route::currentRouteNamed('showUser') && Sentry::getUser()->id == Request::segment(3)
//     ) {
//     } else {
//         if($userPermission === null) {
//             $permissions = Config::get('syntara::permissions');
//             $permission = $permissions[Route::current()->getName()];
//         } else {
//             $permission = $userPermission;
//         }
//         if(!Sentry::getUser()->hasAccess($permission)) {
//             return App::abort(403);
//         }
//     }
// });


// App::error(function (Exception $exception, $code) {
//     View::share('currentUser', Sentry::getUser());
//     $exceptionMessage = $exception->getMessage();
//     $message = !empty($exceptionMessage) ? $exceptionMessage : Lang::trans('syntara::all.messages.error.403');
//     if(403 === $code) {
//         return Response::view(
//             Config::get('syntara::views.error'),
//             array(
//                 'message' => $message,
//                 'code'=>$code,
//                 'title'=>Lang::trans('syntara::all.messages.error.403-title')
//             )
//         );
//     }
//     if(App::environment('production') || !Config::get('app.debug')) {
//         switch ($code) {
//             case 404:
//                 return Response::view(
//                     Config::get('syntara::views.error'),
//                     array(
//                         'message' => Lang::trans('syntara::all.messages.error.404'),
//                         'code'=>$code,
//                         'title'=>Lang::trans('syntara::all.messages.error.404-title')
//                     )
//                 );
//             case 500:
//                 return Response::view(
//                     Config::get('syntara::views.error'),
//                     array(
//                         'message' => Lang::trans('syntara::all.messages.error.500'),
//                         'code'=>$code,
//                         'title'=>Lang::trans('syntara::all.messages.error.500-title')
//                     )
//                 );
//             default:
//                 return Response::view(
//                     Config::get('syntara::views.error'),
//                     array(
//                         'message' => Lang::trans('syntara::all.messages.error.default'),
//                         'code'=>$code,
//                         'title'=>Lang::trans('syntara::all.messages.error.default-title')
//                     )
//                 );
//         }
//     }
// });