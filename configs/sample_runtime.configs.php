<?php
	/**
	 *	Require curlBuddy.php
	 *	Set the inclusion path to your curlBuddy.php file.
	 *	Replace "/path/to/curlBuddy" with the actual path.
	*/
	require_once('/path/to/curlBuddy/curlBuddy.php');

	/**
	 *	Client specific Reminder Call configurations
	**/
	final class runtimeRcConfigs{

		//	Authentications
		//	$username is the same login username/email used to log into remindercall.com
		//	$password is the same password & username combination used to log into remindercall.com
		public static $username = 'yourEmailAddress@domain.com';
		public static $password = 'yourSuperSecretPassword';
		//	API key will be retrieved automatically during runtime
		public static $key = '';
	}