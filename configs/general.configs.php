<?php
	/**
	 *	General Reminder Call configurations
	**/
	final class generalRcConfigs{

		//	API endpoint URL
		public static $endpoint_url = 'https://api.remindercall.net/v3/dispatch.xml';
		//	Standard curl headers defined by Reminder Call
		//	Documentation: https://secure.remindercall.com/developers/api/xml.html
		public static $standard_curl_headers = array(
			'Host:' => 'api.remindercall.net',
			'Accept:' => '*/*',
			'Content-Type:' => 'application/xml; charset=UTF-8',
		);
	}