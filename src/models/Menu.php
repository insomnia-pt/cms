<?php namespace Insomnia\Cms\Models;

use Eloquent;

class Menu extends Eloquent {

	protected $softDelete = false;
	public $timestamps = false;

	public function datasource() {
	    return $this->belongsTo('Insomnia\Cms\Models\Datasource', 'datasource_id');
	}

	public function parent() {
	    return $this->belongsTo('Insomnia\Cms\Models\Menu', 'id_parent');
	}

	public function children() {
	    return $this->hasMany('Insomnia\Cms\Models\Menu', 'id_parent');
	}

}
