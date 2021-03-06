<?php


class PersonalLinksManager {

	// const LOD_SKIN = '/var/www/lodtest/skins/lod/templates';
	/**
	 * var personalLinks
	 *
	 * Formatted html for the personal links.  This should be a 
	 * list of links like "logout", "perferences," "watchlist," etc.
	 */
	private $personalLinks;
	
	/**
	 * var requestUrl
	 *
	 * Use the requested Url to make sure personal links have
	 * a reference to it.  This makes redirecting the user back
	 * to their intended destination easier.
	 */
	private $requestUrl;
	
	/**
	 * var mwUser
	 *
	 * The MediaWiki user associated with these personal links.
	 * This user should be loaded either from session data
	 * or directory from a mwUserId.
	 */
	public $mwUser;
	
	/**
	 * var logoutUrl
	 *
	 * This is the uri of the resource that should
	 * destroy this user's session.
	 */
	private $logoutUrl;
	
	/**
	 * var loginUrl
	 *
	 * This is the uri of the resource that should
	 * establish a user's session.
	 */
	private $loginUrl;
	
	/**
	 * var legComm
	 *
	 * Whether the user associated with these
	 * personal links is part of the Legislative Committee.
	 */
	private $legComm = false;
	
	/**
	 * var sysOp
	 *
	 * Whether the user associated with these
	 * personal links is a member of the Sys Op group.
	 */
	private $sysOp = false;
	
	/**
	 * var useSalesforce
	 *
	 * Salesforce login url.
	 *
	 * We can replace the "login" link and point it to a different 
	 * login service.  Set this to true to use the Salesforce IdP login.
	 */
	private $useSalesforce = false;
	
	
	public function __construct() {

		global $cookiePrefix, $wgSiteDirectory, $wgPersonalLinks_LoginURL, $wgPersonalLinks_LogoutURL;
		  
		$this->loginUrl = $wgPersonalLinks_LoginURL;
		$this->logoutUrl = $wgPersonalLinks_LogoutURL;
		$this->requestUrl = $_GET['requestUrl'];

		$this->mwUser = User::newFromSession();	
		$this->mwUser->load();

		$this->parseMwUserGroups();
		$this->loadTemplates();
	}

	
	public function getLoginUrl() {

		global $wgScriptPath;

		return $wgScriptPath . "/index.php" . $this->loginUrl . '?retURL=' . urlencode($this->requestUrl);
	}
	
	public function isLegComm() {

		return $this->legComm;
	}
	
	public function isSysOp() {

		return $this->sysOp;
	}
	
	public function getLogoutUrl() {

		global $wgScriptPath;

		return $wgScriptPath . "/index.php" . $this->logoutUrl . '?retURL='.urlencode($this->requestUrl);
	}

	public function listUserGroups() {

    	return $this->user->mGroups;
	}
	
	private function loadTemplates() {

		if ($this->mwUser->mId !== 0) $this->personalLinks = new PersonalLinksAuthenticated($this);

		else $this->personalLinks = new PersonalLinksAnonymous($this);
	}
	
	
	public function parseMwUserGroups() {

		$this->sysOp = in_array('sysop', $this->mwUser->mGroups) ? true : false;
		$this->legComm = in_array('legcomm', $this->mwUser->mGroups) ? true : false;
		$this->mName = $this->mwUser->mName;
		$this->mId = $this->mwUser->getId();
	}
	
	public function getLinks() {

		return array(
			'logged_in' => $this->personalLinks->getAttribute('logged_in'),
			'markup' => $this->personalLinks->getAsHtml(),
		);
	}
	
	public function toJson() {

		if($this->useSalesforce) $this->personalLinks->addLink('salesforce','href');

		return json_encode($this->getLinks());
	}

	public function __toString() {

		return $this->personalLinks;
	}
}