<?php
  // ---------------
  // INITIALIZE PAGE
  // ---------------
  require_once('scripts/sb_functions.php');
  global $logged_in;
  $logged_in = logged_in( true, true );

  // Create a session for the anti-spam cookie
  if ( !session_id() ) {
    session_start();
  }

  // Read configuration file
  read_config();

  // Load language strings
  require_once('languages/' . $blog_config[ 'blog_language' ] . '/strings.php');
  sb_language( 'comment_moderation' );
  
  // ---------------
  // POST PROCESSING
  // ---------------
  
  // ------------
  // PAGE CONTENT
  // ------------
  function page_content() {
    global $lang_string, $user_colors, $logged_in, $theme_vars, $blog_theme, $blog_config;

    // SUBJECT
    $entry_array = array();
    $entry_array[ 'subject' ] = $lang_string[ 'title' ];

    // PAGE CONTENT BEGIN
    ob_start(); ?>

    <?php echo( $lang_string[ 'instructions' ] ); ?><p />

    <?php echo( read_unmodded_comments($logged_in) ); ?><p />

    <?php
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
<?php
  echo( '<title>' . $blog_config[ 'blog_title' ] . ' - ' . get_entry_title( substr( $_GET[ 'entry' ], 5, 2 ), substr ( $_GET[ 'entry' ], 7, 2 ), $_GET[ 'entry' ] ) . '</title>');
?>
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
