<?php

/**
 * stolen / forked / inspired from this blog post : http://www.gen-x-design.com/archives/create-a-rest-api-with-php/
 * thanks Ian ;)
 * @author alex
 */

include 'RestRequest.php';
include 'RestResponse.php';

class RestHandler {
	
	public static function processRequest()	{
		// get our verb
		$requestMethod = strtolower($_SERVER['REQUEST_METHOD']);
		$request = new RestRequest();
		// we'll store our data here
		$data = array();

		switch ($requestMethod)
		{
			// gets are easy...
			case 'get':
				$data = $_GET;
				break;
				// so are posts
			case 'post':
				$data = $_POST;
				break;
				// here's the tricky bit...
			case 'put':
				// basically, we read a string from PHP's special input location,
				// and then parse it out into an array via parse_str... per the PHP docs:
				// Parses str  as if it were the query string passed via a URL and sets
				// variables in the current scope.
				parse_str(file_get_contents('php://input'), $put_vars);
				$data = $put_vars;
				break;
			case 'delete':
				parse_str(file_get_contents('php://input'), $put_vars);
				$data = $put_vars;
				break;
		}

		// store the method
		$request->method = $requestMethod;

		// store the resource
		if (isset($_GET["resource"])) {
			$request->resource = $_GET["resource"];
		}


		// set the raw data, so we can access it if needed (there may be
		// other pieces to your requests)
		$request->requestVars = $data;

		if(isset($data['data']))
		{
			// translate the JSON to an Object for use however you want
			$request->data = json_decode($data['data']);
		}
		return $request;
	}

	public static function sendResponse(RestResponse $response)	{
		// set the status
		header('HTTP/1.1 ' . $response->httpCode  . ' ' . RestHandler::getStatusCodeMessage($response->httpCode));
		// set the content type
		header('Content-type: application/json');

		// pages with body are easy
		if($response->data != null)
		{
			// send the body
			echo json_encode($response->data);
		}
		exit;
	}

	public static function sendError($httpCode)	{
		$response = new RestResponse();
		$response->data = Array("code" => $httpCode, "message" =>RestHandler::getStatusCodeMessage($httpCode));
		$response->httpCode = $httpCode;
		
		RestHandler::sendResponse($response);
	}

	public static function getStatusCodeMessage($status) {
		// these could be stored in a .ini file and loaded
		// via parse_ini_file()... however, this will suffice
		// for an example
		$codes = Array(
		100 => 'Continue',
		101 => 'Switching Protocols',
		200 => 'OK',
		201 => 'Created',
		202 => 'Accepted',
		203 => 'Non-Authoritative Information',
		204 => 'No Content',
		205 => 'Reset Content',
		206 => 'Partial Content',
		300 => 'Multiple Choices',
		301 => 'Moved Permanently',
		302 => 'Found',
		303 => 'See Other',
		304 => 'Not Modified',
		305 => 'Use Proxy',
		306 => '(Unused)',
		307 => 'Temporary Redirect',
		400 => 'Bad Request',
		401 => 'Unauthorized',
		402 => 'Payment Required',
		403 => 'Forbidden',
		404 => 'Not Found',
		405 => 'Method Not Allowed',
		406 => 'Not Acceptable',
		407 => 'Proxy Authentication Required',
		408 => 'Request Timeout',
		409 => 'Conflict',
		410 => 'Gone',
		411 => 'Length Required',
		412 => 'Precondition Failed',
		413 => 'Request Entity Too Large',
		414 => 'Request-URI Too Long',
		415 => 'Unsupported Media Type',
		416 => 'Requested Range Not Satisfiable',
		417 => 'Expectation Failed',
		500 => 'Internal Server Error',
		501 => 'Not Implemented',
		502 => 'Bad Gateway',
		503 => 'Service Unavailable',
		504 => 'Gateway Timeout',
		505 => 'HTTP Version Not Supported'
		);

		return (isset($codes[$status])) ? $codes[$status] : '';
	}
}




?>