<?php
  // ---------------
  // INITIALIZE PAGE
  // ---------------
  require_once('scripts/sb_functions.php');
  global $logged_in;
  $logged_in = logged_in( false, true );
  
  read_config();

  require_once('languages/' . $blog_config[ 'blog_language' ] . '/strings.php');
  sb_language( 'login' );

  // ---------------
  // POST PROCESSING
  // ---------------
  $ok = check_password( sb_stripslashes( $_POST[ 'user' ] ), sb_stripslashes( $_POST[ 'pass' ] ) );

  if ( $ok > 99 ) {
    $logged_in = false;
  } else {
    $logged_in = $ok;
  }

  // ------------
  // PAGE CONTENT
  // ------------
  function page_content() {
    global $lang_string, $blog_config, $ok;
    
    // SUBJECT
    $entry_array = array();
    $entry_array[ 'subject' ] = $lang_string[ 'title' ];
    
    // PAGE CONTENT BEGIN
    ob_start();
    
    if ( $ok === true ) {
      echo( $lang_string[ 'success' ] );
    } else {
      switch ($ok) {
        case 100: $errortext = $lang_string[ 'wrong_password' ];
        case 101: $errortext = $lang_string[ 'inactive_account' ];
      }
      echo( $errortext );
    }
    
    echo( '<a href="index.php">' . $lang_string[ 'home' ] . '</a>' );
    
    $upgrade_count = move_all_comment_files( true, true );
    if ( $upgrade_count > 0 ) {
      echo( "<hr />" );
      echo( "<br />" );
      echo( $lang_string[ 'upgrade' ] );
      $str = str_replace ( '%n', $upgrade_count, $lang_string[ 'upgrade_count' ] );
      echo( $str . "<br /><br />" );
      echo( "<a href=\"upgrade.php\">" . $lang_string[ 'upgrade_url' ] ."</a><br />" );
    }
    
    // PAGE CONTENT END
    $entry_array[ 'entry' ] = ob_get_clean();
    
    // THEME ENTRY
    echo( theme_staticentry( $entry_array ) );
  }
  
  // ----
  // HTML
  // ----
?>
  <?php echo( get_init_code() ); ?>
  <?php require_once('themes/' . $blog_theme . '/user_style.php'); ?>
  
  <title><?php echo($blog_config[ 'blog_title' ]); ?> - <?php echo( $lang_string[ 'title' ] ); ?></title>
</head>
  <?php 
    // ------------
    // BEGIN OUTPUT
    // ------------
    theme_pagelayout();
  ?>
</html>
