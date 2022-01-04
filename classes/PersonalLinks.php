<?php

class PersonalLinks {
	
	protected $manager;
	
	protected $data = array();
	
	protected $attributes = array();
	
	
	public function __construct(PersonalLinksManager $manager) {

		$this->manager = $manager;		
	}
	
	public function getAttribute($attrName) {

		return $this->attributes[$attrName];
	}
	

	public function addAttribute($attrName,$attrValue){

		$this->attributes[$attrName]=$attrValue;
	}
	
	
	public function getData() {

		return $this->data;
	}
	
	private function setLinkDefaults(&$link) {

		if(!isset($link['accesskey'])) $link['accesskey'] = '';

		if(!isset($link['title'])) $link['title'] = $link['name'];

		if(!isset($link['id'])) $link['id'] = '';

		else $link['id'] = " id='{$link['id']}'";
	}
	
	public function formatHtml($link) {

		$this->setLinkDefaults($link);

		return "<li{$link['id']}><a accesskey='{$link['accesskey']}' title='{$link['title']} [{$link['accesskey']}]' href='{$link['href']}'>{$link['name']}</a></li>";
	}

	public function getAsHtml() {
		
		$links = array_map(function($link){return $this->formatHtml($link);}, $this->data);

		return "<ul>" .implode($links) ."</ul>";
	}
	
	public function getLinks() {

		global $wgPersonalLinks_LogoutURL, $wgScriptPath;

		$keys = func_get_args();
		// $keys = is_array($key)?$keys:array($keys);
		$links = array(
			'pt-login' 	=> array(
				'title' 		=> 'You are encouraged to log in; however, it is not mandatory [ctrl-option-o]',
				'href' 			=> $this->manager->getLoginUrl(),
				'name' 			=> 'Log in'
			),
			'pt-userpage' => array(
				'accesskey' => '.',
				'title' => 'Your user page',
				'href' => "$wgScriptPath/User:".$this->manager->mwUser->mName,
				'name' => $this->manager->mwUser->mName
			),
			'pt-mytalk' => array(
				'accesskey' => 'n',
				'title' => 'Your talk page',
				'class' => 'new',
				'href' => "$wgScriptPath/User_talk:".$this->manager->mwUser->mName,
				'name' => 'My talk',
			),
			'pt-preferences' => array(
				'title' => 'Your preferences',
				'href' => "$wgScriptPath/Special:Preferences",
				'name' => 'My preferences'
			),
			'pt-wikilog-blog' => array(
				'title' => 'Add a Blog Post',
				'href' => "$wgScriptPath/index.php?title=Blog:Main&action=wikilog",
				'name' => 'Add Blog Post'
			),
			'pt-wikilog-case-review' => array(
				'title' => 'Add a Case Review',
				'href' => "$wgScriptPath/index.php?title=Blog:Case_Reviews&action=wikilog",
				'name' => 'Add Case Review'
			),
			'pt-emailer-review' => array(
				'title' => 'Review new posts.',
				'href' => "$wgScriptPath/ocdlaemail/publish.php",
				'name' => 'Emailer Review'
			),
			'pt-emailer-action' => array(
				'title' => 'Notify subscribers of new posts.',
				'href' => "$wgScriptPath/ocdlaemail/sendmail.php",
				'name' => 'Send Email'
			),
			'pt-legcomm' => array(
				'title' => 'Legislative Affiars Committee',
				'href' => "$wgScriptPath/Legcomm:Home",
				'name' => 'Legislative Affairs'
			),
			'pt-manage-groups' => array(
				'title' => 'Manage Groups',
				'href' => "$wgScriptPath/Special:UserRights/".$this->manager->mwUser->mName,
				'name' => 'Manage Groups'
			),
			'pt-watchlist' => array(
				'accesskey' => 'l',
				'title' => 'A list of pages you are monitoring for changes',
				'href' => "$wgScriptPath/Special:Watchlist",
				'name' => 'My watchlist'
			),
			'pt-mycontris' => array(
				'accesskey' => 'y',
				'title' => 'A list of your contributions',
				'href' => "$wgScriptPath/Special:Contributions/".$this->manager->mwUser->mName,
				'name' => 'My contributions'
			),
			'pt-logout' => array(
				'title' => 'Log out',
				'href' => $this->manager->getLogoutUrl(),
				'name' => 'Log out'
			)
		);
		
		$keys = array_flip($keys);

		return array_intersect_key($links,$keys);
	}
}