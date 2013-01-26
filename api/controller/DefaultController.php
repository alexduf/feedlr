<?php

class DefaultController {

	public function head(RestRequest $request, RestResponse $response) {
		$response->httpCode = 405;
		return $response;
	}

	public function get(RestRequest $request, RestResponse $response) {
		$response->httpCode = 405;
		return $response;
	}

	public function post(RestRequest $request, RestResponse $response) {
		$response->httpCode = 405;
		return $response;
	}

	public function put(RestRequest $request, RestResponse $response) {
		$response->httpCode = 405;
		return $response;
	}

	public function delete(RestRequest $request, RestResponse $response) {
		$response->httpCode = 405;
		return $response;
	}


	/*
	 * Utils methods
	 */

	protected function getParam($request, $param, $default = null) {
		$ret = $default;
		if (isset($request->requestVars[$param])) {
			$ret = $request->requestVars[$param];
		}
		return $ret;
	}

}

?>