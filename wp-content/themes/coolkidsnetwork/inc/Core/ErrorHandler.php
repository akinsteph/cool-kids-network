<?php

/**
 * ErrorHandler.php
 *
 * This file contains the ErrorHandler class, which handles error and exception management for the Cool Kids Network.
 *
 * @package CoolKidsNetwork
 *
 */

namespace CoolKidsNetwork\Core;

use CoolKidsNetwork\Traits\Singleton;

/**
 * Class ErrorHandler.
 *
 * Handles error and exception handling for the Cool Kids Network.
 */

class ErrorHandler {
	use Singleton;

	/**
	 * Constructor for the ErrorHandler class.
	 *
	 * Sets up error and exception handlers.
	 */
	protected function __construct() {
		set_error_handler(array($this, 'handle_error'));
		set_exception_handler(array($this, 'handle_exception'));
	}


	/**
	 * Handles PHP errors.
	 *
	 * This method is called when a PHP error occurs. It logs the error and
	 * optionally displays it if WP_DEBUG is enabled.
	 *
	 * @param int    $errno   The level of the error raised.
	 * @param string $errstr  The error message.
	 * @param string $errfile The filename that the error was raised in.
	 * @param int    $errline The line number the error was raised at.
	 *
	 * @return bool True if the error was handled, false to use PHP's internal error handler.
	 */
	public function handle_error($errno, $errstr, $errfile, $errline) {
		if (!(error_reporting() & $errno)) {
			return false;
		}

		$error_message = "Error [$errno] $errstr on line $errline in file $errfile";
		$this->log_error($error_message);

		if (defined('WP_DEBUG') && WP_DEBUG) {
			echo "<p>$error_message</p>";
		}

		return true;
	}

	/**
	 * Handles uncaught exceptions.
	 *
	 * This method is called when an uncaught exception occurs. It logs the exception
	 * and optionally displays it if WP_DEBUG is enabled.
	 *
	 * @param \Exception $exception The uncaught exception.
	 */
	public function handle_exception($exception) {
		$error_message = "Uncaught Exception: " . $exception->getMessage() . " in file " . $exception->getFile() . " on line " . $exception->getLine();
		$this->log_error($error_message);

		if (defined('WP_DEBUG') && WP_DEBUG) {
			echo "<p>$error_message</p>";
		}
	}

	/**
	 * Logs an error message.
	 *
	 * This method is used internally to log error messages using PHP's error_log function.
	 *
	 * @param string $message The error message to log.
	 */
	private function log_error($message) {
		error_log($message);
	}
}
