<?php

if (!defined('MEDIAWIKI')) die();


final class PersonalLinksHooks {

	static $dir;
	
	public static function onBeforePageDisplay(OutputPage &$out, Skin &$skin ) {

		global $wgPersonalLinks_ReplaceLogin;

		if($wgPersonalLinks_ReplaceLogin) $out->addModules('ext.personalLinks');
		
		return true;
	}
	
	public static function setup() {

		global $wgResourceModules, $wgAutoloadClasses, $wgHooks;
	
		self::$dir = dirname(__FILE__);
	
	
	
		$wgResourceModules['ext.personalLinks'] = array(
			'scripts' => array( 'personalLinks.js' ),
			'position' => 'bottom',
			'remoteBasePath' => '/extensions/PersonalLinks',
			'localBasePath' => '/extensions/PersonalLinks'
		);

	
		$wgHooks['BeforePageDisplay'][] = 'PersonalLinksHooks::onBeforePageDisplay';
		$wgAutoloadClasses['PersonalLinks'] = self::$dir . '/classes/PersonalLinks.php';
		$wgAutoloadClasses['PersonalLinksManager'] = self::$dir . '/classes/PersonalLinksManager.php';
		$wgAutoloadClasses['PersonalLinksAnonymous'] = self::$dir . '/classes/PersonalLinksAnonymous.php';
		$wgAutoloadClasses['PersonalLinksAuthenticated'] = self::$dir . '/classes/PersonalLinksAuthenticated.php';
	}
}