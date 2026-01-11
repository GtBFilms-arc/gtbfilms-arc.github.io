<?php
  // ---------------
  // INITIALIZE PAGE
  // ---------------
  require_once('scripts/sb_functions.php');
  global $logged_in;
  $logged_in = logged_in( true, true );
  
  read_config();
  
  require_once('languages/' . $blog_config[ 'blog_language' ] . '/strings.php');
  sb_language( 'trackbacks' );
  
  // ---------------
  // POST PROCESSING
  // ---------------
  
  // Delete selected comment file.
  global $ok;
  $ok = false;
  
  if ( array_key_exists( 'trackback', $_GET ) ) {
    $ok = delete_trackback( $_GET[ 'trackback' ] ); 
  }
  
  if ( $ok === true ) {
    $relative_url = 'trackback.php?y='.$_GET[ 'y' ].'&m='.$_GET[ 'm' ].'&entry='.$_GET[ 'entry' ].'&__mode=html';
    redirect_to_url( $relative_url );
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
    
    if ( $ok !== true ) {
      echo( $lang_string[ 'error_delete' ] . $ok . '<p />');
    } else {
      echo( $lang_string[ 'success_delete' ] . '<p />');
    }
    
    echo( '<a href="index.php">' . $lang_string[ 'home' ] . '</a>' );
    
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
    global $blog_config;
    if ( $blog_config[ 'blog_comments_popup' ] == 1 ) {
      theme_popuplayout();
    } else {
      theme_pagelayout();
    }
  ?>
</html>
