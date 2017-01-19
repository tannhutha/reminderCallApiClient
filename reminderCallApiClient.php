<?php
	/**
	 *	Constants 
	**/
	define('__REMINDER_CALL_API_CLIENT_DIR__',  realpath(dirname(__FILE__)));

	/**
	 *	Includes
	**/
	require_once(__REMINDER_CALL_API_CLIENT_DIR__ . '/configs/general.configs.php');
	require_once(__REMINDER_CALL_API_CLIENT_DIR__ . '/configs/runtime.configs.php');
	require_once(__REMINDER_CALL_API_CLIENT_DIR__ . '/methods/base.method.php');

	/**
	 *	Client integration to the Reminder Call API. Reminder Call is an
	 *	automated Text/SMS, Email, Call Appointment Reminders service.
	 *
	 *	PHP version 5
	 *
	 *	@author     Tan Ha <tan.ha@devcodestudio.com>
	 *	@copyright	
	 *	@license	
	 *	@version 	
	 *	@link 		https://secure.remindercall.com/developers/api/xml.html
	**/
	final class reminderCallApiClient{

		//	Creates a new instance of this class
		public function __construct(){
			//	Make a call to retrieve the API key
			$this->getApiKey();
		}

		//	Magic call function to direct non existing methods to a
		//	method file within the methods directory. 
		//	format: /path/to/methods/methodName.method.php
		//			$obj = new methodName($args)
		public function __call($name, $arguments){
			$obj = false;
			$methods_path = __REMINDER_CALL_API_CLIENT_DIR__ . '/methods';
			if(is_dir($methods_path)){
				$method_file = $methods_path . "/{$name}.method.php";
				if(file_exists($method_file)){
					require_once($method_file);
					$method = "{$name}";
					if(class_exists($method)){
						$r = new ReflectionClass($method);
						$obj = $r->newInstanceArgs($arguments);
					}
				}
			}
			if($obj == false){
				trigger_error('Call to undefined method ' . __CLASS__ . '::' . $name . '()', E_USER_ERROR);
			}
			return $obj;
		}

		//	Retrieve the Reminder Call API key
		private function getApiKey(){
			//	Start a new request to get the key
			$rc_request = $this->newRequest()->getKey();
			//	Set the xml payload
			$rc_request->setPayload(array(
				'username' => runtimeRcConfigs::$username,
				'password' => runtimeRcConfigs::$password
			));
			//	Send the request and trigger error
			$rc_request->send();
			if($rc_request->hasError()){
				trigger_error($rc_request->errorMessage(), E_USER_ERROR);
			}
			//	Set the the API key
			$response = $rc_request->response();
			if(!isset($response['key']) || empty($response['key'])){
				trigger_error(__CLASS__ . '-Error[]: Unable to retrieve API key.');
			}
			runtimeRcConfigs::$key = $response['key'];
		}

		//	Start a new request
		public function newRequest(){
			return $this;
		}
	}