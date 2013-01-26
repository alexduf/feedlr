<?php


include_once 'DefaultDao.php';

class SubscriptionDao extends DefaultDao {
	
	function select($userId, $feedId) {
		$req = 'select 
			subscriptionId,
			categoryId,
			userId,
			feedId,
			title,
			priority
		from
			subscription
		where
			userId = :userId
			and feedId = :feedId';
		
		$pstmt = $this->getDbh()->prepare($req);
		$pstmt->bindValue(":userId", $userId);
		$pstmt->bindValue(":feedId", $feedId);
		
		$pstmt->execute();
		
		return $pstmt->fetchObject();
	}

	function insert($sub) {
		$req = 'insert into
			subscription
			(categoryId,
			userId,
			feedId,
			title,
			priority)
		values
			(:categoryId,
			:userId,
			:feedId,
			:title,
			:priority)';
		
		$pstmt = $this->getDbh()->prepare($req);
		
		$pstmt->bindValue(":categoryId", $sub->categoryId);
		$pstmt->bindValue(":userId", $sub->userId);
		$pstmt->bindValue(":feedId", $sub->feedId);
		$pstmt->bindValue(":title", $sub->title);
		$pstmt->bindValue(":priority", $sub->priority);

		if (!$pstmt->execute()) {
			throw new Exception("Error during SQL execution");
		}
		
		if ($pstmt->rowCount() != 1) {
			throw new Exception("ERROR ! lines affected : $pstmt->rowCount() but should be 1");
		}
		
		$sub->subscriptionId = $this->getDbh()->lastInsertId();
		
		$pstmt->closeCursor();

		return $sub;
	}

	public function update($sub) {
		$req = 'update subscription
			set categoryId = :categoryId,
			title = :title,
			priority = :priority
		where
			userId = :userId
			and subscriptionId = :subscriptionId';


		$pstmt = $this->getDbh()->prepare($req);
		$pstmt->bindValue(":categoryId", $sub->categoryId);
		$pstmt->bindValue(":title", $sub->title);
		$pstmt->bindValue(":priority", $sub->priority);
		$pstmt->bindValue(":userId", $sub->userId);
		$pstmt->bindValue(":subscriptionId", $sub->subscriptionId);

		if (!$pstmt->execute()) {
			throw new Exception("Error during SQL execution");
		}
		
		if ($pstmt->rowCount() != 1) {
			throw new Exception("ERROR ! lines affected : $pstmt->rowCount() but should be 1");
		}
		
		$pstmt->closeCursor();

		return $sub;
	}

	public function delete($sub) {
		$req = 'delete from subscription
		where
			userId = :userId
			and subscriptionId = :subscriptionId';


		$pstmt = $this->getDbh()->prepare($req);
		$pstmt->bindValue(":userId", $sub->userId);
		$pstmt->bindValue(":subscriptionId", $sub->subscriptionId);

		if (!$pstmt->execute()) {
			throw new Exception("Error during SQL execution");
		}
		
		if ($pstmt->rowCount() != 1) {
			throw new Exception("ERROR ! lines affected : $pstmt->rowCount() but should be 1");
		}
		
		$pstmt->closeCursor();

		return $sub;
	}

}

?>