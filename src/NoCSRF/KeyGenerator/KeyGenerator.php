<?php


namespace NoCSRF\KeyGenerator;


/**
 * Abstract key generator.
 * @package NoCSRF\KeyGenerator
 */
abstract class KeyGenerator
{
	/**
	 * Generate a string key to generate and verify tokens.
	 * @return string - The generated key.
	 */
	public abstract function generate(): string;
}