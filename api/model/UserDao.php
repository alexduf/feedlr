<?php

include_once 'DefaultDao.php';

class UserDao extends DefaultDao {
	
	public function getUser($login) {
		$req = 'select
		    user.userId,
		    user.login,
		    user.password,
		    user.mail,
		    user.created,
		    user.admin
		from
		    user
		where
		    user.login = :login';
		
		$pstmt = $this->getDbh()->prepare($req);
		$pstmt->bindValue(":login", $login);
		
		$pstmt->execute();
		
		return $pstmt->fetchObject();
	}
	
}

?>