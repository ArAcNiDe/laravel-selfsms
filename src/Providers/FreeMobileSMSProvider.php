<?php

namespace Apiseo\SelfSMS\Providers;

use Apiseo\SelfSMS\SelfSMSProvider;

/**
 * This implementation provides a gateway to the Free Mobile SMS API.
 * Useful for self notifications, since you cannot specify a recipient's number.
 * @package Apiseo\SelfSMS\Providers
 */
class FreeMobileSMSProvider extends SelfSMSProvider {

	/**
	 * Free Mobile SMS API Endpoint for HTTPS connections.
	 */
	const API_ENDPOINT = 'https://smsapi.free-mobile.fr/sendmsg';

	/**
	 * @var string $user_id User ID provided by Free Mobile
	 */
	protected $user_id;

	/**
	 * @var string $api_key Secret API key to authenticate youself
	 */
	protected $api_key;

	/**
	 * @var int Maximum message length
	 */
	protected $maxlength = 160;

	/**
	 * Pass the optional arguments to override the parameters from the configuration file.
	 * @param string $user_id User ID provided by Free Mobile
	 * @param string $api_key Secret API key to authenticate youself
	 */
	public function __construct($user_id = null, $api_key = null) {
		$this->user_id = $user_id;
		$this->api_key = $api_key;
	}

	/**
	 * @inheritdoc
	 */
	public function send($options) {
		$options = $this->filterArguments($options);
		$uid = isset($options['user_id']) ? $options['user_id'] : ($this->user_id ?: config('selfsms.free_uid'));
		$key = isset($options['api_key']) ? $options['api_key'] : ($this->api_key ?: config('selfsms.free_key'));
		$message = isset($options['message']) ? $options['message'] : null;

		if(is_null($uid) || is_null($key) || is_null($message))
			throw new \RuntimeException("Missing configuration field for the Free Mobile SMS Gateway.");

		// Build the query
		$query = http_build_query(array(
			'user' => $uid,
			'pass' => $key,
			'msg' => $message
		));

		// Use CURL to send the query
		// We use GET method since the POST method is not supported (even if the Free Mobile's API says otherwise)
		$c = curl_init();
		curl_setopt($c, CURLOPT_URL, self::API_ENDPOINT.'?'.$query);
		curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($c, CURLOPT_HEADER, true);
		curl_setopt($c, CURLOPT_USERAGENT, 'User-Agent: SelfSMS');
		curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);

		// Catch internal CURL errors
		if (curl_exec($c) === false)
			throw new \RuntimeException(curl_error($c));

		$status = curl_getinfo($c, CURLINFO_HTTP_CODE);
		curl_close($c);

		// Compare returned HTTP code to documented errors
		switch ($status) {
			case 200:
				return true; // Success!
			case 400:
				// Should not happen since we cover that in our code
				// But keep it in case the API changes
				throw new \RuntimeException('A mandatory parameter is missing for the Free Mobile\'s gateway');
			case 402:
				throw new \RuntimeException('Too many messages sent to the Free Mobile\'s gateway in a short period of time');
			case 403:
				throw new \RuntimeException('The Free Mobile\'s SMS notification service is not activated or the credentials are invalid');
			case 500:
				throw new \RuntimeException('The Free Mobile\'s gateway is unavailable, please try again later');
			default:
				throw new \RuntimeException('The Free Mobile\'s gateway returned an unknown error');
		}
	}

}