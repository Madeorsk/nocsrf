<?php

namespace NoCSRF;

use NoCSRF\KeyGenerator\KeyGenerator;
use NoCSRF\KeyGenerator\OpensslKeyGenerator;
use NoCSRF\KeyStorage\KeyStorage;
use NoCSRF\KeyStorage\SessionKeyStorage;
use NoCSRF\TokenManager\HMACTokenManager;
use NoCSRF\TokenManager\TokenManager;

/**
 * NoCSRF main class.
 * @package NoCSRF
 */
class NoCSRF
{
	/**
	 * The key generator.
	 * @var KeyGenerator
	 */
	protected $keyGenerator;
	/**
	 * The key storage.
	 * @var KeyStorage
	 */
	protected $keyStorage;
	/**
	 * The token manager.
	 * @var TokenManager
	 */
	protected $tokenManager;

	/**
	 * NoCSRF constructor.
	 * @param array $config - NoCSRF configuration.
	 */
	public function __construct($config = [])
	{
		// Reading configuration.
		$this->_readConfiguration($config);
	}

	/**
	 * Read the configuration.
	 * @param array $config - Configuration of NoCSRF.
	 */
	protected function _readConfiguration($config = [])
	{
		// Default NoCSRF configuration.
		$defaultConfig = [
			"keyGenerator" => new OpensslKeyGenerator(16),
			"keyStorage" => new SessionKeyStorage(),
			"tokenManager" => new HMACTokenManager(),
		];

		// Loading configuration from the default configuration, overriding it by the given configuration.
		foreach ($defaultConfig as $key => $value)
			// Setting each value to the default value if it does not exists in the given configuration.
			$this->{$key} = empty($config[$key]) ? $value : $config[$key];
	}

	/**
	 * Loaded token key.
	 * @var string
	 */
	protected $_currentKey;
	/**
	 * Try to get key from storage.
	 * If the key does not exist in the storage, create a new one and save it.
	 */
	private function loadCurrentKey()
	{
		// Reading key from the storage.
		$this->_currentKey = $this->keyStorage->read();

		if (empty($this->_currentKey))
		{ // The key does not exist, generating a new key.
			$this->_currentKey = $this->keyGenerator->generate();
			// Saving the generated key.
			$this->keyStorage->save($this->_currentKey);
		}
	}
	/**
	 * Get the loaded token key.
	 * @return string - The token key.
	 */
	public function getKey(): string
	{
		if (empty($this->_currentKey))
			// Current key is not loaded, loading or creating.
			$this->loadCurrentKey();

		return $this->_currentKey; // Returning the current key.
	}

	/**
	 * Generated token.
	 * @var string
	 */
	protected $_currentToken = null;
	/**
	 * Generate the current token.
	 * Can load the key if it was not already loaded.
	 */
	protected function genCurrentToken()
	{
		// Generating a new token.
		$this->_currentToken = $this->tokenManager->newToken($this->getKey());
	}
	/**
	 * Get the generated token.
	 * @return string - The token.
	 */
	public function getToken(): string
	{
		if (empty($_currentToken))
			// Current token is not generated, creating a new one.
			$this->genCurrentToken();

		return $this->_currentToken; // Returning the current token.
	}

	/**
	 * Try to find token in POST data or request headers.
	 * @return string - A found token, empty string if no token found.
	 */
	protected function tryToFindToken(): string
	{
		if (!empty($_POST["_csrfToken"]))
			// A token has been found in the POST data.
			return $_POST["_csrfToken"];

		elseif (!empty($_SERVER["HTTP_X_CSRF_TOKEN"]))
			// A token has been found in the HTTP request headers.
			return $_SERVER["HTTP_X_CSRF_TOKEN"];

		return ""; // Default token: empty string.
	}
	/**
	 * Verify token.
	 * @param string|null $token - The token to verify. If NULL, NoCSRF will try to find the CSRF token in `_csrfToken` POST value or in `X-CSRF-Token` request header. NULL by default.
	 * @return bool
	 */
	public function verify(?string $token = null): bool
	{ // Verifying token in the token manager using the loaded key.
		return $this->tokenManager->verifyToken(
			!empty($token) ? $token : $this->tryToFindToken(), // If the token has not been provided in parameter, trying to find it.
			$this->getKey()); // Getting key.
	}
}
