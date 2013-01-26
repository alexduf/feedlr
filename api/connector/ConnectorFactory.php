<?php

include_once 'DefaultConnector.php';

class ConnectorFactory {
	
	public static function initConnector($url) {
		// TODO : detect which connector we need with a list of domains (youtube, tumblr, vimeo ...)
		return new DefaultConnector($url);
	}
	
}

?>