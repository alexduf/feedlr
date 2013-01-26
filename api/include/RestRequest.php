<?php
class RestRequest {
	public $requestVars;
	public $data;
	public $httpAccept;
	public $method;
	public $resource;
	public $user;

	public function __construct() {
		$this->requestVars = array();
		$this->data = '';
		$this->httpAccept = (strpos($_SERVER['HTTP_ACCEPT'], 'json')) ? 'json' : 'xml';
		$this->method = 'get';
		$this->resource = null;
		$this->user = null;
	}
}
?>