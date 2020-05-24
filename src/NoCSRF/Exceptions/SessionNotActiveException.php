<?php


namespace NoCSRF\Exceptions;


use Throwable;

class SessionNotActiveException extends SessionException
{
	public function __construct($code = 0, Throwable $previous = null)
	{
		parent::__construct("session not active.", $code, $previous);
	}
}