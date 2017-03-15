<?php namespace Insomnia\Cms\Models;

use Eloquent;

class DatasourcePage extends Eloquent {

    public $timestamps = false;
    protected $guarded = array();

    protected $table = "datasource_page";

    public function datasource()
    {
        return $this->belongsTo('Insomnia\Cms\Models\Datasource', 'datasource_id');
    }

    public function page()
    {
        return $this->belongsTo('Insomnia\Cms\Models\Page', 'page_id');
    }
}
