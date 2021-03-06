<?php namespace Insomnia\Cms\Controllers;

use Insomnia\Cms\Controllers\AdminController;
use Insomnia\Cms\Models\DatasourcePage;
use Insomnia\Cms\Models\Page as Page;
use Insomnia\Cms\Models\PageType as PageType;
use Insomnia\Cms\Models\PageHistory as PageHistory;
use Insomnia\Cms\Models\Datasource as Datasource;
use Insomnia\Cms\Models\DatasourceFieldtype as DatasourceFieldtype;
use Insomnia\Cms\Models\Setting as Setting;
use Input;
use Lang;
use Redirect;
use Validator;
use View;
use Helpers;
use Session;
use Sentry;
use Str;
use URL;

class PagesController extends AdminController {

	public function getIndex()
	{
		AdminController::checkPermission('pages.view');

		$pages = Page::where('visible', 1)->orderBy('order')->get();

		$globalPageSettings = Setting::where('name', 'page_global')->first()->config();
		$pageGlobal = null;

		if($globalPageSettings->active){
			$pageGlobal = Page::where('pagetype_id', $globalPageSettings->pagetype_id)->first();
		}

		$datasource = Datasource::where('table', 'pages')->first();
		$parentPages = $pages->filter(function($page) {
		    return $page->id_parent == 0;
		})->values();

		if(@!$datasource->options()->subitems && @$datasource->options()->group && !Input::get('group')){
			return Redirect::to('cms/pages?group='.$parentPages->first()->id);
		}

		return View::make('cms::pages/index', compact('pages','datasource','parentPages','pageGlobal'));
	}

	public function getCreate()
	{
		AdminController::checkPermission('pages.create');

		$languages = null;
		$pageTypes = PageType::where('system', 0)->get();
		$pageTypeSel = null;
		$datasourceFieldtypes = null;

		if(Input::get('pageType')) {
			$languages = Setting::where('name', 'languages')->first()->config();
			$datasource = Datasource::where('table', 'pages')->first();
			$pageTypeSel = PageType::find(Input::get('pageType'));
			$datasourceFieldtypes = DatasourceFieldtype::orderBy('id')->get();
		}
		return View::make('cms::pages/create', compact('pageTypes','pageTypeSel','datasourceFieldtypes', 'datasource', 'languages'));
	}

	public function postCreate()
	{
		AdminController::checkPermission('pages.create');

		$rules = array(
			'pageType'   => 'required',
			'title'   => 'required|min:3',
			'slug'		=> 'required|unique:pages,slug',
		);

		$slugOrigin = Input::get('slug');
		$slug = Input::get('slug')?('/'.Input::get('slug')):('/'.Helpers::slugify(Input::get('title')));
		Input::merge(array('slug' => $slug));

		$inputs = Input::except('_token');
		$validator = Validator::make($inputs, $rules);

		if ($validator->fails()) {
			Input::merge(array('slug' => $slugOrigin));
			return Redirect::back()->withInput()->withErrors($validator);
		}

        $pageType = PageType::find(Input::get('pageType'));

		$page = new Page;
		$page->pagetype_id    = $pageType->id;
		$page->slug           = $slug;
		$page->title          = Input::get('title');
		$page->content        = json_encode(Input::except('_token','title','pageType','group','slug'));
		if(isset($inputs['id_parent'])) { $page->id_parent = Input::get('id_parent'); }

		if($page->save()) {

		    //SE HOUVER "DATASOURCES" NO TIPO DE PÁGINA, CRIA DATASOURCES E ASSOCIA-OS À PÁGINA
            if(@count($pageType->datasources())){
                $datasourcesIds = [];
                foreach ($pageType->datasources() as $pageTypeDatasource){
                    $datasource = DatasourcesController::DsCreate($pageTypeDatasource['name']." - ".Input::get('title'), 0, $pageTypeDatasource['model']);
                    DatasourcePage::create(array('page_id' => $page->id, 'datasource_id' => $datasource->id));
                    array_push($datasourcesIds, $datasource->id);
                }

                $pageContent = Input::except('_token','title','pageType','group','slug');
                $page->content = json_encode(array_add($pageContent, 'datasources', $datasourcesIds));
                $page->save();
            }
            ////

			$pageVersion = new PageHistory();
			$pageVersion->page_id = $page->id;
			$pageVersion->page_type = $page->pagetype_id;
			$pageVersion->user_id = \Sentry::getUser()->id;
			$pageVersion->title = $page->title;
			$pageVersion->slug = $page->slug;
			$pageVersion->content = $page->content;
			$pageVersion->save();

			return Redirect::route('pages/edit', $page->id)->with('success',Lang::get('cms::pages/message.success.create'));
		}

		return Redirect::route('pages/create')->with('error',Lang::get('cms::pages/message.error.create'));
	}

