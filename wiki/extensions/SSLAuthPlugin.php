<?php
/**
 */
# Version 1.1.4 (Works out of box with MW 1.15.1)
#
# Authentication Plugin for Apache2 mod_ssl
# Derived from AuthPlugin.php and
# http://meta.wikimedia.org/wiki/Shibboleth_Authentication
#
# Much of the commenting comes straight from AuthPlugin.php
#
# Portions Copyright 2006, 2007 Martin Johnson
# Portions Copyright 2006, 2007 Regents of the University of California
# Portions Copyright 2007 Steven Langenaken
# Portions Copyright 2007 Krzysztof Kozlowski
# compatibility fixes for 1.11 / 1.12  by datenritter.de
# compatibility fixes for 1.13  by Philippe Marty
# compatibility fixes for 1.15  by Christophe Jacquet
# Released under the GNU General Public License

/**
 * Changes between 1.1.4 and 1.1.3
 * == 1.15 compatibility fixes
 *
 * Changes between 1.1.3 and 1.1.2
 * == 1.13 compatibility fixes
 *
 * Changes between 1.1.2 and 1.1
 * == 1.11 / 1.12 compatibility fixes
 *
 * Changes between 1.1 and 1.0.2:
 * == 1.10 compatibility fixes
 *
 * Changes between 1.0.2 and 1.0.1:
 * = Merge changes from Shibboleth Authentication: (By DJC)
 * == More 1.9 compatibility fixes and less ugly code
 *
 * Changes between 1.0.1 and 1.0:
 * = Merge changes from Shibboleth Authentication: (By DJC)
 * == Compatible with MW 1.9+ again (By DJC)
 * == Minor fix in loginform handling (By Steven Langenaken)
 *
 * Documentation at http://www.mediawiki.org/wiki/Extension:SSL_authentication
 * require_once('AuthPlugin.php');
 */
 
class SSLAuthPlugin extends AuthPlugin {
        /**
         * See AuthPlugin.php for specific information
         */
        function userExists( $username ) {
                return true;
        }
 
        /**
         * See AuthPlugin.php for specific information
         */
        function authenticate( $username, $password ) {
                global $ssl_UN;
 
                if($username == $ssl_UN)
                        return true;
                else
                        return false;
        }
 
        /**
         * See AuthPlugin.php for specific information
         */
        function modifyUITemplate( &$template ) {
                $template->set( 'usedomain', false );
        }
 
        /**
         * See AuthPlugin.php for specific information
         */
        function setDomain( $domain ) {
                $this->domain = $domain;
        }
 
        /**
         * See AuthPlugin.php for specific information
         */
        function validDomain( $domain ) {
                return true;
        }
 
        /**
         * See AuthPlugin.php for specific information
         */
        function updateUser( &$user ) {
                global $ssl_map_info;
                global $ssl_email;
                global $ssl_RN;
 
                //Map extra info or not?
                if($ssl_map_info == true) {
                        //If Email, set info in MW
                        if($ssl_email != null)
                        $user->setEmail($ssl_email);
 
                        //If realName, set info in MW
                        if($ssl_RN != null)
                        $user->setRealName($ssl_RN);
 
                        $user->saveSettings();
                }
 
 
                //For security, set password to a non-existant hash.
                $user->load();
                if($user->mPassword != "nologin") {
                        $user->mPassword = "nologin";
                        $user->saveSettings();
                }
 
                return true;
        }
 
        /**
         * See AuthPlugin.php for specific information
         */
        function autoCreate() {
                return true;
        }
 
        /**
         * See AuthPlugin.php for specific information
         */
        function allowPasswordChange() {
                return false;
        }
 
        /**
         * See AuthPlugin.php for specific information
         */
        function setPassword( $password ) {
                return false;
        }
 
        /**
         * See AuthPlugin.php for specific information
         */
        function updateExternalDB( $user ) {
                //Not really, but wiki thinks we did...
                return true;
        }
 
        /**
         * See AuthPlugin.php for specific information
         */
        function canCreateAccounts() {
                return false;
        }
 
        /**
         * See AuthPlugin.php for specific information
         */
        function addUser( $user, $password, $email='', $realname='' ) {
                return false;
        }
 
        /**
         * See AuthPlugin.php for specific information
         */
        function strict() {
                return false;
        }
	function strictUserAuth( $username ) {
		return false;
	}
 
        /**
         * See AuthPlugin.php for specific information
         */
        function initUser( &$user, $autocreate ) {
                //Update MW with new user information
                $this->updateUser($user);
        }
 
        /**
         * See AuthPlugin.php for specific information
         */
        function getCanonicalName( $username ) {
                return $username;
        }
}
 
/**
 * End of AuthPlugin Code, beginning of hook code and auth functions
 */
 
