<?php


namespace NoCSRF;


use NoCSRF\Exceptions\SessionException;
use NoCSRF\Exceptions\SessionNotActiveException;

/**
 * Session manager class.
 * @package NoCSRF
 */
class Session
{
	/**
	 * Create a new session manager.
	 */
	public function __construct()
	{
		// Starting session if it is not active.
		if (session_status() != PHP_SESSION_ACTIVE)
			session_start();
	}

	/**
	 * Check if session is active or not.
	 * @return bool - True if the session is active, false otherwise.
	 */
	public function isActive(): bool
	{
		return session_status() == PHP_SESSION_ACTIVE;
	}
	/**
	 * Check that the session is active.
	 * @throws SessionNotActiveException - Thrown when the session is not active.
	 */
	protected function _checkActive()
	{
		if (!$this->isActive())
			throw new SessionNotActiveException();
	}

	/**
	 * Get session ID from the currently started session.
	 * @return string - The session ID.
	 * @throws SessionException - Thrown when session ID is not available.
	 */
	public function getSessionID(): string
	{
		// Getting session ID from the current session.
		$session_id = session_id();

		if (empty($session_id))
			// Not session ID available, throwing an exception.
			throw new SessionException("session ID is not available; session may not have been started successfully.");

		return $session_id; // Returning session ID.
	}

	/**
	 * Set a specified value for a specified session variable.
	 * @param string $varname - Name of the variable to set.
	 * @param mixed $value - Value to set.
	 * @throws SessionNotActiveException - Thrown when the session is not active.
	 */
	public function write(string $varname, $value)
	{
		$this->_checkActive(); // Check that the session is active.

		// Write specified value in the session.
		$_SESSION[$varname] = $value;
	}

	/**
	 * Try to read a value from the current session.
	 * @param string $varname - Variable to read.
	 * @param mixed|null $default - Default value. NULL by default (default value of default value, yes yes).
	 * @return mixed|null - The value of the specified variable if defined, or the default value if it did not exist.
	 * @throws SessionNotActiveException - Thrown when the session is not active.
	 */
	public function read(string $varname, $default = null)
	{
		$this->_checkActive(); // Check that the session is active.

		if (isset($_SESSION[$varname]))
			// Value exists, returning it.
			return $_SESSION[$varname];
		else // Value does not exists, returning the specified default value.
			return $default;
	}
}