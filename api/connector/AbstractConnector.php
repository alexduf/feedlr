<?php
abstract class AbstractConnector {
	
	private $url;
	
	public function __construct($url) {
		$this->url = $url;
	}
	
	public function getUrl() {
		return $this->url;
	}
	
	public abstract function extractInfos();

	public abstract function extractPosts();
	
}

?>