/**
 * Some extension information init
 */
$wgExtensionFunctions[] = 'SSLAuthSetup';
$wgExtensionCredits['other'][] = array(
        'name' => 'SSL Authentication',
        'version' => '1.1.4',
        'author' => 'Martin Johnson and others',
        'description' => 'Automatic login with certificates using Apache2 mod_ssl clientside',
        'url' => 'http://www.mediawiki.org/wiki/Extension:SSL_authentication'
);
 
/**
 * Setup extensionfunctions
 */
function GetSSLAuthHook() {
	global $wgVersion;
	if ($wgVersion >= "1.13") {
		return 'UserLoadFromSession';
	} else {
		return 'AutoAuthenticate';
	}
}
 
function SSLAuthSetup() {
        global $ssl_UN, $wgHooks, $wgAuth;
 
        if($ssl_UN != null) {
                $wgHooks[GetSSLAuthHook()][] = 'SSLAuth'.GetSSLAuthHook(); /* Hook for magical authN */
                $wgHooks['PersonalUrls'][] = 'NoLogout'; /* Disallow logout link */
                $wgAuth = new SSLAuthPlugin();
        }
}
 
/* No logout link in MW */
function NoLogout(&$personal_urls, $title) {
        $personal_urls['logout'] = null;
        return true;
}
 
/* Tries to be magical about when to log in users and when not to. */
function SSLAuthAutoAuthenticate(&$user) {
	SSLAuthUserLoadFromSession($user, true);
}
 
function SSLAuthUserLoadFromSession($user, &$result) {
        global $ssl_UN, $wgUser, $wgContLang, $wgHooks;
 
        //ShibAuthPlugin added this function call here also, and indeed it made things work ;)
        KillAA();
 
        //Give us a user, see if we're around
        //$tmpuser = User::LoadFromSession(); // Pre MediaWiki 1.10
        $tmpuser = User::newFromSession(); // For MediaWiki 1.10.0 and up
 
        //They already with us?  If so, quit this function.
        if($tmpuser->isLoggedIn())
        return true;
 
        //Is the user already in the database?
        $tmpuser = User::newFromName($ssl_UN);
 
        //If exists, log them in
        if($tmpuser->getID() != 0) {
                $wgUser = &$tmpuser;
                $wgUser->setupSession();
                $wgUser->setCookies();
                return true;
        }
 
        //ShibAuthPlugin added this function call here also, and indeed it made things work ;)
        BringBackAA();
 
        //Okay, kick this up a notch then...
        $wgUser = &$tmpuser;
        $wgUser->setName($wgContLang->ucfirst($ssl_UN));
 
        /*
         * Some magic that Shibboleth Authentication does and I just copy
         */
        require_once('specials/SpecialUserlogin.php');
 
        //This section contains a silly hack for MW
        global $wgLang, $wgContLang, $wgRequest;
        if(!isset($wgLang)) {
                $wgLang = $wgContLang;
                $wgLangUnset = true;
        }
 
        //Temporarily kill The AutoAuth Hook to prevent recursion
        KillAA();
 
        //This creates our form that'll do black magic
        $lf = new LoginForm($wgRequest);
 
        //Place the hook back (Not strictly necessarily MW Ver >= 1.9)
        BringBackAA();
 
        //And now we clean up our hack
        if($wgLangUnset == true) {
                unset($wgLang);
                unset($wgLangUnset);
        }
 
        //Now we _do_ the black magic
        $lf->mRemember = false;
        $lf->initUser($wgUser, true);
 
        //Finish it off
        $wgUser->saveSettings();
        $wgUser->setupSession();
        $wgUser->setCookies();
	return true; // needed in MediaWiki 1.15
}
 
/* Temporarily kill The AutoAuth Hook to prevent recursion */
function KillAA()
{
	global $wgHooks;
#	global $wgAuth; //looks unuseful here, but it appeared in ShibAuthPlugin... let _real_ developpers decide ;)
 
        foreach ($wgHooks[GetSSLAuthHook()] as $key => $value) {
                if($value == 'SSLAuth'.GetSSLAuthHook())
                $wgHooks[GetSSLAuthHook()][$key] = 'BringBackAA';
        }
}
 
/* Puts the auto-auth hook back into the hooks array */
function BringBackAA() {
        global $wgHooks;
#	global $wgAuth; //looks unuseful here, but it appeared in ShibAuthPlugin... let _real_ developpers decide ;)

        foreach ($wgHooks[GetSSLAuthHook()] as $key => $value) {
                if($value == 'BringBackAA')
                $wgHooks[GetSSLAuthHook()][$key] = 'SSLAuth'.GetSSLAuthHook();
        }
        return true;
}