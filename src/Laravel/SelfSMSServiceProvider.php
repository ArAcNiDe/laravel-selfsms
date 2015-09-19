<?php

namespace Apiseo\SelfSMS\Laravel;

use Apiseo\SelfSMS\Providers\FreeMobileSMSProvider;
use Illuminate\Support\ServiceProvider;

/**
 * Laravel service provider doing all the magic!
 * @package Apiseo\SelfSMS\Laravel
 */
class SelfSMSServiceProvider extends ServiceProvider {

	/**
	 * @inheritdoc
	 */
	protected $defer = true;

	/**
	 * @var array Default providers
	 */
	public $providers = [
		'free' => FreeMobileSMSProvider::class
	];

	/**
	 * Boot this service provider.
	 */
	public function boot() {
		$this->publishes([
			__DIR__.'/../../config/selfsms.php' => config_path('selfsms.php')
		]);
		$this->mergeConfigFrom(
			__DIR__.'/../../config/selfsms.php', 'selfsms'
		);
	}

	/**
	 * @inheritdoc
	 */
	public function register()
	{
		$this->app->bind('Apiseo\SelfSMS\SelfSMSProvider', function($app, $args) {
			return $app->make($this->resolveDriver(), $args); // On-the-fly resolution
		});
	}

	/**
	 * @inheritdoc
	 */
	public function provides()
	{
		return ['Apiseo\SelfSMS\SelfSMSProvider'];
	}

	/**
	 * Get the driver's name to use and try to get the matching provider.
	 * @return string The matching provider's full class name
	 * @throws \RuntimeException We were unable to find a provider matching the driver's name
	 */
	protected function resolveDriver() {
		$driver = config('selfsms.driver');
		$providers = array_merge($this->providers, config('selfsms.providers', []));

		if(!array_key_exists($driver, $providers))
			throw new \RuntimeException("Unknown SMS provider: {$driver}");

		return $providers[$driver];
	}

}