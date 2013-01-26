<?php
include_once 'DefaultController.php';
include_once 'model/CategoryDao.php';

class CategoryController extends DefaultController {

	public function post(RestRequest $request, RestResponse $response) {

		$userId = $request->user->userId;
		$caption = $request->data->caption;

		//error_log("userId = $userId, caption = $caption");

		$categoryDao = new CategoryDao();
		$id = $categoryDao->insert($userId, $caption);

		$data = Array("categoryId" => $id, "caption" => $caption);

		$response->data = $data;
		$response->httpCode = 201;

		return $response;
	}

	public function put(RestRequest $request, RestResponse $response) {

		$category = $request->data;

		if (!empty($category->categoryId) && !empty($category->caption)) {

			$userId = $request->user->userId;
			$category->userId = $userId;

			$categoryDao = new CategoryDao();
			$categoryDao->update($category);

			$response->data = $request->data;
			$response->httpCode = 200;
		} else {
			$response->httpCode = 400;
			error_log("categoryId = $category->categoryId, caption = $category->caption");
		}

		return $response;

	}

	public function delete(RestRequest $request, RestResponse $response) {

		$category = $request->data;

		if (!empty($category->categoryId)) {

			$userId = $request->user->userId;
			$category->userId = $userId;

			$categoryDao = new CategoryDao();
			$categoryDao->delete($category);

			$response->httpCode = 200;
		} else {
			$response->httpCode = 400;
			error_log("categoryId = $category->categoryId, caption = $category->caption");
		}

		return $response;

	}

}
?>