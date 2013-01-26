<?php


include_once 'DefaultDao.php';

class FeedDao extends DefaultDao {

	function getFeed($url) {
		$req = 'select
		    feed.feedId,
		    feed.title,
		    feed.subtitle,
		    feed.link,
		    feed.updated,
		    feed.type
		from
		    feed
		where
		    feed.link = :link';
		
		$pstmt = $this->getDbh()->prepare($req);
		$pstmt->bindValue(":link", $url);
		
		$pstmt->execute();
		
		return $pstmt->fetchObject();
	}
	
	function insert($feed) {
		$req = 'insert into
			feed
			(title, subtitle, url, link, updated, type)
		values
			(:title, :subtitle, :url, :link, now(), :type)';


		$pstmt = $this->getDbh()->prepare($req);
		
		$pstmt->bindValue(":title", $feed->title);
		$pstmt->bindValue(":subtitle", $feed->subtitle);
		$pstmt->bindValue(":url", $feed->url);
		$pstmt->bindValue(":link", $feed->link);
		$pstmt->bindValue(":type", $feed->type);

		if (!$pstmt->execute()) {
			throw new Exception("Error during SQL execution");
		}
		
		if ($pstmt->rowCount() != 1) {
			throw new Exception("ERROR ! lines affected : $pstmt->rowCount() but should be 1");
		}
		
		$feed->feedId = $this->getDbh()->lastInsertId();
		
		$pstmt->closeCursor();

		return $feed;
	}

}

?>