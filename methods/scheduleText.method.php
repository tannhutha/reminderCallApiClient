<?php
	/**
	 *
	**/
	class scheduleText extends baseMethod{

		//	Creates a new instance of this class
		public function __construct(){
			//	Calls the parent constructor first
			parent::__construct();
			//	Set the XML schema for this class
			$xml = '<request>
						<key>%%key%%</key>
						<text action="create">
							<id>%%id%%</id>
							<delivery>%%delivery_datetime%%</delivery>
							<number>%%phone_number%%</number>
							<message>%%message%%</message>
							<name>%%callee_name%%</name>
							<grouping>%%group_name%%</grouping>
						</text>
					</request>';
			$this->setXMLSchema($xml);
		}
	}