<?php namespace Gchin\Anglar;

use Illuminate\Support\ServiceProvider;

class AnglarServiceProvider extends ServiceProvider {

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
		$this->package('gchin/anglar');
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app['AnglarCommand'] = $this->app->share(function($app)
        {
            return new AnglarCommand;
        });

        $this->commands('AnglarCommand');
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

}
