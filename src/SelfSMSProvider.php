<?php

namespace Apiseo\SelfSMS;

/**
 * Abstract provider implementation
 * @package Apiseo\SelfSMS
 */
abstract class SelfSMSProvider {

	const LENGTH_NOLIMIT = -1;

	/**
	 * @var int Maximum message length. Default = LENGTH_NOLIMIT = -1
	 */
	protected $maxlength = self::LENGTH_NOLIMIT;

	/**
	 * Send the message with the following options.
	 * @param array|object|null $options Options to pass to the provider.
	 * @return Message Message object with a Fluent interface.
	 */
	public function make($options = []) {
		return new Message($this, $options);
	}

	/**
	 * Send a message with the following options.
	 * @param array|string $options Options to pass to the provider
	 * @return bool true if the operation was successful, RuntimeException thrown otherwise
	 * @throws \RuntimeException The operation was not successful
	 */
	abstract public function send($options);

	/**
	 * Muse be used by providers to filter out arguments from the Message fluent interface, and validate the message length.
	 * @param array $in Arguments collection
	 * @return array Filtered array
	 * @throws \RuntimeException The message is too long
	 */
	protected function filterArguments($in) {
		if(is_string($in))
			return [ 'message' => $in ];

		$out = array();
		foreach($in as $k => $v) {
			$nk = trim(
				preg_replace_callback(
					"/[A-Z]/",
					function($matches) { return "_".strtolower($matches[0]); },
					preg_replace( "/^with(.*)$/", '\1', $k )
				),
				"_"
			);
			if($nk == "message") {
				if($this->maxlength != self::LENGTH_NOLIMIT && ($len = strlen($v)) > $this->maxlength)
					throw new \RuntimeException( sprintf("This provider has a limit of %i characters, %i given.", $this->maxlength, $len) );
				$v = str_replace(array("\r\n", "\r"), "\n", $v);
			}
			$out[$nk] = $v;
		}
		return $out;
	}

}