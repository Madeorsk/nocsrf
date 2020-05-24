<?php


namespace NoCSRF\KeyGenerator;


/**
 * OpenSSL key generator.
 * @package NoCSRF\KeyGenerator
 */
class OpensslKeyGenerator extends KeyGenerator
{
	/**
	 * @var int
	 */
	protected $bytesNumber;

	/**
	 * Create a new OpenSSL key generator.
	 * @param int $bytes_number - Number of random bytes to generate. This is not the string size, as the random bytes are encoded in base64.
	 */
	public function __construct($bytes_number = 32)
	{
		$this->bytesNumber = $bytes_number;
	}

	/**
	 * @inheritDoc
	 */
	public function generate(): string
	{
		// Generate random bytes and convert it to base64 to get a string.
		return base64_encode(openssl_random_pseudo_bytes($this->bytesNumber));
	}
}