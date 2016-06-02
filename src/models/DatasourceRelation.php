<?php namespace Insomnia\Cms\Models;

use Eloquent;

class DatasourceRelation extends Eloquent {

	protected $softDelete = false;
	public $timestamps = false;
	protected $guarded = array();

	protected $table = "datasources_relations";

	public function config()
	{
		return json_decode($this->config);
	}

	public function datasource()
	{
		return $this->belongsTo('Insomnia\Cms\Models\Datasource', 'datasource_id');
	}

	public function relationdatasource()
	{
		return $this->belongsTo('Insomnia\Cms\Models\Datasource', 'relation_datasource_id');
	}
}
