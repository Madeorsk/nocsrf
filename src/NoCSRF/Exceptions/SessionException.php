<?php


namespace NoCSRF\Exceptions;


use Throwable;

class SessionException extends \Exception
{
	public function __construct($message = "", $code = 0, Throwable $previous = null)
	{
		parent::__construct("SessionException: {$message}", $code, $previous);
	}
}