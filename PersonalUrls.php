<?php

if (!defined('MEDIAWIKI')) die();


final class PersonalUrls {

	static $dir;
	
	public static function onBeforePageDisplay(OutputPage &$out, Skin &$skin ) {

		global $wgAuthOcdla_ReplaceLogin;

		if($wgAuthOcdla_ReplaceLogin) $out->addModules('ext.personalUrls');
		
		return true;
	}
	
	public static function setup() {

		global $wgResourceModules, $wgAutoloadClasses, $wgHooks;
	
		self::$dir = dirname(__FILE__);
	
	
	
		$wgResourceModules['ext.personalUrls'] = array(
			'scripts' => array( 'personalUrls.js' ),
			'position' => 'bottom',
			'remoteBasePath' => '/extensions/PersonalUrls',
			'localBasePath' => '/extensions/PersonalUrls'
		);

	
		$wgHooks['BeforePageDisplay'][] = 'PersonalUrls::onBeforePageDisplay';
		$wgAutoloadClasses['PersonalLinks'] = self::$dir . '/classes/PersonalLinks.php';
		$wgAutoloadClasses['AuthOcdlaPersonalLinksManager'] = self::$dir . '/classes/AuthOcdlaPersonalLinksManager.php';
		$wgAutoloadClasses['AuthOcdlaPersonalLinksAnonymous'] = self::$dir . '/classes/AuthOcdlaPersonalLinksAnonymous.php';
		$wgAutoloadClasses['AuthOcdlaPersonalLinksAuthenticated'] = self::$dir . '/classes/AuthOcdlaPersonalLinksAuthenticated.php';
	}
}