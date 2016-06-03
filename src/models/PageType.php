<?php namespace Insomnia\Cms\Models;

use Eloquent;

class PageType extends Eloquent {

	public $timestamps = false;
	protected $guarded = array();

	protected $table = "pages_types";

	public function config()
	{
		return json_decode($this->config);
	}

}
