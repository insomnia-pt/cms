<?php namespace Insomnia\Cms\Models;

use Eloquent;

class Datasource extends Eloquent {

	public function config()
	{
		return json_decode($this->config);
	}

	public function options()
	{
		return json_decode($this->options);
	}

	public function permissions()
	{
		$options = json_decode($this->options);

		if(property_exists($options, "permissions")){
			return $options->permissions;
		} else {
			return null;
		}
	}

	public function relations()
	{
		return $this->hasMany('Insomnia\Cms\Models\DatasourceRelation');
	}

}
