<?php

namespace Apiseo\SelfSMS\Laravel;

use Illuminate\Support\Facades\Facade;

/**
 * Laravel facade for the SMS provider
 * @package Apiseo\SelfSMS\Laravel
 */
class SelfSMSFacade extends Facade {

	/**
	 * @inheritdoc
	 */
	protected static function getFacadeAccessor()
	{
		return 'Apiseo\SelfSMS\SelfSMSProvider';
	}

}