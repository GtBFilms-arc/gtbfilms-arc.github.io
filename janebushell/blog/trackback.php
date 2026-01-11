<?php
  // ---------------
  // INITIALIZE PAGE
  // ---------------
  require_once('scripts/sb_functions.php');
  global $logged_in;
  $logged_in = logged_in( false, true );  
  
  read_config();
  
  require_once('languages/' . $blog_config[ 'blog_language' ] . '/strings.php');
  sb_language( 'trackbacks' );
  
  // ---------------
  // POST PROCESSING
  // ---------------

  function trackback_response( $val, $msg ) {
    echo "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n";
    echo "<response>\n";
    echo "  <error>$val</error>\n";
    if( $val > 0 ) {
      echo "  <message>$msg</message>\n";
    }
    echo "</response>\n";
    exit;
  }
  $port = ':' . $_SERVER[ 'SERVER_PORT'];
  if ($port == ':80') {
    $port = '';
  }
  
  if ( ( dirname($_SERVER[ 'PHP_SELF' ]) == '\\' || dirname($_SERVER[ 'PHP_SELF' ]) == '/' ) ) {
    // Hosted at root.
    $base_url = '://' . $_SERVER[ 'HTTP_HOST' ].$port;
  } else {
    // Hosted in sub-directory.
    $base_url = '://' . $_SERVER[ 'HTTP_HOST' ].$port.dirname($_SERVER[ 'PHP_SELF' ]);
  }
  
   // trackback ping contains entry in the URI
  $redirect = true;
  if ( isset( $_GET[ 'y' ] ) && isset( $_GET[ 'm' ] ) && isset( $_GET[ 'entry' ] ) ) {
    $entry_id = CONTENT_DIR.$_GET[ 'y' ].'/'.$_GET[ 'm' ].'/'.$_GET[ 'entry' ];
    $entry = $_GET[ 'entry' ];
    $year = $_GET[ 'y' ];
    $month = $_GET[ 'm' ];
    
    if ( file_exists( $entry_id . '.txt' ) ) {
      $redirect = false;
    } elseif ( file_exists( $entry_id . '.txt.gz' ) ) {
      $redirect = false;
    }
  }

  // trackback is done by a POST
  $tb_url = $_POST[ 'url' ];
  $title = $_POST[ 'title' ];
  $excerpt = $_POST[ 'excerpt' ];
  $blog_name = $_POST[ 'blog_name' ];

  // No such entry exists OR trackback is disabled
  if ( ($redirect === true ) || ( !$blog_config[ 'blog_trackback_enabled' ] ) ) {
    redirect_to_url( 'index.php' );
  }

  if ( ( strlen('' . $entry ) ) && ( empty( $_GET[ '__mode' ] ) ) && ( strlen( '' . $tb_url ) ) && ( strpos( sb_read_file( $tb_url ), $base_url ) !== false ) ) {
    @header('Content-Type: text/xml');
    
    $tb_url = addslashes($tb_url);
    $title = strip_tags($title);
    $title = ( strlen($title) > 127 ? substr( $title, 0, 124 ) . '...' : $title );
    $excerpt = strip_tags($excerpt);
    $excerpt = ( strlen($excerpt) > 127 ? substr( $excerpt, 0, 124 ) . '...' : $excerpt );
    $blog_name = htmlspecialchars($blog_name);
    $blog_name = ( strlen($blog_name) > 127 ? substr( $blog_name, 0, 124 ) . '...' : $blog_name );
    
    $user_ip = $HTTP_SERVER_VARS[ 'REMOTE_ADDR' ];
    $user_domain = @gethostbyaddr($user_ip);
    
    $ok = write_trackback( $_GET[ 'y' ], $_GET[ 'm' ], $entry = $_GET[ 'entry' ], $tb_url, $title, $excerpt, $blog_name, $user_ip, $user_domain );
    
    if (!$ok) {
      trackback_response(1, $lang_string[ 'error_add' ] );
    } else {
      trackback_response(0, '');
    }

  } else if( $_GET[ '__mode' ] === 'html' ) {
    //
    // Mode HTML: display in the style of the sphpblog
    //
?>
  <?php echo( get_init_code() ); ?>
  <?php require_once('themes/' . $blog_theme . '/user_style.php'); ?>  
  <?php require_once('scripts/sb_editor.php'); ?>

  <title><?php echo($blog_config[ 'blog_title' ]); ?> - <?php echo( $lang_string[ 'title' ] ); ?></title>
</head>
<?php
  function page_content() {
    global $lang_string, $user_colors, $logged_in, $theme_vars;

    $port = ':' . $_SERVER[ 'SERVER_PORT'];
    if ($port == ':80') {
      $port = '';
    }
    if ( ( dirname($_SERVER[ 'PHP_SELF' ]) == '\\' || dirname($_SERVER[ 'PHP_SELF' ]) == '/' ) ) {
      // Hosted at root.
      $base_url = 'http://'.$_SERVER[ 'HTTP_HOST' ].$port.'/';
    } else {
      // Hosted in sub-directory.
      $base_url = 'http://'.$_SERVER[ 'HTTP_HOST' ].$port.dirname($_SERVER[ 'PHP_SELF' ]).'/';
    }
    
    $tb[ 'subject' ] = $lang_string[ 'title' ];
    $tb[ 'entry' ] = $lang_string[ 'header' ] . '<br />' . '<input type="text" style="width: ' . $theme_vars[ 'max_image_width' ] . 'px;" OnMouseOver=this.select() value="'.$base_url.'trackback.php?y='.$_GET[ 'y' ].'&m='.$_GET[ 'm' ].'&entry='.$_GET[ 'entry' ] . '">' . "<p />\n";
    echo ( theme_blogentry( $tb ) );
    
      echo ( read_trackbacks ( $_GET[ 'y' ], $_GET[ 'm' ], $_GET[ 'entry' ], $logged_in, true ) );
      echo "<p />\n";
  }

  global $blog_config;
  if ( $blog_config[ 'blog_comments_popup' ] == 1 ) {
    theme_popuplayout();
  } else {
    theme_pagelayout();
  }
?>
</html>

<?php
  } else {
    //
    // Default XML output
    //

    $port = ':' . $_SERVER[ 'SERVER_PORT'];
    if ($port == ':80') {
      $port = '';
    }
    if ( ( dirname($_SERVER[ 'PHP_SELF' ]) == '\\' || dirname($_SERVER[ 'PHP_SELF' ]) == '/' ) ) {
      // Hosted at root.
      $base_url = 'http://'.$_SERVER[ 'HTTP_HOST' ].$port.'/';
    } else {
      // Hosted in sub-directory.
      $base_url = 'http://'.$_SERVER[ 'HTTP_HOST' ].$port.dirname($_SERVER[ 'PHP_SELF' ]).'/';
    }

    header('Content-type: application/xml');
    
    echo '<?xml version="1.0" encoding="iso-8859-1"?>'."\n";
    echo "<response>\n";
    echo "<error>0</error>\n";
    echo '<rss version="0.91"><channel>'."\n";
    echo "<title>" . $blog_config[ 'blog_title' ] . "</title>\n";
    echo "<link>" . $base_url . "index.php</link>\n";
    echo "<description>". $blog_config[ 'blog_footer' ] . "</description>\n";
    echo "<language>" . str_replace( '_', '-', $lang_string[ 'locale' ] ) . "</language>\n";
    
    $results = read_trackbacks ( $year, $month, $entry, $logged_in, false );
    
    for ( $i = 0; $i <= count( $results ) - 1; $i++ ) {
      echo "<item>\n";
      echo "<title>" . $results[$i][ 'title' ] . "</title>\n";
      echo "<link>" . $results[$i][ 'url' ] . "</link>\n";
      echo "<description>" . $results[$i][ 'excerpt' ] . "</description>\n";
      echo "</item>\n";
    }
    
    echo "</channel>\n";
    echo "</rss></response>\n";
    }
?>
