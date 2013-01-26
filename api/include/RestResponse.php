<?php
class RestResponse {
	public $data;
	public $httpCode;

	public function __construct() {
		$this->data = null;
		$this->httpCode = 200;
	}
}
?>