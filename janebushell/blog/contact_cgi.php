<?php
  // ---------------
  // INITIALIZE PAGE
  // ---------------
  require_once('scripts/sb_functions.php');
  global $logged_in;
  $logged_in = logged_in( false, true );
  
  read_config();
  
  require_once('languages/' . $blog_config[ 'blog_language' ] . '/strings.php');
  sb_language( 'contact' );
  
  // -------------
  // POST PROCESSING
  // -------------
  if ( !session_id() ) {
    session_start();
  }
  
  $client_ip_local = getIP();
  
    if (!isset($_SESSION['cookies_enabled'])) {
    redirect_to_url('errorpage-nocookies.php');
    // header('location: http://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']).'errorpage-nocookies.php');
  }
  
  $comment_date = time();
  
  $subject=$lang_string['contactsent'] . $blog_config[ 'blog_title' ];
  $body='<b>' . $lang_string[ 'name' ] . '</b> ' . $_POST[ 'name' ] . '<br />';
  $body .= '<b>' . $lang_string[ 'IPAddress' ] . '</b> ' . $client_ip_local . ' (' . @gethostbyaddr($client_ip_local) .')<br />';
  $body .= '<b>' . $lang_string[ 'useragent' ] . '</b> ' . $_SERVER[ 'HTTP_USER_AGENT' ] . '<br />';
  $body .= '<b>' . $lang_string[ 'email' ] . '</b> ' . $_POST[ 'email' ] . '<br />';
  $body .= '<b>' . $lang_string[ 'subject' ] . '</b> ' . $_POST[ 'subject' ] . '<br /><br />';
  $body .= '<b>' . $lang_string[ 'comment' ] . '</b><br /><br />';
  $body .= sprintf( $lang_string[ 'wrote' ], format_date( $comment_date ), $_POST[ 'name' ], str_replace( "\r\n", "<br />\r\n", $_POST[ 'comment' ] ) );
  $ok=false;
  if ($_POST[ 'capcha_contact' ] == $_SESSION[ 'capcha_contact' ] AND $_SESSION[ 'capcha_contact' ] != '' ) {
    $ok=sb_mail( $_POST[ 'email' ], $blog_config[ 'blog_email' ], $subject, $body, false );
  } else {
    $_SESSION['errornum'] = '403.8';
    $_SESSION['errortype'] = 'error_emailnotsentcapcha';
    redirect_to_url('errorpage.php');
    // header('location: http://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']).'errorpage.php');
  }
  @session_unregister( 'capcha_contact' );
  
  // -----------
  // PAGE CONTENT
  // -----------
  function page_content() {
    global $lang_string, $blog_config, $ok;
    
    // SUBJECT
    $entry_array = array();
    $entry_array[ 'subject' ] = $lang_string[ 'title' ];
    
    // PAGE CONTENT BEGIN
    ob_start();
    
    if ( $ok == true ) { 
      echo( $lang_string[ 'success' ] );
    } else {
      $_SESSION['errornum'] = '403.8';
      $_SESSION['errortype'] = 'error_emailnotsent';
      redirect_to_url('errorpage.php');
      // header('location: http://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']).'errorpage.php');
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
    theme_pagelayout();
  ?>
</html>
