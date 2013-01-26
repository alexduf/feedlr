<?php

include_once 'include/RestHandler.php';
include_once '../config.php';
include_once 'model/UserDao.php';

$resources = Array("category" => "CategoryController", "sub" => "SubscriptionController", "post" => "PostController", "list" => "ListController");

function checkResource($request, $resources) {
	// the resource must be valued
	if (!isset($request->resource) || $request->resource == '') {
		RestHandler::sendError(400);
	}

	// and must be correct
	if (!isset($resources[$request->resource])) {
		RestHandler::sendError(404);
	}
}

function getController($request, $resources) {
	// get the complete path
	$realPath = realpath(dirname(__FILE__));
	// add our resource name
	$resourcePath = $realPath . "/controller/" . $resources[$request->resource] . ".php";
	// check if readable
	if (!is_readable($resourcePath)) {
		RestHandler::sendError(500);
	}
	// load it !
	include $resourcePath;
	// and instanciate it
	$controllerClass = $resources[$request->resource];
	$controller = new $controllerClass();
	return $controller;
}

function checkAuth() {
	// first, we check if the request is authentified
	if (!isset($_SERVER['PHP_AUTH_USER'])) {
		RestHandler::sendError(401);
	}
	// get the user from database
	$userDao = new UserDao();
	$user = $userDao->getUser($_SERVER['PHP_AUTH_USER']);
	if (!isset($user) || !$user) {
		RestHandler::sendError(401);
	}
	$password = hash("sha256", $_SERVER['PHP_AUTH_PW'].HASH_SALT);
	if ($password != $user->password) {
		//error_log("hash1=$password hash2=$user->password");
		RestHandler::sendError(401);
	}
	
	return $user;
}

try {

	// check auth
	$user = checkAuth();
	
	// process request data
	$request = RestHandler::processRequest();
	
	// save the user on the request
	$request->user = $user;

	// check if resource is OK
	checkResource($request, $resources);

	// now, get the controller
	$controller = getController($request, $resources);

	// create a response
	$response = new RestResponse();


	// we can let the controller process the request
	switch ($request->method) {
		case "get":
			$controller->get($request, $response);
			break;
		case "post":
			$controller->post($request, $response);
			break;
		case "put":
			$controller->put($request, $response);
			break;
		case "delete":
			$controller->delete($request, $response);
			break;
		case "head":
			$controller->head($request, $response);
			break;
	}
	
} catch (Exception $e) {
	error_log($e->getMessage());
	error_log($e->getTraceAsString());
	RestHandler::sendError(500);
}

// and send the response
RestHandler::sendResponse($response);

?>