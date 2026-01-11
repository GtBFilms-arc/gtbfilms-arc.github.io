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

/**
 * Requires get_all_dirs() function.
 */
require_once('functions.php');

// Module Global Variables
/**
 * Translation array
 * 
 * Contains English string as key and as value selected language translated
 * string
 * 
 * @name $transarray
 * @global array $transarray
 */ 

$transarray=array();
$tlang; //Destination Language
$tdomain; //TextDomain
$tlocaledir;//Locale dir

/**
 * Set text domain to use
 *  
 * @param string $domain set this domain as text domain 
 * @global string $tdomain
 */

function textdomain_y($domain) {
    global $tdomain;
    $tdomain=$domain;
}

/**
 * Removes quotes from a string
 * 
 * Finds first " and final " returns string within this two chars.
 * @param string msg string we want to remove quotes
 */

function remove_quotes($msg) {
    $msg=strstr($msg,"\"");
    $msg=substr($msg,1,strlen($msg)-2);   
    return($msg);
}

/**
 * bind text domain
 *
 * You have to do this 
 *
 * @param stringt $domain translation domain
 * @param string  $pathtolocaledir path where the gettext directory structure is
 *
 */

function bindtextdomain_y($domain,$pathtolocaledir) {
    // load global which are needed !!
    
    global $transarray, $tdomain,$tlocaledir,$LANG;
    $tdomain=$domain;
    $tlocaledir=$pathtolocaledir;
    //echo "bindtextdomain($LANG $domain $pathtolocaledir)<br />";//debug line
    $transfile = $pathtolocaledir ."/" . $LANG . "/LC_MESSAGES/".$tdomain.".po";
    if(file_exists($transfile)) {
	//echo "Fichero $transfile existe";
	if (!($fd = fopen($transfile,"r"))) {
	    return(false);
	}
	
	while (!feof($fd)) {
	    $multiline=false; 
	    $line = trim(fgets($fd,1024));
	    if(substr($line,0,5)=="msgid") {
		//echo "(msgid a): $line <br>"; // debug line --
		$msgid=remove_quotes($line);		
		if (strlen($msgid)==0) { //Multiline
		    $multiline=true;
		  
		    $line = trim(fgets($fd,1024));
		    while (substr($line,0,6)!="msgstr") {
			$msgid.=remove_quotes($line);
			$line = trim(fgets($fd,1024));
		    }
		}
		//Now search for msgstr;
		$line = trim(fgets($fd,1024));
		if ($multiline){
		    $msgstr='';
		    while (strlen($line)>0) {
			$msgstr.=remove_quotes($line);
			$line = trim(fgets($fd,1024));
		    }		  		    
		}
		else { //one line
		    $msgstr=remove_quotes($line);
		}
		$msgid=str_replace("\\\"","\"",$msgid );
		$msgid=str_replace("\\n","\n",$msgid );
		$msgstr=str_replace("\\n","\n",$msgstr);
		$msgstr=str_replace("\\\"","\"",$msgstr );
		$temp=array($msgid=> $msgstr);	
		$transarray=array_merge($transarray,$temp);	      
		
	    } //end if substr(msgid)
	}//end while !feof
	fclose($fd);
	//echo "<pre>" . print_r($transarray) . "</pre>"; // debug line
    }
}

/**
 * Translate string
 *
 * @param string $transstr We want to translate this string.
 * 
 * @return string Translated string if translation is available
 */

function _y($transstr) {
  global $transarray;
  // check if is avaible else do no translation
  if($transarray[$transstr]) return $transarray[$transstr];
  else return $transstr;
}

/**
 * Include Translated File
 *
 * This function is for replacing the inclusion of long strings in .PO files
 * If you want to include a translated html file use this function.
 * If translated file does not exist original file must be in:
 *  $tlocaledir/C/LC_MESSAGES/$filename 
 *  For example:  ./locale/C/LC_MESSAGES/faq.html
 * 
 * @param string $filename we want to include this filename in the correct lang.
 */

function include_y($filename) {
  global $LANG,$tlocaledir;
  
  $transfile = $tlocaledir ."/" . $LANG . "/LC_MESSAGES/".$filename;
  if (file_exists($transfile)) {
    include($transfile);
  }
  else {
    $transfile = $tlocaledir ."/" ."C" . "/LC_MESSAGES/".$filename;
    if (!file_exists($transfile)) return($false);
    include($transfile);
    
  }
  return(true);
}

/**
 * use_accept_language() 
 * 
 * Searches in accept-language header sent by the browser. If one of the 
 * languages is available on localedir, then it returns the string 
 * corresponding to that language. For example:
 * 
 * _SERVER["HTTP_ACCEPT_LANGUAGE"]es-es,en-us;q=0.5 
 * 
 * And these are the available languages:
 * 
 * br  cn   cz  es  id  nl  ru  ca  de  fr  gl  it  sv 
 * 
 * It would return 'es'
 * 
 * @param string $localedir directory where are stored the translation tables.
 * @global $_SERVER needed to get the string of HTTP_ACCEPT_LANGUAGE 
 * @return mixed the corresponding string to an available language or false
 * 
 */


function use_accept_language($localedir) {
 global $_SERVER;

    $accept_arr= explode(';', $_SERVER['HTTP_ACCEPT_LANGUAGE']);
    if ($accept_arr[0] == '') return(false);
    $accept_arr= explode(',', $accept_arr[0]);
    //get directory list.
    
    if (!($all_langs=get_all_dirs($localedir))) return(false);
   
    //echo "<pre>Accept:";print_r($accept_arr);echo "</pre>";
    foreach ($accept_arr as $lang) {
	$la=substr($lang,0,2); //expample:  $lang='es_ES';  $la='es';
     	
	if (in_array($lang,$all_langs)) return($lang);
	if (in_array($la,$all_langs)) return($la);   
    }
    return(false);
}


function getCharset() {
    global $transarray;
    
    //echo $transarray[''];
    $default = 'iso-8859-1';
    if (!preg_match("/charset=([a-zA-Z0-9\-]+)/",$transarray[''],$match)) {
	return ($default); //Default Latin1	
    }
    //echo "<br />match = $match[1]";
    if ($match[1]=="CHARSET") {
	return $default;	
    }
   // echo "charset: $match[1]";
   return ($match[1]);
}


?>
