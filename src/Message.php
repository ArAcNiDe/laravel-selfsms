<?php

namespace Apiseo\SelfSMS;

use Illuminate\Support\Fluent;

/**
 * A simple and fluent object that embeds options for the underlying provider.
 * @package Apiseo\SelfSMS
 */
class Message extends Fluent {

	protected $provider;

	/**
	 * @param SelfSMSProvider $provider Base provider
	 * @param array $options Preset options
	 */
	public function __construct(SelfSMSProvider $provider, $options = []) {
		parent::__construct($options);
		$this->provider = $provider;
	}

	/**
	 * Send a message with all the options from this object.
	 * @return bool true if the operation was successful, RuntimeException thrown otherwise
	 * @throws \RuntimeException The operation was not successful
	 */
	public function send() {
		return $this->provider->send($this->getAttributes());
	}

}