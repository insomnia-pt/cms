<?php namespace Insomnia\Cms\Models;

use Eloquent;

class DatasourceFieldtype extends Eloquent {

	public $timestamps = false;
	protected $guarded = array();

	protected $table = "datasources_fieldtypes";

	public function config()
	{
		return json_decode($this->config);
	}

}
