<?php namespace Kamaro\Cart;

use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\AliasLoader;

class CartServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app['LaraCart'] = $this->app->share(function($app)
                              {
                                return new LaraCart;
                              });
	}

	public function boot(){

		$this->package('Kamaro/Cart');

		AliasLoader::getInstance()->alias('LaraCart','Kamaro\Cart\LaraCart');
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array('LaraCart');
	}

}
