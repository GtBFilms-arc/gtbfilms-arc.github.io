<?php
	// Simple PHP Blog is released under the GNU Public License.
	//
	// You are free to use and modify Simple PHP Blog. Changes 
	// should be uploaded to http://sourceforge.net/projects/sphpblog/
	// or emailed to apalmo <at> bigevilbrain <dot> com
	
	
	// Last version and update information.
	//
	global $sb_info;
	$sb_info[ 'version' ] = "0.5.1";
	$sb_info[ 'last_update' ] = '9/23/07';	
	
	// Error reporting should be set to 0 in production environments.
	//
	error_reporting( E_ALL ^ E_NOTICE );
	// error_reporting( 0 );
	
	//Remove timeout limit
	@set_time_limit( 0 );
	
	// Store "time" for benchmarking.
	//
	function getmicrotime() { 
		if ( version_compare( phpversion(), '5.0.0' ) == -1 ) {
			list($usec, $sec) = explode(' ', microtime()); 
			return ((float)$usec + (float)$sec); 
		} else {
			return( microtime( true ) );
		}
	}

	global $page_timestamp;
	$page_timestamp = getmicrotime();
	
	// Legacy support functions
	// PHP4 < 4.3.0
	if (!function_exists("ob_get_clean")) { 
		function ob_get_clean() { 
		$ob_contents = ob_get_contents(); 
		ob_end_clean(); 
		return $ob_contents; 
		} 
	} 
	
	if (!function_exists('str_word_count')) {
		function str_word_count($str,$n = "0"){ 
			$m=strlen($str)/2;
			$a=1;
			while ($a<$m) {
				$str=str_replace("Ê "," ",$str);
				$a++;
			}
			$b = explode(" ", $str);
			$i = 0;
			foreach ($b as $v) { 
				$i++;
			}
			if ($n==1) {
				return $b;
			} else {
				return $i;
			}
		}
	}
	
	// BASE URL
	define('BASEURL', '');
	
	// ROOT DIRECTORY
	define('ROOT_DIR', '');
	
	// FOLDER LOCATIONS
	define('CONTENT_DIR',	ROOT_DIR.'content/');
	define('IMAGES_DIR',	ROOT_DIR.'images/');
	define('TEMPLATE_DIR',	ROOT_DIR.'templates/');
	define('CONFIG_DIR',	ROOT_DIR.'config/');
	define('CACHE_DIR',		CONFIG_DIR.'cache/');
	define('SCRIPTS_DIR',	ROOT_DIR.'scripts/');
	define('CLASSES_DIR',	SCRIPTS_DIR.'classes/');
	
	// SESSION LOCATION
	$sessionpath = session_save_path();
	if (strpos($sessionpath, ";") !== FALSE) {
		$sessionpath = substr($sessionpath, strpos ($sessionpath, ";")+1); // '5;/tmp'
	}
	define('SESSION_SAVE_PATH', $sessionpath); // Default is '/tmp'
	
	// Load all the other functions.
	
	require_once(SCRIPTS_DIR.'sb_fileio.php');
	require_once(SCRIPTS_DIR.'sb_config.php');
	require_once(SCRIPTS_DIR.'sb_login.php');
	require_once(SCRIPTS_DIR.'sb_theme.php');
	require_once(SCRIPTS_DIR.'sb_formatting.php');
	require_once(SCRIPTS_DIR.'sb_emoticons.php');
	require_once(SCRIPTS_DIR.'sb_date.php');
	require_once(SCRIPTS_DIR.'sb_communicate.php');
	require_once(SCRIPTS_DIR.'sb_comments.php');
	require_once(SCRIPTS_DIR.'sb_static.php');
	require_once(SCRIPTS_DIR.'sb_utility.php');
	require_once(SCRIPTS_DIR.'sb_menu.php');
	require_once(SCRIPTS_DIR.'sb_search.php');
	require_once(SCRIPTS_DIR.'sb_entry.php');
	require_once(SCRIPTS_DIR.'sb_image.php');
	require_once(SCRIPTS_DIR.'sb_display.php');
	require_once(SCRIPTS_DIR.'sb_color.php'); // These functions don't get used
	require_once(SCRIPTS_DIR.'sb_trackback.php');
	require_once(SCRIPTS_DIR.'sb_feed.php');
	require_once(SCRIPTS_DIR.'sb_categories.php');
	require_once(SCRIPTS_DIR.'sb_forms.php');
	require_once(SCRIPTS_DIR.'sb_texteditor.php');
	require_once(SCRIPTS_DIR.'sb_counter.php');
	require_once(SCRIPTS_DIR.'sb_blacklist.php');
	
	require_once(SCRIPTS_DIR.'sb_sidebar.php');
	
	require_once(CLASSES_DIR.'fileio.php');
	require_once(CLASSES_DIR.'arrays.php');
	
	// require_once(CLASSES_DIR.'login.php');
	// require_once(CLASSES_DIR.'template.php');
	// require_once(CLASSES_DIR.'utility.php');
	// require_once(CLASSES_DIR.'html.php');
	// require_once(CLASSES_DIR.'logging.php');
	// require_once(CLASSES_DIR.'datawrapper.php');
	// require_once(CLASSES_DIR.'datacontainer.php');
	// require_once(CLASSES_DIR.'posts.php');
	// require_once(CLASSES_DIR.'entry.php');
?>
