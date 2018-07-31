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