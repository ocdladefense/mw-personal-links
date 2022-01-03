<?php

class AuthOcdlaPersonalLinksAnonymous extends PersonalLinks
{
	
	public function __construct(AuthOcdlaPersonalLinksManager $manager) {

		parent::__construct($manager);

		$this->data = $this->getLinks('pt-login');
		
		$this->addAttribute('logged_in',false);
	}

}