<?php

include_once 'DefaultDao.php';

class PostDao extends DefaultDao {

	public function getPosts($userId, $feedId, $categoryId, $readed, $page, $pageSize) {
		$req = 'select
			    userPost.userPostId,
			    post.feedId,
			    post.externalId,
			    post.title,
			    post.link,
			    post.mobileLink,
			    post.updated,
			    post.summary,
			    post.content,
			    userPost.readed,
			    userPost.favourited
			from
			    post,
			    userPost';

		if ($categoryId != null) {
			$req .= ', subscription';
		}

		$req .= ' where
			    userPost.userId = :userId
			    and userPost.postId = post.postId';

		if (isset($readed)) {
			$req .= ' and userPost.readed = :readed';
		}

		if ($feedId != null) {
			$req .= ' and post.feedId = :feedId';
		}

		if ($categoryId != null) {
			$req .= ' and subscription.feedId = post.feedId and subscription.categoryId = :categoryId';
		}

		$req .= ' order by post.updated desc';
		$req .= ' limit :offset, :pageSize';

		$pstmt = $this->getDbh()->prepare($req);
		$pstmt->bindValue(":userId", $userId);

		if (isset($readed)) {
			$pstmt->bindValue(":readed", $readed, PDO::PARAM_BOOL);
		}

		if ($feedId != null) {
			$pstmt->bindValue(":feedId", $feedId);
		}

		if ($categoryId != null) {
			$pstmt->bindValue(":categoryId", $categoryId);
		}

		$pstmt->bindValue(":offset", max($page - 1, 0) * $pageSize, PDO::PARAM_INT);
		$pstmt->bindValue(":pageSize", $pageSize, PDO::PARAM_INT);

		if (!$pstmt->execute()) {
			error_log($pstmt->errorCode());	
			$errInf = $pstmt->errorInfo();
			error_log($errInf[0]);
			error_log($errInf[1]);
			error_log($errInf[2]);
			throw new Exception("Error during SQL execution");
		}

		$result = $pstmt->fetchAll(PDO::FETCH_CLASS);

		$pstmt->closeCursor();

		return $result;
	}

	public function getPost($userId, $postId) {
		$req = 'select
			    userPost.userPostId,
			    post.feedId,
			    post.externalId,
			    post.title,
			    post.link,
			    post.mobileLink,
			    post.updated,
			    post.summary,
			    post.content,
			    userPost.readed,
			    userPost.favourited
			from
			    post,
			    userPost
			where
				userPost.userPostId = :postId
			    and userPost.postId = post.postId
			    and userPost.userId = :userId';

		$pstmt = $this->getDbh()->prepare($req);
		$pstmt->bindValue(":userId", $userId);
		$pstmt->bindValue(":postId", $postId);

		if (!$pstmt->execute()) {
			error_log($pstmt->errorCode());
			$errInf = $pstmt->errorInfo();
			error_log($errInf[0]);
			error_log($errInf[1]);
			error_log($errInf[2]);
			throw new Exception("Error during SQL execution");
		}

		$result = $pstmt->fetchObject();

		$pstmt->closeCursor();

		return $result;
	}
	
	public function updatePost($post) {
		$req = 'update userPost
			set readed = :readed,
				favourited = :favourited
			where
				userPost.userPostId = :postId
			    and userPost.userId = :userId';

		$pstmt = $this->getDbh()->prepare($req);
		$pstmt->bindValue(":userId", $post->userId);
		$pstmt->bindValue(":postId", $post->postId);
		$pstmt->bindValue(":readed", $post->readed);
		$pstmt->bindValue(":favourited", $post->favourited);

		if (!$pstmt->execute()) {
			error_log($pstmt->errorCode());
			$errInf = $pstmt->errorInfo();
			error_log($errInf[0]);
			error_log($errInf[1]);
			error_log($errInf[2]);
			throw new Exception("Error during SQL execution");
		}

		$pstmt->closeCursor();
	}
	
	public function insert($post) {
		$req = 'insert into post
				(feedId,
				externalId,
				title,
				link,
				mobileLink,
				updated,
				summary,
				content)
			values
				(:feedId,
				:externalId,
				:title,
				:link,
				:mobileLink,
				:updated,
				:summary,
				:content)';
		
		$pstmt = $this->getDbh()->prepare($req);
		
		$pstmt->bindValue(":feedId", $post->feedId);
		$pstmt->bindValue(":externalId", $post->externalId);
		$pstmt->bindValue(":title", $post->title);
		$pstmt->bindValue(":link", $post->link);
		$pstmt->bindValue(":mobileLink", $post->mobileLink);
		$pstmt->bindValue(":updated", $post->updated);
		$pstmt->bindValue(":summary", $post->summary);
		$pstmt->bindValue(":content", $post->content);

		if (!$pstmt->execute()) {
			throw new Exception("Error during SQL execution");
		}
		
		if ($pstmt->rowCount() != 1) {
			throw new Exception("ERROR ! lines affected : $pstmt->rowCount() but should be 1");
		}
		
		$post->postId = $this->getDbh()->lastInsertId();
		
		$pstmt->closeCursor();

		return $post;
	}

}

?>