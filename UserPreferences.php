<?php

if (!defined('MEDIAWIKI')) die();


final class UserPreferencesSetup {

	static $dir;
	
	public static function onBeforePageDisplay(OutputPage &$out, Skin &$skin ) {

		global $wgAuthOcdla_ReplaceLogin;

		if($wgAuthOcdla_ReplaceLogin) $out->addModules('ext.userPreferences');
		
		return true;
	}
	
	public static function SetupUserPreferences() {

		global $wgResourceModules, $wgAutoloadClasses, $wgHooks;
	
		self::$dir = dirname(__FILE__);
	
	
	
		$wgResourceModules['ext.userPreferences'] = array(
			'scripts' => array( 'userPreferences.js' ),
			'position' => 'bottom',
			'remoteBasePath' => '/extensions/UserPreferences',
			'localBasePath' => '/extensions/UserPreferences'
		);

	
		$wgHooks['BeforePageDisplay'][] = 'UserPreferencesSetup::onBeforePageDisplay';
		$wgAutoloadClasses['UserPreferencesPersonalLinks'] = self::$dir . '/classes/UserPreferencesPersonalLinks.php';
		$wgAutoloadClasses['AuthOcdlaPersonalLinksManager'] = self::$dir . '/classes/AuthOcdlaPersonalLinksManager.php';
		$wgAutoloadClasses['AuthOcdlaPersonalLinksAnonymous'] = self::$dir . '/classes/AuthOcdlaPersonalLinksAnonymous.php';
		$wgAutoloadClasses['AuthOcdlaPersonalLinksAuthenticated'] = self::$dir . '/classes/AuthOcdlaPersonalLinksAuthenticated.php';
	}
}