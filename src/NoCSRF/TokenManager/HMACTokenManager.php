<?php


namespace NoCSRF\TokenManager;


use NoCSRF\Exceptions\SessionException;
use NoCSRF\Session;

/**
 * HMAC token manager class.
 * @package NoCSRF\TokenManager
 */
class HMACTokenManager extends TokenManager
{
	/**
	 * Hash algorithm.
	 * @var string
	 */
	protected $algo;
	/**
	 * Session manager.
	 * @var Session
	 */
	protected $session;

	/**
	 * Create a HMAC token manager.
	 * @param string $algo - Name of the hash algorithm.
	 */
	public function __construct(string $algo = "sha512")
	{
		$this->algo = $algo;

		// Initialize the session manager.
		$this->session = new Session();
	}

	/**
	 * Get the hash algorithm.
	 * @return string - Name of the hash algorithm.
	 */
	public function getAlgorithm()
	{
		return $this->algo;
	}

	/**
	 * Get current time in milliseconds.
	 * @return string - The time in milliseconds.
	 */
	private function _currentTimeMillis(): string
	{
		// Get time in microseconds and convert it in string.
		// microtime returns the time in seconds with microseconds precision.
		return round(microtime(true)*1000)."";
	}
	/**
	 * Generate token for the specified time with the given key.
	 * @param string $key - Key used to hash the raw token.
	 * @param string $time - Timestamp of the token.
	 * @return string - The token.
	 * @throws SessionException - Thrown when session ID is not available.
	 */
	protected function generateToken(string $key, string $time): string
	{
		return hash_hmac($this->getAlgorithm(),
			// Hashing the generated string with the given key using the configured algorithm.
			// The generated string is the session ID concatenated to the given timestamp.
			$this->session->getSessionID().$time, $key) . ".{$time}"; // Appending timestamp used to generate the token.
	}
	/**
	 * Generate a new token.
	 * @param string $key - Key used to hash the token.
	 * @return string - The token.
	 * @throws SessionException - Thrown when session ID is not available.
	 */
	public function newToken(string $key): string
	{
		// Generate a token using the key and the current time.
		return $this->generateToken($key, $this->_currentTimeMillis());
	}

	/**
	 * Verify that the given token hashed with the given key is valid.
	 * @param string $token - The token to verify.
	 * @param string $key - The key used to generate the token.
	 * @return bool - True if the token is valid, false otherwise.
	 * @throws SessionException - Thrown when session ID is not available.
	 */
	public function verifyToken(string $token, string $key): bool
	{
		// Getting token generation time.
		$dotpos = strrpos($token, ".");
		$token_time = substr($token, $dotpos);

		// If the given token and generated token are the same, the token is verified.
		return $token == $this->generateToken($key, $token_time);
	}
}