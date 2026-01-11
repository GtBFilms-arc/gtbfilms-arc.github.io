<?php
  // --------------------------
  // Simple PHP Blog Theme File
  // --------------------------

  // Name: Simple Template Theme
  // Author: Alexander Palmo
  // Version: 0.5.0

  // Description:
  // This theme uses separate template files and "str_replace"
  // to accomplish its goal.
  //
  // Should be easier for the novice to edit this theme and make
  // quick modifications without having to edit the PHP code much.

  theme_init();

  // ---------------
  // Theme Variables
  // ---------------
  function theme_init () {
    global $theme_vars;

    $theme_vars = array();

    // Optional:
    // "content_width" and "menu_width" area used internally
    // within the theme only. (optional but highly recommended.)
    $theme_vars[ 'content_width' ] = 550;
    $theme_vars[ 'menu_width' ] = 200;

    // Required:
    // "popup_window" "width" and "height" are used to determine
    // the size of window to open for the comment view.
    $theme_vars[ 'popup_window' ][ 'width' ] = $theme_vars[ 'content_width' ] + 50;
    $theme_vars[ 'popup_window' ][ 'height' ] = 600;

    // Optional:
    // "popup_window" "content_width" is only used internally.
    $theme_vars[ 'popup_window' ][ 'content_width' ] = $theme_vars[ 'content_width' ];

    // Required:
    // Determines the maximum width of images within a page.
    // Make sure this value is less then "content_width" or you
    // might have wrapping problems.
    $theme_vars[ 'max_image_width' ] = $theme_vars[ 'content_width' ] - 50;
  }

  // -------------
  // Load Templates
  // -------------
  function theme_load_template( $url ) {
    global $theme_vars, $blog_theme, $user_colors;

    // Description:
    // This function loads an individual template .html file and does
    // the first round of search/replace for some global strings and
    // the color strings. This is a utility function.

    $template = sb_read_file( $url );

    // SEARCH AND REPLACE TERMS
    $search = array();
    $replace = array();

    // DEFAULT REPLACEMENTS
    array_push( $search, '%page_width%' );
    array_push( $replace, $theme_vars[ 'content_width' ] + $theme_vars[ 'menu_width' ] );
    array_push( $search, '%image_path%' );
    array_push( $replace, "themes/" . $blog_theme . "/images/" );
    array_push( $search, '%content_width%' );
    array_push( $replace, $theme_vars[ 'content_width' ] );
    array_push( $search, '%menu_width%' );
    array_push( $replace, $theme_vars[ 'menu_width' ] );

    // COLORS
    $arr = array_keys( $user_colors );
    for ( $i = 0; $i < count( $arr ); $i++ ) {
      array_push( $search, '%" . $arr[$i] . "%' );
      array_push( $replace, $user_colors[ $arr[$i] ] );
    }

    $template = str_replace($search, $replace, $template);

    return ( $template );
  }

  // ---------
  // Blog Entry
  // ---------
  //
  // Function:
  // theme_blogentry ( $entry_array, $mode='entry' )
  //
  // Theme for Blog Entries
  // ----------------------
  // All data is stored the $entry_array associative array and
  // passed to the function. Keep in mind that multiple languages
  // are used. So, try not to hard-code 'english' strings. If
  // you are creating graphics for buttons, try to use icons
  // instead of words.
  //
  // (Please note, the "\n" at the end of the echo() command adds
  // a return charater in the HTML source. This is standard PHP
  // behavior. Note the '\n' will not work for this. It has to be
  // surrounded by double-quotes.)
  //
  // The array could contains the following keys:
  // $entry_array[ 'subject' ]          = String: Subject line
  // $entry_array[ 'date' ]             = String: Date posted in the appropriate language and format.
  // $entry_array[ 'entry' ]            = String: The body of the blog entry
  // $entry_array[ 'logged_in' ]       = Boolean: True if user is logged in (used to determine whether to show 'edit' and 'delete' buttons)
  // $entry_array[ 'edit' ][ 'url' ]      = String: URL
  // $entry_array[ 'edit' ][ 'name' ]     = String: The word 'edit' in the appropriate language.
  // $entry_array[ 'delete' ][ 'url' ]    = String: URL
  // $entry_array[ 'delete' ][ 'name' ]   = String: The word 'delete' in the appropriate language.
  // $entry_array[ 'comment' ][ 'url' ]   = String: URL
  // $entry_array[ 'comment' ][ 'name' ]  = String: This will be 'add comment', '1 comment', or '2 comments' in the appropriate language.
  // $entry_array[ 'comment' ][ 'count' ] = String: The number of 'views' in the appropriate language.
  // $entry_array[ 'count' ]            = Integer: Index of current entry (i.e. use this if you want to add a line after every entry except the last one...)
  // $entry_array[ 'maxcount' ]         = Integer: Total number of entries
  //
  function theme_blogentry ( $entry_array, $mode='entry' ) {
    global $blog_config, $user_colors, $blog_theme;

    // ------------------
    // HTML ENTRY TEMPLATE
    // ------------------
    $template = '';
    switch ( $mode ) {
      case 'entry' :
        $template = theme_load_template( "themes/" . $blog_theme . "/templates/entry.html" );
        break;
      case 'comment' :
        $template = theme_load_template( "themes/" . $blog_theme . "/templates/comment.html" );
        break;
      case 'static' :
        $template = theme_load_template( "themes/" . $blog_theme . "/templates/static.html" );
        break;
    }

    // CATEGORIES
    $category_string = '';
    if ( array_key_exists( "categories", $entry_array ) ) {
      for ( $i = 0; $i < count( $entry_array[ 'categories' ] ); $i++ ) {
        $category_string .= '<a href="index.php?category=' . $entry_array[ 'categories_id' ][$i] . '">' . $entry_array[ 'categories' ][$i] . '</a>';
        if ( $i < count( $entry_array[ 'categories' ] ) - 1 ) {
          $category_string .= ', ';
        }
      }
    }

    // EDIT/DELETE BUTTONS
    $edit_button = "";
    $delete_button = "";
    $ban_button = "";
    if ( isset( $entry_array[ 'logged_in' ] ) && $entry_array[ 'logged_in' ] == true ) {
      // Show 'edit' and 'delete' buttons if the user is logged-in...
      if ( isset( $entry_array[ 'edit' ][ 'url' ] ) ) {
        $edit_button = '<a href="' . $entry_array[ 'edit' ][ 'url' ] . '">[ ' . $entry_array[ 'edit' ][ 'name' ] . ' ]</a>';
      }
      if ( isset( $entry_array[ 'delete' ][ 'url' ] ) ) {
        $delete_button = '<a href="' . $entry_array[ 'delete' ][ 'url' ] . '">[ ' . $entry_array[ 'delete' ][ 'name' ] . ' ]</a>';
      }
      if ( isset( $entry_array[ 'ban' ][ 'url' ] ) ) {
        $ban_button = '<a href="' . $entry_array[ 'ban' ][ 'url' ] . '">[ ' . $entry_array[ 'ban' ][ 'name' ] . ' ]</a>';
      }
    }

    // IP ADDRESS
    $ipaddress = "";
    if ( isset( $entry_array[ 'logged_in' ] ) && $entry_array[ 'logged_in' ] == true ) {
      if ( array_key_exists( 'ip-address', $entry_array ) && $mode == 'comment' ) {
        $ipaddress = '(' . $entry_array[ 'ip-address' ] . ')';
      }
    }

    // ADD COMMENT BUTTON
    $add_comment = "";
    if ( isset( $entry_array[ 'comment' ][ 'url' ] ) ) {
      // Show 'add comment' button if set...
      $add_comment = '<a href="' . $entry_array[ 'comment' ][ 'url' ] . '">[ ' . $entry_array[ 'comment' ][ 'name' ] . ' ]</a>';
    }

    // VIEW COUNTER
    $views = "";
    if ( isset( $entry_array[ 'comment' ][ 'count' ] ) ) {
      // Show '( x views )' string...
      $views = '( ' . $entry_array[ 'comment' ][ 'count' ] . ' )';
    }

    // TRACKBACKS
    $trackbacks = "";
    if ( isset( $entry_array[ 'trackback' ][ 'url' ] ) ) {
      // Show 'trackback'
      $trackbacks = '<a href="' . $entry_array[ 'trackback' ][ 'url' ] . '">[ ' . $entry_array[ 'trackback' ][ 'name' ] . ' ]</a>';
    }

    // PERMALINK
    $permalink = "";
    if ( $blog_config['blog_enable_permalink'] ){ // New for 0.4.6
      if ( isset( $entry_array[ 'permalink' ][ 'url' ] ) ) {
        // Show 'permalink'
        $permalink = '<a href="' . $entry_array[ 'permalink' ][ 'url' ] . '">' . $entry_array[ 'permalink' ][ 'name' ] . '</a>';
      }
    }

    // RELATED LINK
    $relatedlink = "";
    if ( isset( $entry_array['relatedlink']['url'] ) ) {
      // Show 'related link'
      $relatedlink = '<a href="' . $entry_array[ 'relatedlink' ][ 'url' ] . '">' . $entry_array[ 'relatedlink' ][ 'name' ] . '</a>';
    }

    // RATING STARS
    $ratings = "";
    if ( isset( $entry_array[ 'stars' ] ) ) {
      // Show 'stars' symbol
      $ratings = $entry_array[ 'stars' ];
    }

    // TRACKBACKS (RDF)
    $rdf_string = "";
    if ( $blog_config[ 'blog_trackback_enabled' ] ) {
      // This is the trackback link
      ob_start();
?>
<!--
<rdf:RDF xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
         xmlns:dc="http://purl.org/dc/elements/1.1/"
         xmlns:trackback="http://madskills.com/public/xml/rss/module/trackback/">
<rdf:Description
    rdf:about="<?php echo( $entry_array[ 'permalink' ][ 'url' ] ); ?>"
    dc:identifier="<?php echo( $entry_array[ 'permalink' ][ 'url' ] ); ?>"
    dc:title="<?php echo( $entry_array[ 'subject' ] ); ?>"
    trackback:ping="<?php echo( $entry_array[ 'trackback' ][ 'ping_url' ] ); ?>" />
</rdf:RDF>
-->
<?php
      $rdf_string = ob_get_clean();
    }

    // SUBJECT
    $subject = "";
    if ( isset( $entry_array[ 'subject' ] ) ) {
      $subject = $entry_array[ 'subject' ];
    }

    // ID
    $id = "";
    if ( isset( $entry_array[ 'id' ] ) ) {
      $id = $entry_array[ 'id' ];
    }

    // DATE
    $date = "";
    if ( isset( $entry_array[ 'date' ] ) ) {
      $date = $entry_array[ 'date' ];
    }

    // ENTRY CONTENT
    $entry_content = "";
    if ( isset( $entry_array[ 'entry' ] ) ) {
      $entry_content = $entry_array[ 'entry' ];
    }

    // SEARCH AND REPLACE TERMS
    $search = array();
    $replace = array();

    array_push( $search, '%rdf%' );
    array_push( $replace, $rdf_string );
    array_push( $search, '%subject%' );
    array_push( $replace, $subject );
    array_push( $search, '%id%' );
    array_push( $replace, $id );
    array_push( $search, '%date%' );
    array_push( $replace, $date );
    array_push( $search, '%categories%' );
    array_push( $replace, $category_string );
    array_push( $search, '%edit_button%' );
    array_push( $replace, $edit_button );
    array_push( $search, '%delete_button%' );
    array_push( $replace, $delete_button );
    array_push( $search, '%ban_button%' );
    array_push( $replace, $ban_button );
    array_push( $search, '%add_comment%' );
    array_push( $replace, $add_comment );
    array_push( $search, '%views%' );
    array_push( $replace, $views );
    array_push( $search, '%trackbacks%' );
    array_push( $replace, $trackbacks );
    array_push( $search, '%permalink%' );
    array_push( $replace, $permalink );
    array_push( $search, '%relatedlink%' );
    array_push( $replace, $relatedlink );
    array_push( $search, '%ipaddress%' );
    array_push( $replace, $ipaddress );
    array_push( $search, '%ratings%' );
    array_push( $replace, $ratings );
    array_push( $search, '%content%' );
    array_push( $replace, $entry_content );

    // DO SEARCH AND REPLACE
    $blog_content = str_replace( $search, $replace, $template );

    // RETURN HTML
    return $blog_content;
  }

  // Generic Entry
  function theme_genericentry ( $entry_array, $style='normal' ) { // New 0.5.0
    global $user_colors, $blog_theme, $blog_config;

    // init vars
    $img_path = "themes/" . $blog_theme . "/images/";
    $blog_content = '';
    $blog_content = $blog_content . "\n<!-- STATIC NO HEADER ENTRY START -->\n";

    // Text of entry

    if ( $style == 'solid' ) {
      $blog_content = $blog_content . '<div class="blog_body_solid">' . "\n\t";
    } elseif ( $style == 'clear' ) {
      $blog_content = $blog_content . '<div class="blog_body_clear">' . "\n\t";
    } else {
      $blog_content = $blog_content . '<div class="blog_body_framed">' . "\n\t";
    }

    $blog_content = $blog_content . $entry_array[ 'entry' ];
    $blog_content = $blog_content . "\n\t</div>";

    $blog_content = $blog_content . "<br />";

    $blog_content = $blog_content . "\n<!-- STATIC NO HEADER ENTRY END -->\n";

    return $blog_content;
  }

  // ----------
  // Static Entry
  // ----------
  function theme_staticentry ( $entry_array ) {
    $blog_content = theme_blogentry( $entry_array, 'static' );
    return $blog_content;
  }

  // -------------
  // Comment Entry
  // -------------
  function theme_commententry ( $entry_array ) {
    $blog_content = theme_blogentry( $entry_array, 'comment' );
    return $blog_content;
  }

  // --------------
  // Trackback Entry
  // --------------
  function theme_trackbackentry ( $entry_array ) {
    global $blog_config, $user_colors;

     $blog_content = "\n";

    $blog_content  .= '<div class="blog_subject">' . $entry_array[ 'title' ] . '</div>' . "\n";
    $blog_content  .= '<div class="blog_date">' . $entry_array[ 'date' ] . '</div>' . "\n";

    if ( isset( $entry_array[ 'logged_in' ] ) && $entry_array[ 'logged_in' ] == true ) {
      // Show 'delete' button if the user is logged-in...
      if ( isset( $entry_array[ 'delete' ][ 'url' ] ) ) {
        $blog_content  .= '<a href="' . $entry_array[ 'delete' ][ 'url' ] . '">[ ' . $entry_array[ 'delete' ][ 'name' ] . ' ]</a><br /><br />' . "\n";
      }
    }

    $blog_content  .= $entry_array[ 'excerpt' ] . "<p>\n";

    if ( (isset( $entry_array[ 'blog_name' ] ) ) && ($entry_array[ 'blog_name' ] != "") ) {
       $blog_content = $blog_content . '<a href="'.$entry_array[ 'url' ].'">[ ' . $entry_array[ 'blog_name' ] . " ]</a><p>\n";
    } else {
       $blog_content = $blog_content . '<a href="'.$entry_array[ 'url' ].'">[ ' . $entry_array[ 'url' ] . " ]</a><p>\n";
    }

    $blog_content  .= '<hr />' . "\n";

    return $blog_content;
  }

  // ------
  // Colors
  // ------
  //
  // Function:
  // theme_default_colors( )
  //
  // Default Base Colors
  // -------------------
  // $user_colors is an associative array that stores
  // all color information. These are the default colors
  // for the theme. These values are read in, and then
  // get overwritten when the user saved colors are
  // read from file.
  //
  // Custom keys appear on the Color page.
  //
  function theme_default_colors () {
    global $lang_string;

    $color_def = array();

    // Backgrounds
    array_push( $color_def, array( 'id' => 'main_bg_color',
                'string' => $lang_string[ 'main_bg_color' ],
                'default' => 'FFFFFF' ) );
    array_push( $color_def, array( 'id' => 'header_bg_color',
                'string' => $lang_string[ 'header_bg_color' ],
                'default' => '999966' ) );
    array_push( $color_def, array( 'id' => 'footer_bg_color',
                'string' => $lang_string[ 'footer_bg_color' ],
                'default' => 'EEEEEE' ) );

    // Menus
    array_push( $color_def, array( 'id' => 'menu_bg',
                'string' => $lang_string[ 'menu_bg' ],
                'default' => 'ACACA0' ) );
    array_push( $color_def, array( 'id' => 'menu_title_bg',
                'string' => $lang_string[ 'menu_title_bg' ],
                'default' => '959587' ) );
    array_push( $color_def, array( 'id' => 'menu_border',
                'string' => $lang_string[ 'menu_border' ],
                'default' => 'DADACE' ) );
    array_push( $color_def, array( 'id' => 'menu_title_text',
                'string' => $lang_string[ 'menu_title_text' ],
                'default' => 'FFFFFF' ) );
    array_push( $color_def, array( 'id' => 'menu_text',
                'string' => $lang_string[ 'menu_text' ],
                'default' => 'FFFFFF' ) );


    array_push( $color_def, array( 'id' => 'menu_link_reg_color',
                'string' => $lang_string[ 'menu_link_reg_color' ],
                'default' => '59594F' ) );
    array_push( $color_def, array( 'id' => 'menu_link_hi_color',
                'string' => $lang_string[ 'menu_link_hi_color' ],
                'default' => 'FF6600' ) );
    array_push( $color_def, array( 'id' => 'menu_link_down_color',
                'string' => $lang_string[ 'menu_link_down_color' ],
                'default' => '6329F7' ) );

    // Border
    array_push( $color_def, array( 'id' => 'inner_border_color',
                'string' => $lang_string[ 'inner_border_color' ],
                'default' => 'D9D9D9' ) );

    // Text Color
    array_push( $color_def, array( 'id' => 'txt_color',
                'string' => $lang_string[ 'txt_color' ],
                'default' => '666633' ) );
    array_push( $color_def, array( 'id' => 'headline_txt_color',
                'string' => $lang_string[ 'headline_txt_color' ],
                'default' => '666633' ) );
    array_push( $color_def, array( 'id' => 'date_txt_color',
                'string' => $lang_string[ 'date_txt_color' ],
                'default' => '999999' ) );
    array_push( $color_def, array( 'id' => 'header_txt_color',
                'string' => $lang_string[ 'header_txt_color' ],
                'default' => 'FFFFFF' ) );
    array_push( $color_def, array( 'id' => 'footer_txt_color',
                'string' => $lang_string[ 'footer_txt_color' ],
                'default' => '666666' ) );

    // Links
    array_push( $color_def, array( 'id' => 'link_reg_color',
                'string' => $lang_string[ 'link_reg_color' ],
                'default' => '993333' ) );
    array_push( $color_def, array( 'id' => 'link_hi_color',
                'string' => $lang_string[ 'link_hi_color' ],
                'default' => 'FF3333' ) );
    array_push( $color_def, array( 'id' => 'link_down_color',
                'string' => $lang_string[ 'link_down_color' ],
                'default' => '3333FF' ) );

    // Entries
    array_push( $color_def, array( 'id' => 'entry_title_text',
                'string' => $lang_string[ 'entry_title_text' ],
                'default' => '666661' ) );

    return ( $color_def );
  }

  // --------------
  // Main Page Layout
  // --------------
  //
  // Function:
  // theme_pagelayout( )
  //
  // Page Layout Container/Wrapper
  // -----------------------------
  // This function controls all HTML output to the browser.
  //
  // Invoking the page_content() fuction inserts the actual
  // contents of the page.
  //
  function theme_pagelayout () {
    global $user_colors, $blog_config, $blog_theme, $theme_vars;

    // -----------------
    // HTML PAGE TEMPLATE
    // -----------------
    $template = theme_load_template( "themes/" . $blog_theme . "/templates/main_layout.html" );

    // SEARCH AND REPLACE TERMS
    $search = array();
    $replace = array();

    // BLOG CONTENT
    array_push( $search, '%blog_title%' );
    array_push( $replace, $blog_config[ 'blog_title' ] );
    array_push( $search, '%menu%' );
    array_push( $replace, "theme_menu()" );
    array_push( $search, '%footer%' );
    array_push( $replace, $blog_config[ 'blog_footer' ] . " - " . page_generated_in() );

    // MENU WIDGETS (NO CUSTOM PATH)
    array_push( $search, '%widget_avatar%' );
    array_push( $replace, theme_widget_avatar() );
    array_push( $search, '%widget_links%' );
    array_push( $replace, theme_widget_links() );
    array_push( $search, '%widget_user%' );
    array_push( $replace, theme_widget_user() );
    array_push( $search, '%widget_setup%' );
    array_push( $replace, theme_widget_setup() );
    array_push( $search, '%widget_custom%' );
    array_push( $replace, theme_widget_custom() );
    array_push( $search, '%widget_calendar%' );
    array_push( $replace, theme_widget_calendar() );
    array_push( $search, '%widget_random_entry%' );
    array_push( $replace, theme_widget_random_entry() );
    array_push( $search, '%widget_archive_tree%' );
    array_push( $replace, theme_widget_archive_tree() );
    array_push( $search, '%widget_categories%' );
    array_push( $replace, theme_widget_categories() );
    array_push( $search, '%widget_search%' );
    array_push( $replace, theme_widget_search() );
    array_push( $search, '%widget_counter%' );
    array_push( $replace, theme_widget_counter() );
    array_push( $search, '%widget_recent_entries%' );
    array_push( $replace, theme_widget_recent_entries() );
    array_push( $search, '%widget_recent_comments%' );
    array_push( $replace, theme_widget_recent_comments() );
    array_push( $search, '%widget_recent_trackbacks%' );
    array_push( $replace, theme_widget_recent_trackbacks() );

    // MENU WIDGETS (CUSTOM PATH)
    $pattern_arr = Array();
    $function_arr = Array();
    array_push( $pattern_arr, 'widget_avatar' );
    array_push( $function_arr, 'theme_widget_avatar' );
    array_push( $pattern_arr, 'widget_links' );
    array_push( $function_arr, 'theme_widget_links' );
    array_push( $pattern_arr, 'widget_user' );
    array_push( $function_arr, 'theme_widget_user' );
    array_push( $pattern_arr, 'widget_setup' );
    array_push( $function_arr, 'theme_widget_setup' );
    array_push( $pattern_arr, 'widget_custom' );
    array_push( $function_arr, 'theme_widget_custom' );
    array_push( $pattern_arr, 'widget_calendar' );
    array_push( $function_arr, 'theme_widget_calendar' );
    array_push( $pattern_arr, 'widget_random_entry' );
    array_push( $function_arr, 'theme_widget_random_entry' );
    array_push( $pattern_arr, 'widget_archive_tree' );
    array_push( $function_arr, 'theme_widget_archive_tree' );
    array_push( $pattern_arr, 'widget_categories' );
    array_push( $function_arr, 'theme_widget_categories' );
    array_push( $pattern_arr, 'widget_search' );
    array_push( $function_arr, 'theme_widget_search' );
    array_push( $pattern_arr, 'widget_counter' );
    array_push( $function_arr, 'theme_widget_counter' );
    array_push( $pattern_arr, 'widget_recent_entries' );
    array_push( $function_arr, 'theme_widget_recent_entries' );
    array_push( $pattern_arr, 'widget_recent_comments' );
    array_push( $function_arr, 'theme_widget_recent_comments' );
    array_push( $pattern_arr, 'widget_recent_trackbacks' );
    array_push( $function_arr, 'theme_widget_recent_trackbacks' );

    for ($i=0; $i<count($pattern_arr); $i++) {

      $pattern = $pattern_arr[$i];
      $function = $function_arr[$i];

      preg_match_all('/%' . $pattern . '=(.+)%/', $template, $matches );
      $attrib_arr = $matches[1];

      if ( count( $attrib_arr ) ) {
        foreach ($attrib_arr as $attrib) {
          // echo( '%' .$pattern . '=' . $attrib . '%' . '<br />' );
          array_push( $search, '%' .$pattern . '=' . $attrib . '%' );
          $str = call_user_func( $function, $attrib );
          array_push( $replace, $str );
        }
      }
    }

    array_push( $search, '%widget_badges%' );
    array_push( $replace, theme_widget_badges() );

    // MAIN CONTENT
    array_push( $search, '%content%' );

    ob_start();
    page_content();
    $main_content = ob_get_clean();

    array_push( $replace,  $main_content);

    // DO SEARCH AND REPLACE
    echo( str_replace( $search, $replace, $template ) );
  }

  // -----------------
  // Popup Window Layout
  // -----------------
  //
  // Function:
  // theme_popuplayout( )
  //
  // Popup Layout Container/Wrapper
  // -----------------------------
  // This function controls all HTML output to the browser.
  //
  // Same as above, but for the pop-up comment window and
  // the image list pop-up.
  //
  function theme_popuplayout () {
    global $user_colors, $blog_config, $blog_theme, $theme_vars;

    // ------------------
    // HTML POPUP TEMPLATE
    // ------------------
    $template = theme_load_template( "themes/" . $blog_theme . "/templates/popup_layout.html" );

    // SEARCH AND REPLACE TERMS
    $search = array();
    $replace = array();

    array_push( $search, '%popup_width%' );
    array_push( $replace, $theme_vars[ 'popup_window' ][ 'content_width' ] );

    $arr = array_keys( $user_colors );
    for ( $i = 0; $i < count( $arr ); $i++ ) {
      array_push( $search, '%" . $arr[$i] . "%' );
      array_push( $replace, $user_colors[ $arr[$i] ] );
    }

    array_push( $search, '%blog_title%' );
    array_push( $replace, $blog_config[ 'blog_title' ] );
    array_push( $search, '%footer%' );
    array_push( $replace, $blog_config[ 'blog_footer' ] . " - " . page_generated_in() );

    // MAIN CONTENT
    array_push( $search, '%content%' );

    ob_start();
    page_content();
    $main_content = ob_get_clean();

    array_push( $replace,  $main_content);

    // DO SEARCH AND REPLACE
    echo( str_replace($search, $replace, $template ) );
  }

  // ----------
  // Menu Block
  // ----------
  function theme_menu_block ($blockArray, $comment='MENU BLOCK', $toggleDiv=null, $template_file='menu_block.html' ) {
    global $blog_theme;

    if ( $template_file == false ) {
      $template_file = 'menu_block.html';
    }

    // ------------------
    // HTML BLOCK TEMPLATE
    // ------------------
    if ( isset( $blockArray[ 'content' ] ) && $blockArray[ 'content' ] != '' ) {

      // Expand/Collapse Images
      $img_path = "themes/" . $blog_theme . "/images/";
      $img_show = $img_path . 'plus.gif';
      $img_hide = $img_path . 'minus.gif';

      // SEARCH AND REPLACE TERMS
      $search = array();
      $replace = array();

      array_push( $search, '%title%' );
      array_push( $replace, $blockArray[ 'title' ] );
      array_push( $search, '%content%' );
      array_push( $replace, $blockArray[ 'content' ] );
      array_push( $search, '%comment%' );
      array_push( $replace, $comment );
      array_push( $search, '%id%' );
      array_push( $replace, $toggleDiv );
      array_push( $search, '%twisty%' );
      array_push( $replace, $img_hide );

      $template = theme_load_template( "themes/" . $blog_theme . "/templates/" . $template_file );

      return( str_replace( $search, $replace, $template) );
    }
  }

  // ------------------
  // Complete Sidebar Menu
  // ------------------
  function theme_menu() {
    global $user_colors, $blog_theme, $lang_string, $theme_vars, $logged_in, $sb_info, $blog_config;

    // ----------------
    // HTML SIDEBAR MENU
    // ----------------
    $html = "";
    $html .= "\n<!-- SIDEBAR MENU BEGIN -->\n";
    $html .= theme_widget_avatar();
    $html .= theme_widget_links();
    $html .= theme_widget_user();
    $html .= theme_widget_setup();
    $html .= theme_widget_custom();
    $html .= theme_widget_calendar();
    $html .= theme_widget_random_entry();
    $html .= theme_widget_archive_tree();
    $html .= theme_widget_categories();
    $html .= theme_widget_search();
    $html .= theme_widget_counter();
    $html .= theme_widget_recent_entries();
    $html .= theme_widget_recent_comments();
    $html .= theme_widget_recent_trackbacks();
    $html .= '<p />';
    $html .= theme_widget_badges();
    $html .= "\n<!-- SIDEBAR MENU END -->\n";

    // RETURN HTML
    return ( $html );
  }

  // ------------------
  // Sidebar Menu "Widgets"
  // ------------------

  // LINKS
  function theme_widget_links( $template_file='menu_block.html' ) {
    $result = menu_display_links();
    $loginString = menu_display_login();
    if ( $loginString ) {
      $result[ 'content' ]  .= '<hr />' . $loginString;
    }
    $html = theme_menu_block( $result, 'LINKS', 'SidebarLinks', $template_file );
    return ( $html );
  }

  // AVATAR
  function theme_widget_avatar( $template_file='menu_block.html' ) {
    $html = theme_menu_block( menu_display_avatar(), 'AVATAR', 'SidebarAvatar', $template_file );
    return ( $html );
  }

  // MENU
  function theme_widget_user( $template_file='menu_block.html' ) {
    $html = theme_menu_block( menu_display_user(), 'USER MENU', 'SidebarMenu', $template_file );
    return ( $html );
  }

  // SETUP
  function theme_widget_setup( $template_file='menu_block.html' ) {
    $html = theme_menu_block( menu_display_setup(), 'SETUP MENU', 'SidebarPreferences', $template_file );
    return ( $html );
  }

  // CUSTOM BLOCKS
  function theme_widget_custom( $template_file='menu_block.html' ) {
    global $logged_in;

    $html = "";
    $array = read_blocks($logged_in);
    for ($i=0 ; $i<count($array) ; $i+=2) {
      $result = Array();
      $result[ 'title' ] = $array[$i];
      $result[ 'content' ] = $array[$i+1];
      $html .= theme_menu_block( $result, 'CUSTOM BLOCK', null, $template_file );
    }

    return ( $html );
  }

  // CALENDAR
  function theme_widget_calendar( $template_file='menu_block.html' ) {
    $html = theme_menu_block( menu_display_blognav(), 'CALENDAR', 'SidebarCalendar', $template_file );
    return ( $html );
  }

  // RANDOM ENTRY
  function theme_widget_random_entry( $template_file='menu_block.html' ) {
    $html = theme_menu_block( menu_random_entry(), 'RANDOM ENTRY', 'SidebarRandomEntry', $template_file );
    return ( $html );
  }

  // ARCHIVE TREE
  function theme_widget_archive_tree( $template_file='menu_block.html' ) {
    $html = theme_menu_block( menu_display_blognav_tree(), 'ARCHIVE TREE', 'SidebarArchives', $template_file );
    return ( $html );
  }

  // CATEGORIES
  function theme_widget_categories( $template_file='menu_block.html' ) {
    $html = theme_menu_block( menu_display_categories(), 'CATEGORIES', 'SidebarCategories', $template_file );
    return ( $html );
  }

  // SEARCH
  function theme_widget_search( $template_file='menu_block.html' ) {
    $html = theme_menu_block( menu_search_field(), 'SEARCH', 'SidebarSearch', $template_file );
    return ( $html );
  }

  // COUNTER
  function theme_widget_counter( $template_file='menu_block.html' ) {
    $html = theme_menu_block( menu_display_countertotals(), 'COUNTER', 'SidebarCounter', $template_file );
    return ( $html );
  }

  // RECENT ENTRIES
  function theme_widget_recent_entries( $template_file='menu_block.html' ) {
    $html = theme_menu_block( menu_most_recent_entries(), 'RECENT ENTRIES', 'SidebarRecentEntries', $template_file );
    return ( $html );
  }

  // RECENT COMMENTS
  function theme_widget_recent_comments( $template_file='menu_block.html' ) {
    $html = theme_menu_block( menu_most_recent_comments(), 'RECENT COMMENTS', 'SidebarRecentComments', $template_file );
    return ( $html );
  }

  // RECENT TRACKBACKS
  function theme_widget_recent_trackbacks( $template_file='menu_block.html' ) {
    $html = theme_menu_block( menu_most_recent_trackbacks(), 'RECENT TRACKBACKS', 'SidebarRecentTrackbacks', $template_file );
    return ( $html );
  }

  // BADGES
  function theme_widget_badges() {
    $html = '';
    // $html .= '<div align="center">';
    $html .= '<a href="http://sourceforge.net/projects/sphpblog/"><img style="margin-bottom: 5px;" src="interface/button_sphpblog.png" alt="Powered by Simple PHP Blog" title="Powered by Simple PHP Blog" border="0" /></a> ';
    $html .= '<a href="rss.php"><img style="margin-bottom: 5px;" src="interface/button_rss20.png" alt="Get RSS 2.0 Feed" title="Get RSS 2.0 Feed" border="0" /></a><br />';
    $html .= '<a href="http://php.net/"><img style="margin-bottom: 5px;" src="interface/button_php.png" alt="Powered by PHP ' . phpversion() . '" title="Powered by PHP ' . phpversion() . '" border="0" /></a> ';
    $html .= '<a href="atom.php"><img style="margin-bottom: 5px;" src="interface/button_atom03.png" alt="Get Atom 0.3 Feed" title="Get Atom 0.3 Feed" border="0" /></a><br />';
    $html .= '<img style="margin-bottom: 5px;" src="interface/button_txt.png" alt="Powered by Plain text files" title="Powered by Plain text files" border="0" /> ';
    $html .= '<a href="rdf.php"><img style="margin-bottom: 5px;" src="interface/button_rdf10.png" alt="Get RDF 1.0 Feed" title="Get RDF 1.0 Feed" border="0" /></a><br />';
    // $html .= '</div>';
    return ( $html );
  }
?>
