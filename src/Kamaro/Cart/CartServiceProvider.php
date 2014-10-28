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
		$this->app->bind('cart','Kamaro\Cart\Cart');
	}

	public function boot(){

		$this->package('Kamaro/Cart');

		AliasLoader::getInstance()->alias('Cart','Kamaro\Cart\Cart');
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array('cart');
	}

}
