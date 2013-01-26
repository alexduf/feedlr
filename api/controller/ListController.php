<?php
include_once 'DefaultController.php';
include_once 'model/PostDao.php';

class ListController extends DefaultController {

	public function get(RestRequest $request, RestResponse $response) {
		
		$userId = $request->user->userId;
		$feedId = $this->getParam($request, "feed");
		$categoryId = $this->getParam($request, "category");
		$readed = $this->getParam($request, "readed", 0);
		$page = $this->getParam($request, "page", 1);
		$pageSize = $this->getParam($request, "pageSize", 50);
		
		// if we are asked to show unreaded ones, we want to show readed ones with them
		if ($readed == true) {
			$readed = null;
		}
		

		$postDao = new PostDao();
		$response->data = $postDao->getPosts($userId, $feedId, $categoryId, $readed, $page, $pageSize);
		
		// TODO alex : add favourite support

		return $response;
	}

}
?>