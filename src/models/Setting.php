<?php namespace Insomnia\Cms\Models;

use Eloquent;

class Setting extends Eloquent {

	protected $softDelete = false;

	public function parent() {
	    return $this->belongsTo('Insomnia\Cms\Models\Setting', 'id_parent');
	}

	public function children() {
	    return $this->hasMany('Insomnia\Cms\Models\Setting', 'id_parent');
	}

	public function config()
	{
		return json_decode($this->value);
	}

}
