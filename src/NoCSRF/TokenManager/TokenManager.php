<?php


namespace NoCSRF\TokenManager;


/**
 * Abstract token manager.
 * @package NoCSRF\TokenManager
 */
abstract class TokenManager
{
	/**
	 * Generate a new token.
	 * @param string $key - The key used to generate the token.
	 * @return string - The generated token.
	 */
	public abstract function newToken(string $key): string;

	/**
	 * Verify the given token using the given key.
	 * @param string $token - The token to verify.
	 * @param string $key - The key used to generate the token.
	 * @return bool - True if the token is valid, false otherwise.
	 */
	public abstract function verifyToken(string $token, string $key): bool;
}