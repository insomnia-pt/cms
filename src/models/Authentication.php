<?php namespace Insomnia\Cms\Models;

use Eloquent;

class Authentication extends Eloquent {

	/**
	 *
	 *
	 * @return
	 */
	public function user()
	{
		return $this->belongsTo('Insomnia\Cms\Models\User');
	}

}
