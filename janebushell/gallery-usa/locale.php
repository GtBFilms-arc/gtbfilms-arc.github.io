<?php
/**
 * YaPiG Internationalization module
 * 
 *  Original Idea: http://phplasmonges.sf.net
 *  MODIFIED FOR YAPIG Integration
 *  Read locale/GETTEXT.txt for more information about how to get .po files
 *  and how to translate it into your language. 
 *  There is more documentation about this subject in doc/translations.html
 *
 * @package i18n
 */

require_once('config.php');
require_once('locale_func.php');
require_once('functions.php');
require_once('global.php');

//Set Language variable.
//Lang is set on config.php
// $LANG="en"; "es"...
//Bind domain

//If $LANG == 'auto' this means we have to try to
// get it from _SERVER["HTTP_ACCEPT_LANGUAGE"]

$localedir = './locale/';

if ($LANG=='auto') {
    if (($LANG=use_accept_language($localedir))===false)
	$LANG=$DEFAULT_LANG;
}

$domain = 'yapig';
bindtextdomain_y($domain,$localedir);
textdomain_y($domain);
//Get language charset
$_YAPIG_CHARSET= getCharset();
$_YAPIG_LANG = $LANG;
?>
