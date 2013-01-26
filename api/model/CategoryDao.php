<?php

include_once 'DefaultDao.php';

class CategoryDao extends DefaultDao {

	public function insert($userId, $caption) {
		$req = 'insert into
			category
			(userId, caption)
		values
			(:userId, :caption)';


		$pstmt = $this->getDbh()->prepare($req);
		$pstmt->bindValue(":userId", $userId);
		$pstmt->bindValue(":caption", $caption);

		if (!$pstmt->execute()) {
			throw new Exception("Error during SQL execution");
		}
		
		if ($pstmt->rowCount() != 1) {
			throw new Exception("ERROR ! lines affected : $pstmt->rowCount() but should be 1");
		}
		
		$result = $this->getDbh()->lastInsertId();
		
		$pstmt->closeCursor();

		return $result;
	}

	public function update($category) {
		$req = 'update category
			set caption = :caption
		where
			userId = :userId
			and categoryId = :categoryId';


		$pstmt = $this->getDbh()->prepare($req);
		$pstmt->bindValue(":userId", $category->userId);
		$pstmt->bindValue(":caption", $category->caption);
		$pstmt->bindValue(":categoryId", $category->categoryId);

		if (!$pstmt->execute()) {
			throw new Exception("Error during SQL execution");
		}
		
		if ($pstmt->rowCount() != 1) {
			throw new Exception("ERROR ! lines affected : $pstmt->rowCount() but should be 1");
		}
		
		$result = $this->getDbh()->lastInsertId();
		
		$pstmt->closeCursor();

		return $result;
	}

	public function delete($category) {
		$req = 'delete from category
		where
			userId = :userId
			and categoryId = :categoryId';


		$pstmt = $this->getDbh()->prepare($req);
		$pstmt->bindValue(":userId", $category->userId);
		$pstmt->bindValue(":categoryId", $category->categoryId);

		if (!$pstmt->execute()) {
			throw new Exception("Error during SQL execution");
		}
		
		if ($pstmt->rowCount() != 1) {
			throw new Exception("ERROR ! lines affected : $pstmt->rowCount() but should be 1");
		}
		
		$result = $this->getDbh()->lastInsertId();
		
		$pstmt->closeCursor();

		return $result;
	}

}

?>