<?php
include_once 'DefaultController.php';
include_once 'connector/ConnectorFactory.php';
include_once 'model/FeedDao.php';
include_once 'model/PostDao.php';
include_once 'model/SubscriptionDao.php';

class SubscriptionController extends DefaultController {

	public function post($request, $response) {

		$userId = $request->user->userId;
		
		// what url do we want to consult ?
		$url = $request->data->url;
		
		// first we check if we know that feed
		$feedDao = new FeedDao();
		$feed = $feedDao->getFeed($url);
		
		if ($feed == null) {
			// nope, we don't know that feed, let's create it
			$connector = ConnectorFactory::initConnector($url);
			$feed = $connector->extractInfos();
			
			$feed = $feedDao->insert($feed);
			
			// if we don't know that feed, we need to insert each post
			$postDao = new PostDao();
			$posts = $connector->extractPosts();
			foreach ($posts as $post) {				
				$post->feedId = $feed->feedId;
				$postDao->insert($post);
			}
		}
		
		// now we have a feed, let's check if that user have already subscribed to that feed
		$subDao = new SubscriptionDao();
		$sub = $subDao->select($userId, $feed->feedId);
		
		if ($sub == null) {
			
			// get title from request, or from feed eitherway
			$title = $this->getParam($request, "title", $feed->title);
			// get categoryId from request, or null. // TODO check if the category is owned by the user
			$categoryId = $this->getParam($request, "categoryId");
			// get priority from request, 10 default
			$priority = $this->getParam($request, "priority", 10);
			
			$sub = (object) Array(
				"categoryId" => $categoryId,
				"userId" => $userId,
				"feedId" => $feed->feedId,
				"title" => $title, 
				"priority" => $priority
			);			
			
			$sub = $subDao->insert($sub);
			$response->httpCode = 201;
		} else {
			$response->httpCode = 200;
		}

		$response->data = $sub;

		return $response;
	}

	public function put($request, $response) {
		$sub = $request->data;

		if (!empty($sub->url) && !empty($sub->priority)) {

			$userId = $request->user->userId;
			$sub->userId = $userId;
			
			if (!isset($sub->categoryId)) {
				$sub->categoryId = null;
			}

			$subDao = new SubscriptionDao();
			$subDao->update($sub);

			$response->data = $sub;
			$response->httpCode = 200;
		} else {
			$response->httpCode = 400;
			error_log("categoryId = $sub->categoryId, url = $sub->url, priority = $sub->priority");
		}

		return $response;
	}

	public function delete($request, $response) {
		$sub = $request->data;
		
		if (!empty($sub->subscriptionId)) {

			$userId = $request->user->userId;
			$sub->userId = $userId;

			$subDao = new SubscriptionDao();
			$subDao->delete($sub);

			$response->httpCode = 200;
		} else {
			$response->httpCode = 400;
			error_log("subscriptionId = $sub->subscriptionId");
		}
		return $response;
	}

}
?>