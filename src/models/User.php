<?php namespace Insomnia\Cms\Models;

use Illuminate\Database\Eloquent\SoftDeletingTrait;
use Eloquent;
use Cartalyst\Sentry\Users\Eloquent\User as SentryUserModel;
use Config;

class User extends SentryUserModel {

	use SoftDeletingTrait;

    protected $dates = ['deleted_at'];

	/**
	 * Returns the user full name, it simply concatenates
	 * the user first and last name.
	 *
	 * @return string
	 */
	public function fullName()
	{
		return "{$this->first_name} {$this->last_name}";
	}

	public function thumbnail($sizeW,$sizeH)
	{	
		$this->sizeW = $sizeW;
		$this->sizeH = $sizeH;

		if($this->photo){
			$userPhoto = $this->photo;
			$img = \Image::cache(function($image) use ($userPhoto, $sizeW, $sizeH) {
	   			return $image->make(Config::get('cms::config.assets_path').'/users-photo/'.$userPhoto)->fit($sizeW, $sizeH)->encode('data-url');
			}, 10);

			return $img;
		}
		else {
			$img = \Image::cache(function($image) use ($sizeW, $sizeH) {
	   			return $image->make(Config::get('cms::config.assets_path').'/assets/img/default_avatar.jpg')->fit($sizeW, $sizeH)->encode('data-url');
			}, 10);

			return $img;
		}
	}

}
