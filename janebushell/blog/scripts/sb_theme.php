<?php

  // Simple PHP Blog
  // Version 0.3.5 - 06/04/2004
  // ------------------------------
  // Created by: Alexander Palmo, apalmo <at> bigevilbrain <dot> com
  //
  // The Simple PHP Blog is released under the GNU Public License.
  //
  // You are free to use and modify the Simple PHP Blog. All changes
  // must be uploaded to SourceForge.net under Simple PHP Blog or
  // emailed to apalmo <at> bigevilbrain <dot> com
  //
  // Credit should be give to the original author and the Simple PHP Blog
  // logo graphic must appear on the site and link to the project
  // on SourceForge.net

	// ------------------
	// Theme Menu Display
	// ------------------
	
	function menu_display_links () {
		$plugin = new Links;
		$arr = $plugin->display();
		unset( $plugin );
		return ( $arr );
	}

	function menu_display_countertotals () {
		$plugin = new CounterTotals;
		$arr = $plugin->display();
		unset( $plugin );
		return ( $arr );
	}
	
	function menu_random_entry() {
		$plugin = new RandomEntry;
		$arr = $plugin->display();
		unset( $plugin );
		return ( $arr );
	}
	
	function menu_display_avatar() {
		$plugin = new Avatar;
		$arr = $plugin->display();
		unset( $plugin );
		return ( $arr );
	}

	function menu_display_blognav () {
		$plugin = new Calendar;
		$arr = $plugin->display();
		unset( $plugin );
		return ( $arr );
	}

	function menu_display_blognav_tree () {
		$plugin = new Archives;
		$arr = $plugin->display();
		unset( $plugin );
		return ( $arr );
	}

	function menu_display_login () {
		return ( '' );
	}

	function menu_display_user () {
		$plugin = new AuthoringMenu;
		$arr = $plugin->display();
		unset( $plugin );
		return ( $arr );
	}

	function menu_display_categories () {
		$plugin = new Categories;
		$arr = $plugin->display();
		unset( $plugin );
		return ( $arr );
	}

	function menu_display_setup () {
		// Returns login / logout link as HTML.
		//
		// Example:
		// --------
		// Setup
		// Setup
		// Options
		// Colors
		// Themes
		// Change Login
		//
		global $lang_string, $logged_in, $user_colors, $blog_config;
		
		$admin = $_SESSION[ 'fulladmin' ];
			if (( $logged_in == true ) and ( $admin == 'yes' )) {
			$str = '';
			$str  .= '<a href="categories.php">' . $lang_string[ 'menu_categories' ] . '</a><br />';
			$str  .= '<a href="add_block.php">' . $lang_string[ 'menu_add_block' ] . '</a><br />';
			$str  .= '<a href="setup.php">' . $lang_string[ 'menu_setup' ] . '</a><br />';
			$str  .= '<a href="plugins.php">' . $lang_string[ 'menu_plugins' ] . '</a><br />';
			$str  .= '<a href="emoticons.php">' . $lang_string[ 'menu_emoticons' ] . '</a><br />';
			$str  .= '<a href="themes.php">' . $lang_string[ 'menu_themes' ] . '</a><br />';
			$str  .= '<a href="colors.php">' . $lang_string[ 'menu_colors' ] . '</a><br />';
			$str  .= '<a href="options.php">' . $lang_string[ 'menu_options' ] . '</a><br />';
			$str  .= '<a href="info.php">' . $lang_string[ 'menu_info' ] . '</a><br />';
			$str  .= '<a href="manage_users.php">' . $lang_string[ 'manage_users' ] . '</a><br />';
			$str  .= '<a href="phpinfo.php">' . $lang_string['manage_php_config'] . '</a><br />';
			$str  .= '<hr />';
			$str  .= '<a href="moderation.php">' . $lang_string[ 'menu_moderation' ] . '</a><br />';
			if ( $blog_config[ 'blog_comments_moderation' ] ) {
				$str  .= '<a href="comments_moderation.php">' . $lang_string[ 'menu_commentmoderation' ] . ' (' . get_unmodded_count(1) . ')</a><br />';
			}
			
			$result = array();
			$result[ 'title' ] = $lang_string[ 'menu_setup' ];
			$result[ 'content' ] = $str;
			
			return ( $result );
		}
	}

	function menu_most_recent_comments () {
		$plugin = new RecentComments;
		$arr = $plugin->display();
		unset( $plugin );
		return ( $arr );
	}

	function menu_most_recent_trackbacks () {
		$plugin = new RecentTrackbacks;
		$arr = $plugin->display();
		unset( $plugin );
		return ( $arr );
	}

	function menu_search_field () {
		$plugin = new Search;
		$arr = $plugin->display();
		unset( $plugin );
		return ( $arr );
	}

	function menu_search_field_horiz () {
		return menu_search_field();
	}

	function menu_most_recent_entries () {
		$plugin = new RecentEntries;
		$arr = $plugin->display();
		unset( $plugin );
		return ( $arr );
	}

	function page_generated_in () {
		// Returns "Page Generated x.xxxx in seconds"
		global $page_timestamp;

		$str = str_replace ( '%s', round( getmicrotime() - $page_timestamp, 4 ), $GLOBALS[ 'lang_string' ][ 'page_generated_in' ] );

		if ( $GLOBALS[ 'blog_config' ][ 'blog_footer_counter'] ) {
			$str  .= '&nbsp;|&nbsp;' . $GLOBALS[ 'lang_string' ][ 'counter_total' ] . stat_total();
		}

		return ( $str );
	}
	
	function get_user_color( $key, $default="ff0000") {
		$color = $GLOBALS['user_colors'][$key];
		if (isColor($color,0)) { return $color;	}
		return $default;
	}
		
	function isColor($value, $empty) {
		if (strlen($value) == 3) { return preg_match('/^[a-f0-9]{3}$/i', $value); }
		if (strlen($value) == 6) { return preg_match('/^[a-f0-9]{6}$/i', $value); }
		return $empty;
	}
?>