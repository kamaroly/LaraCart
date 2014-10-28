<?php namespace Kamaro\Facades;

use Illuminate\Support\Facades\Facade;

class Cart extends Facade {

	protected static function getFacadeAccessor()
	{
		return 'cart';
	}


   public static function test(){
   	return 'test nyine';
   }
}