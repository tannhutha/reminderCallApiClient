<?php
	/**
	 *
	**/
	class getKey extends baseMethod{

		//	Creates a new instance of this class
		public function __construct(){
			//	Calls the parent constructor first
			parent::__construct();
			//	Set the XML schema for this class
			$xml = '<?xml version="1.0" encoding="UTF-8"?>
					<request>
						<key action="get">
							<username>%%username%%</username>
							<password>%%password%%</password>
						</key>
					</request>';
			$this->setXMLSchema($xml);
		}
	}