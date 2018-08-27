<?php namespace Insomnia\Cms\Models;

use Eloquent;

class MenuItem extends Eloquent {

	public $timestamps = false;
	protected $table = "menus_items";

	public function datasource() {
	    return $this->belongsTo('Insomnia\Cms\Models\Datasource', 'datasource_id');
	}

	public function parent() {
	    return $this->belongsTo('Insomnia\Cms\Models\MenuItem', 'id_parent');
	}

	public function children() {
	    return $this->hasMany('Insomnia\Cms\Models\MenuItem', 'id_parent');
	}

}
