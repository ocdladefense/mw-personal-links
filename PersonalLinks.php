<?php

if (!defined("MEDIAWIKI")) die();


final class PersonalLinksHooks {

	static $dir;
	
	public static function onBeforePageDisplay(OutputPage &$out, Skin &$skin ) {

		$out->addModules("ext.personalLinks");
		
		return true;
	}

	public static function onPersonalUrls( array &$personal_urls, \Title $title ) {

        global $wgScriptPath, $wgUser, $wgPersonalLinks_LoginURL;

        if(self::isLoggedIn($wgUser)){

           unset($personal_urls["login"]);

        } else {
            
            $personal_urls["login"]["text"] = "OCDLA login";
            $personal_urls["login"]["href"] = "$wgScriptPath/$wgPersonalLinks_LoginURL";
            $personal_urls["login"]["active"] = true;

            unset($personal_urls["anonuserpage"]);
            unset($personal_urls["anontalk"]);
            unset($personal_urls["anonlogin"]);
        }
	
	    return true;
    }

	    
    public static function isLoggedIn($user) {

        return $user->getId() != 0;
    }
	
	public static function setup() {

		global $wgResourceModules, $wgAutoloadClasses, $wgHooks, $wgScriptPath;
	
		self::$dir = dirname(__FILE__);
	
		$wgResourceModules["ext.personalLinks"] = array(
			"scripts" => array( "personalLinks.js" ),
			"position" => "bottom",
			"remoteBasePath" => "/extensions/PersonalLinks",
			"localBasePath" => "extensions/PersonalLinks"
		);

	
		$wgHooks["BeforePageDisplay"][] = "PersonalLinksHooks::onBeforePageDisplay";
		$wgHooks["PersonalUrls"][] = "OAuthHooks::onPersonalUrls";

		$wgAutoloadClasses["PersonalLinks"] = self::$dir . "/classes/PersonalLinks.php";
		$wgAutoloadClasses["PersonalLinksManager"] = self::$dir . "/classes/PersonalLinksManager.php";
		$wgAutoloadClasses["PersonalLinksAnonymous"] = self::$dir . "/classes/PersonalLinksAnonymous.php";
		$wgAutoloadClasses["PersonalLinksAuthenticated"] = self::$dir . "/classes/PersonalLinksAuthenticated.php";
	}
}