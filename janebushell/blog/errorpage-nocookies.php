<?php
  require_once('scripts/sb_functions.php');
  global $logged_in;
  $logged_in = logged_in( false, true );

  read_config();

  require_once('languages/' . $blog_config[ 'blog_language' ] . '/strings.php');
  sb_language( 'errorpage-nocookies' );

  // ---------------
  // POST PROCESSING
  // ---------------

  // ------------
  // PAGE CONTENT
  // ------------
  function page_content() {
    global $lang_string, $blog_config, $blog_theme;

    // SUBJECT
    $entry_array = array();
    $entry_array[ 'subject' ] = $lang_string[ 'title' ];
    $entry_array[ 'entry' ] = '<table width="100%"  border="0"><tr><td><img src="themes/' . $blog_theme . '/images/error_icon.png" alt="" border="0" /></td>';
    $entry_array[ 'entry' ]  .= '<td>' . $lang_string[ 'errorline1' ] . '<br><br>';
    $entry_array[ 'entry' ]  .= $lang_string[ 'errorline2' ] . '<br><br>';
    $entry_array[ 'entry' ]  .= $lang_string[ 'clientid' ] . @gethostbyaddr(getIP()) . '</td></tr></table>';

    // THEME ENTRY
    echo( theme_staticentry( $entry_array ) );
  }

  // ----
  // HTML
  // ----
?>
  <?php echo( get_init_code() ); ?>
  <?php require_once('themes/' . $blog_theme . '/user_style.php'); ?>

  <title><?php echo($blog_config[ 'blog_title' ]); ?></title>
</head>
  <?php
    // ------------
    // BEGIN OUTPUT
    // ------------
    theme_pagelayout();
  ?>
</html>