	public function getEdit($id = null)
	{
		AdminController::checkPermission('pages.view');

		if (is_null($page = Page::find($id)))
		{
			return Redirect::route('pages')->with('error',Lang::get('cms::pages/message.does_not_exist'));
		}

		$globalPageSettings = Setting::where('name', 'page_global')->first()->config();
		$pageGlobal = false;

		if($globalPageSettings->active && ($globalPageSettings->pagetype_id == $page->pagetype_id)){
			$pageGlobal = true;
		}

		$languages = Setting::where('name', 'languages')->first()->config();
		$datasource = Datasource::where('table', 'pages')->first();
		$datasourceFieldtypes = DatasourceFieldtype::get();
		$hasDatasources = count($page->datasources);
		return View::make('cms::pages/edit', compact('page','datasourceFieldtypes','datasource','hasDatasources','pageGlobal','languages'));
	}


	public function postEdit($id = null)
	{
		AdminController::checkPermission('pages.update');

		if (is_null($page = Page::find($id)))
		{
			return Redirect::to('cms/pages')->with('error',Lang::get('cms::pages/message.does_not_exist'));
		}

		$rules = array(
			'title'   => 'required|min:3',
		);
		$inputs = Input::except('_token');

		$validator = Validator::make($inputs, $rules);

		if ($validator->fails())
		{
			return Redirect::back()->withInput()->withErrors($validator);
		}

		if(@count($page->contentdatasources())){
            $pageContent = Input::except('_token','title','pageType','group');
            $page->content = json_encode(array_add($pageContent, 'datasources', $page->contentdatasources()));
        } else {
            $page->content = json_encode(Input::except('_token','title','pageType','group'));
        }

		$page->title          = Input::get('title');

		if(isset($inputs['id_parent'])) { $page->id_parent = Input::get('id_parent'); }

		if($page->save()) {

			$pageVersion = new PageHistory();
			$pageVersion->page_id = $page->id;
			$pageVersion->page_type = $page->pagetype_id;
			$pageVersion->user_id = \Sentry::getUser()->id;
			$pageVersion->title = $page->title;
			$pageVersion->slug = $page->slug;
			$pageVersion->content = $page->content;
			$pageVersion->save();

			return Redirect::to("cms/pages/$id/edit".(Input::get('group')?'?group='.Input::get('group'):null))->with('success',Lang::get('cms::pages/message.success.update'));
		}

		return Redirect::to("cms/pages/$id/edit")->with('error',Lang::get('cms::pages/message.error.update'));
	}

	public function getDelete($id)
	{

        AdminController::checkPermission('pages.delete');
        if (is_null($page = Page::find($id)))
        {
            return Redirect::to('cms/pages')->with('error',Lang::get('cms::pages/message.does_not_exist'));
        }


        Page::where('id_parent', $page->id)->update(array('id_parent' => null));
        $page->datasources()->detach();

        //SE HOUVER "DATASOURCES" NO "CONTENT" DA PÁGINA, ELIMINA-OS
        if(@count($page->contentdatasources())) {
            foreach ($page->contentdatasources() as $contentdatasource){
                $datasource = Datasource::find($contentdatasource);
                DatasourcesController::DsDelete($datasource);
            }
        }
        ////

        $page->delete();

		return Redirect::to('cms/pages'.(Input::get('group')?'?group='.Input::get('group'):null))->with('success',Lang::get('cms::pages/message.success.delete'));
	}
}
