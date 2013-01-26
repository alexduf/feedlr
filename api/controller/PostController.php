<?php
include_once 'DefaultController.php';
include_once 'model/PostDao.php';

class PostController extends DefaultController {

	public function get(RestRequest $request, RestResponse $response) {

		$userId = $request->user->userId;
		$postId = $this->getParam($request, "postId");

		$postDao = new PostDao();
		$response->data = $postDao->getPost($userId, $postId);

		return $response;
	}

	public function put(RestRequest $request, RestResponse $response) {

		$post=$request->data;
		$post->userId =  $request->user->userId;

		$postDao = new PostDao();
		$postDao->updatePost($post);
		$response->data = $postDao->getPost($post->userId, $post->postId);
		
		//TODO alex : add here support for favourites

		return $response;
	}

}
?>