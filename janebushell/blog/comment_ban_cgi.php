<?php
  // ---------------
  // INITIALIZE PAGE
  // ---------------
  require_once('scripts/sb_functions.php');
  global $logged_in;
  $logged_in = logged_in( true, true );
  
  if ( !session_id() ) {
    session_start();
  }

  read_config();

  require_once('languages/' . $blog_config[ 'blog_language' ] . '/strings.php');
  sb_language( 'comments' );
  
  // ---------------
  // POST PROCESSING
  // ---------------
  $admin = $_SESSION[ 'fulladmin' ];
  if (( $logged_in == true) and ( $admin == 'no' ) and ( CheckUserSecurity( $_SESSION[ 'username' ], 'MOD' ) == false ) )
    { redirect_to_url( 'index.php' ); }

  // Verify information being passed
  global $ok;
  $ok = false;
  if (isset( $_GET[ "ban" ] ) ) {
    $ok = add_to_blacklist ( $_GET[ "ban" ] );
  } else {
    $ok = $lang_string[ 'error_noip' ];
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
      echo $lang_string[ 'error_ban' ] . $ok . '<p />';
    } else {
      echo $lang_string[ 'success_ban1' ] . '(' . $_GET[ "ban" ] . ')' . $lang_string[ 'success_ban2' ] . '<p />';
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
