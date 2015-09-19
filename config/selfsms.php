<?php

return [

	/**
	 * Default drivers available:
	 *      free: Free Mobile's SMS API
	 */
	'driver' => 'free',

	'free_uid' => env('FREESMS_UID'),
	'free_key' => env('FREESMS_KEY')
];