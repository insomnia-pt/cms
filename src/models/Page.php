<?php namespace Insomnia\Cms\Models;

use Eloquent;

class Page extends Eloquent {

	// public function content()
	// {
	// 	return nl2br($this->content);
	// }

	public function url()
	{
		return URL::route('view-news', $this->slug);
	}

	public function areas()
	{
		return json_decode($this->content);
	}

	public function images()
	{
		return json_decode($this->images);
	}

	public function imageFile()
	{	

		$imagePath = json_decode($this->images, true);
		return ltrim(@$imagePath["images"][0], '/');
	}

	public function pagetype()
	{
		return $this->belongsTo('Insomnia\Cms\Models\PageType', 'pagetype_id');
	}

	public function parent() {
	    return $this->belongsTo('Insomnia\Cms\Models\Page', 'id_parent');
	}

	public function children() {
	    return $this->hasMany('Insomnia\Cms\Models\Page', 'id_parent');
	}

	public function datasources()
    {
        return $this->belongsToMany('Insomnia\Cms\Models\Datasource');
    }

}
