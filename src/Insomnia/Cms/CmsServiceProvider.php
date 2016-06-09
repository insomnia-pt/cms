<?php namespace Insomnia\Cms;

use Illuminate\Support\ServiceProvider;

class CmsServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->package('insomnia/cms');
		$this->loadIncludes();

		$this->app->register('Cartalyst\Sentry\SentryServiceProvider');
		$this->app->register('Barryvdh\Elfinder\ElfinderServiceProvider');
		$this->app->register('Intervention\Image\ImageServiceProvider');
		$this->app->register('Thujohn\Analytics\AnalyticsServiceProvider');	

		\Config::set('cartalyst/sentry::users.model', 'Insomnia\Cms\Models\User');
		\Config::set('cartalyst/sentry::users.login_attribute', 'username');

		class_alias('Insomnia\Cms\Models\ModelBuilder', 'CMS_ModelBuilder');
		class_alias('Insomnia\Cms\Models\Page', 'CMS_Page');
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app['config']->package('insomnia/cms', __DIR__.'/../../config');

		$this->app->booting(function()
		{
		    $loader = \Illuminate\Foundation\AliasLoader::getInstance();
		    $loader->alias('Sentry', 'Cartalyst\Sentry\Facades\Laravel\Sentry');
		    $loader->alias('Image', 'Intervention\Image\Facades\Image');
		    $loader->alias('Analytics', 'Thujohn\Analytics\AnalyticsFacade');
		    
		});

		$this->app['cms:install'] = $this->app->share(function ($app) {
            return new Commands\InstallCommand($app);
        });

        $this->app['cms:update'] = $this->app->share(function ($app) {
            return new Commands\UpdateCommand($app);
        });

		$this->commands('cms:install');
		$this->commands('cms:update');

	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array();
	}

	/**
     * Include some specific files from the src-root.
     *
     * @return void
     */
    private function loadIncludes()
    {
        $filesToLoad = array(
            'helpers',
            'filters',
            'routes',
        );

        foreach ($filesToLoad as $file) {
            $file = __DIR__ . '/../../' . $file . '.php';
            if (is_file($file)) include $file;
        }
    }

}
