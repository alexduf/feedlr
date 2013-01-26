<?php

include_once 'AbstractConnector.php';
include_once 'lib/SimplePie.compiled.php';

class DefaultConnector extends AbstractConnector {

	private $simplePie;

	public function __construct($url) {
		parent::__construct($url);


		$this->simplePie = new SimplePie();
		$this->simplePie->set_feed_url($this->getUrl());
		$this->simplePie->enable_cache(false);
		$this->simplePie->init();
	}

	public function extractInfos() {


		$feed = (object) Array("title" => $this->simplePie->get_title(),
			"subtitle" => $this->simplePie->get_description(),
			"url" => $this->simplePie->get_permalink(),
			"link" => $this->simplePie->subscribe_url(),
			"type" => "feed");

		return $feed;
	}

	public function extractPosts() {
		$posts = Array();

			
		for ($i = 0; $i < $this->simplePie->get_item_quantity(); $i++) {
			
			$item = $this->simplePie->get_item($i);
			
			// TODO : transform where possible to mobile link ?
			$posts[$i] = (object) Array("externalId" => $item->get_id(),
				"title" => $item->get_title(),
				"link" => $item->get_permalink(),
				"mobileLink" => $item->get_permalink(),
				"updated" => $item->get_gmdate("c"),
				"summary" => $item->get_description(),
				"content" => $item->get_content());
		}
		
		return $posts;
	}
}
?>