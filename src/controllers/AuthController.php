<?php namespace Insomnia\Cms\Controllers;

use Insomnia\Cms\Controllers\AdminController;
use Cartalyst\Sentry\Users\Eloquent\User;

use Sentry;
use Redirect;
use View;
use Validator;
use Input;
use Session;
use Lang;


class AuthController extends AdminController {

	public function __construct()
	{
		$this->messageBag = new \Illuminate\Support\MessageBag;
	}

	public function getSignin()
	{
		if (Sentry::check())
		{
			return Redirect::route('cms');
		}

		return View::make('cms::auth.signin');
	}


	public function postSignin()
	{
		$rules = array(
			'username'    => 'required|between:3,32',
			'password' => 'required|between:3,32',
		);

		$validator = Validator::make(Input::all(), $rules);

		if ($validator->fails()) {
			
			return Redirect::back()->withInput()->withErrors($validator);
			
		}

		try
		{			
            $credentials = Input::only('username', 'password');
            $user = Sentry::authenticate($credentials, Input::get('remember-me', Input::get('remember')));

			// Get the page we were before
			$redirect = Session::get('loginRedirect', 'cms');

			// Unset the page we were before from the session
			Session::forget('loginRedirect');

			return Redirect::to($redirect);	
		}
		catch (\Cartalyst\Sentry\Users\UserNotFoundException $e)
		{
			$this->messageBag->add('username', Lang::get('cms::auth/message.account_not_found'));
		}
		catch (\Cartalyst\Sentry\Users\UserNotActivatedException $e)
		{
			$this->messageBag->add('username', Lang::get('cms::auth/message.account_not_activated'));
		}
		catch (\Cartalyst\Sentry\Throttling\UserSuspendedException $e)
		{
			$this->messageBag->add('username', Lang::get('cms::auth/message.account_suspended'));
		}
		catch (\Cartalyst\Sentry\Throttling\UserBannedException $e)
		{
			$this->messageBag->add('username', Lang::get('cms::auth/message.account_banned'));
		}

		// Ooops.. something went wrong
		return Redirect::back()->withInput()->withErrors($this->messageBag);
		
		
		
	}

	public function getSignup($refCode = null)
	{
		// Is the user logged in?
		if (Sentry::check())
		{
			return Redirect::route('account');
		}

		// Show the page
		return View::make('cms::auth.signup')->with('refCode', $refCode);
	}


	public function getActivate($activationCode = null)
	{
		// Is the user logged in?
		if (Sentry::check())
		{
			return Redirect::route('account');
		}

		try
		{
			// Get the user we are trying to activate
			$user = Sentry::getUserProvider()->findByActivationCode($activationCode);

			// Try to activate this user account
			if ($user->attemptActivation($activationCode))
			{
				// Redirect to the login page
				return Redirect::route('signin')->with('success', Lang::get('cms::auth/message.activate.success'));
			}

			// The activation failed.
			$error = Lang::get('cms::auth/message.activate.error');
		}
		catch (Cartalyst\Sentry\Users\UserNotFoundException $e)
		{
			$error = Lang::get('cms::auth/message.activate.error');
		}

		// Ooops.. something went wrong
		return Redirect::route('signin')->with('error', $error);
	}

	/**
	 * Forgot password page.
	 *
	 * @return View
	 */
	public function getForgotPassword()
	{
		// Show the page
		return View::make('cms::auth.forgot-password');
	}

	/**
	 * Forgot password form processing page.
	 *
	 * @return Redirect
	 */
	public function postForgotPassword()
	{

		$rules = array(
			'email' => 'required|email',
		);

		$validator = Validator::make(Input::all(), $rules);

		if ($validator->fails()) {
            return Redirect::back()->withErrors($validator);
		}

		try
		{
			// Get the user password recovery code *fix para procurar por email
			//$user = Sentry::getUserProvider()->findByLogin(Input::get('email'));

			$emptyModelInstance = Sentry::getUserProvider()->getEmptyUser();
			$user = $emptyModelInstance->where('email', '=', Input::get('email'))->first();
			
            if($user){
                // Data to be used on the email view
                $data = array(
                    'user'              => $user,
                    'forgotPasswordUrl' => URL::route('forgot-password-confirm', $user->getResetPasswordCode()),
                );

                // Send the activation code through email
                Mail::send('emails.forgot-password', $data, function($m) use ($user)
                {
                    $m->to($user->email, $user->first_name . ' ' . $user->last_name);
                    $m->subject('Recuperação de Password');
                });
            }

		}
		catch (Cartalyst\Sentry\Users\UserNotFoundException $e)
		{
			// Even though the email was not found, we will pretend
			// we have sent the password reset code through email,
			// this is a security measure against hackers.
		}

        return Redirect::back()->with('success', Lang::get('cms::auth/message.forgot-password.success'));
	}

	/**
	 * Forgot Password Confirmation page.
	 *
	 * @param  string  $passwordResetCode
	 * @return View
	 */
	public function getForgotPasswordConfirm($passwordResetCode = null)
	{
		try
		{
			// Find the user using the password reset code
			$user = Sentry::getUserProvider()->findByResetPasswordCode($passwordResetCode);
		}
		catch(Cartalyst\Sentry\Users\UserNotFoundException $e)
		{
			// Redirect to the forgot password page
			return Redirect::route('forgot-password')->with('error', Lang::get('cms::auth/message.account_not_found'));
		}

		// Show the page
		return View::make('cms::auth.forgot-password-confirm')->with('passwordResetCode', $passwordResetCode);
	}

	/**
	 * Forgot Password Confirmation form processing page.
	 *
	 * @param  string  $passwordResetCode
	 * @return Redirect
	 */
	public function postForgotPasswordConfirm($passwordResetCode = null)
	{

		$rules = array(
			'password'         => 'required|between:3,32',
			'password_confirm' => 'required|same:password'
		);

		$validator = Validator::make(Input::all(), $rules);

		if ($validator->fails()) {
            return Redirect::back()->withErrors($validator);
		}

		try
		{

			// Find the user using the password reset code
			$user = Sentry::getUserProvider()->findByResetPasswordCode($passwordResetCode);

			// Attempt to reset the user password
			if ($user->attemptResetPassword($passwordResetCode, Input::get('password')))
			{
				// Password successfully reseted
                return Redirect::back()->with('success', Lang::get('cms::auth/message.forgot-password-confirm.success'));
			}
			else
			{
				// Ooops.. something went wrong
                return Redirect::back()->with('success', Lang::get('cms::auth/message.forgot-password-confirm.error'));
			}
		}
		catch (Cartalyst\Sentry\Users\UserNotFoundException $e)
		{
			return Redirect::route('signin')->with('error', Lang::get('cms::auth/message.account_not_found'));
		}
	}

	public function getLogout()
	{
		Sentry::logout();
		return Redirect::route('cms')->with('success', 'Logout efectuado com sucesso!');
	}

}
