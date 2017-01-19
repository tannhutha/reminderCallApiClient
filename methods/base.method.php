<?php
	/**
	 *
	**/
	class baseMethod{

		//	XML payload
		protected $_xml_schema = false;
		protected $_xml_data = array();

		//	Responses
		//	Raw response returned from by the API
		protected $_raw_response = false;
		//	Decoded response
		protected $_response = false;

		//	Error
		protected $_error = false;

		//	Creates a new instance of tihs class
		protected function __construct(){}

		//	Set the XML schema
		//	*	$xml 	- 	(required | string) XML format string
		protected function setXMLSchema($xml){
			$this->_xml_schema = $xml;
		}

		//	Set the XML data array 
		//	*	$payload 	-	(required | array) request data to be sent
		//						within the XML schema.
		public function setPayload($payload=array()){
			//	Must be in array format!
			if(is_array($payload)){
				//	Include the API key into the payload
				$payload['key'] = runtimeRcConfigs::$key;
				$this->_xml_data = $payload;
			}
		}

		//	Send the request to Reminder Call's API
		//	Require PHP curlBuddy.php
		public function send(){
			try{
				//	Prepare the xml payload
				$xml_payload = $this->prepareXmlPayload();
				//	Create a new curlBuddy instance
				$curl_buddy = new curlBuddy();
				//	Start a new curl POST to the API endpoint
				$post_h = $curl_buddy->newCurl()->post(generalRcConfigs::$endpoint_url);
				//	Set the xml payload
				$post_h->setData($xml_payload);
				//	Set the standard Reminder Call HTTP headers
				$post_h->setHeaders(generalRcConfigs::$standard_curl_headers);
				//	Send the POST request and check for errors
				$post_h->send();
				if($post_h->hasError()){
					throw new Exception($post_h->errorMessage(), 1);
				}else{
					$this->_raw_response = $post_h->response();
					//	Parse the XML response into a PHP array
					$this->_response = $this->xmlToArray($post_h->response());
					//	Check for errors
					if(isset($this->_response['errors']['error'])){
						if(isset($this->_response['errors']['error']['message'])){
							throw new Exception('reminderCallApiClient-Error[' . $this->_response['errors']['error']['@attributes']['code'] . ']: ' . $this->_response['errors']['error']['message'], 1);
						}elseif(isset($this->_response['errors']['error'][0]['message'])){
							throw new Exception('reminderCallApiClient-Error[' . $this->_response['errors']['error'][0]['@attributes']['code'] . ']: ' . $this->_response['errors']['error'][0]['message'], 1);
						}else{
							throw new Exception('reminderCallApiClient-Error[]: Unknown error.', 1);
						}
					}
				}
				return true;
			}catch(Exception $e){
				$this->_error = $e->getMessage();
			}
			return false;
		}

		//	Convert the xml schema into an actual xml payload by replacing
		//	all of its placeholders with data within the xml data array.
		protected function prepareXmlPayload(){
			//	Combine the schema and the data array
			$xml_schema = $this->_xml_schema;
			$xml_data = $this->_xml_data;
			if(!empty($xml_schema) && is_array($xml_data) && !empty($xml_data)){
				//	Grab all of the %%placeholders%% from the schema
				preg_match_all('/(?:<(\w+)>)(%%(\w+)%%)(?:<\/\1>)/', $xml_schema, $matches);
				$placeholders = $matches[2];
				$data_keys = $matches[3]; 
				foreach(array_keys($placeholders) as $i){
					$value = '';
					if(isset($xml_data[$data_keys[$i]])){
						$value = $xml_data[$data_keys[$i]];
					}
					$xml_schema = str_replace($placeholders[$i], $value, $xml_schema);
				}
			}
			return $xml_schema;
		}

		//	Convert an XML string into a PHP array
		//	$xml 		-	(required | string) xml string
		//	$as_object	-	(optional | boolean) Set to true to return
		//					as an object instead of an array.
		protected function xmlToArray($xml, $as_object=false){
			$doc = simplexml_load_string($xml);
			if(!$doc){
				throw new Exception(__FUNCTION__ . '-Error[]: Invalid XML string cannot be parsed into array.', 1);
			}
			return json_decode(json_encode($doc), !$as_object);
		}

		//	Return the response
		public function response(){
			return $this->_response;
		}

		//	Return the raw response
		public function responseRaw(){
			return $this->_raw_response;
		}

		//	Return the error message
		public function errorMessage(){
			return $this->_error;
		}

		//	Check if there are any errors
		public function hasError(){
			if($this->_error !== false){
				return true;
			}
			return $this->_error;
		}
	}