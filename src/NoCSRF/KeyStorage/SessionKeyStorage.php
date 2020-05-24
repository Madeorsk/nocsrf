<?php


namespace NoCSRF\KeyStorage;


use NoCSRF\Exceptions\SessionNotActiveException;
use NoCSRF\Session;

/**
 * Session key storage.
 * @package NoCSRF\KeyStorage
 */
class SessionKeyStorage extends KeyStorage
{
	/**
	 * Variable name in session storage.
	 * @var string
	 */
	protected $sessionVariableName;
	/**
	 * Session manager.
	 * @var Session
	 */
	protected $session;

	/**
	 * Create a new session key storage.
	 * Simply store the key in the session.
	 * @param string $session_variable_name - Variable name in session storage.
	 */
	public function __construct(string $session_variable_name = "__NoCSRF__key")
	{
		$this->sessionVariableName = $session_variable_name;
		$this->session = new Session();
	}

	/**
	 * @inheritDoc
	 */
	public function save(string $key): bool
	{
		try
		{ // Writing key to session.
			$this->session->write($this->sessionVariableName, $key);
			return true; // Value have been written successfully, returning true.
		}
		catch (SessionNotActiveException $e)
		{ // If the session is not active, returning false as we cannot save the key.
			return false;
		}
	}

	/**
	 * @inheritDoc
	 * @throws SessionNotActiveException
	 */
	public function read(): ?string
	{ // Reading key from session.
		return $this->session->read($this->sessionVariableName);
	}
}