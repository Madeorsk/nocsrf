<?php


namespace NoCSRF\KeyStorage;


/**
 * Abstract key storage.
 * @package NoCSRF\KeyStorage
 */
abstract class KeyStorage
{
	/**
	 * Save the given key to the storage.
	 * @param string $key - Key to save.
	 * @return bool - True if the save was successful, false otherwise.
	 */
	public abstract function save(string $key): bool;

	/**
	 * Read the key from storage.
	 * @return string - The read key.
	 */
	public abstract function read(): string;
}