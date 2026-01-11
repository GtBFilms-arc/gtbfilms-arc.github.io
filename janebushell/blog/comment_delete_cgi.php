<?php
  // ---------------
  // INITIALIZE PAGE
  // ---------------
  require_once('scripts/sb_functions.php');
  global $logged_in;
  $logged_in = logged_in( true, true );

  read_config();

  require_once('languages/' . $blog_config[ 'blog_language' ] . '/strings.php');
  sb_language( 'comments' );
  
  // ---------------
  // POST PROCESSING
  // ---------------
  $admin = $_SESSION[ 'fulladmin' ];
  if (( $logged_in == true) and ( $admin == 'no' ) and ( CheckUserSecurity( $_SESSION[ 'username' ], 'MOD' ) == false ) )
    { redirect_to_url( 'index.php' ); }

  // Delete selected comment file.
  global $ok;
  $ok = false;

  if ( strpos( $_GET[ "comment" ], array( "/", ".", "\\", "%" ) ) === false && strlen( sb_strip_extension($_GET["comment"]) ) == 20 ) {
    if ( strpos( $_GET[ "y" ], array( "/", ".", "\\", "%" ) ) === false && strlen( $_GET["y"] ) == 2 ) {
      if ( strpos( $_GET[ "m" ], array( "/", ".", "\\", "%" ) ) === false && strlen( $_GET["m"] ) == 2 ) {
        if ( strpos( $_GET[ "entry" ], array( "/", ".", "\\", "%" ) ) === false && strlen( $_GET["entry"] ) == 18 ) {
          $ok = delete_comment( CONTENT_DIR . $_GET['y'].'/'.$_GET['m'].'/'.$_GET['entry'].'/comments/'.$_GET["comment"] );
        }
      }
    }
  }

  if ( $ok === true ) {
    $relative_url = 'comments.php?y='.$_GET[ 'y' ].'&m='.$_GET[ 'm' ].'&entry='.$_GET[ 'entry' ];

    if ($_GET[ "sourcepage" ] == 'm') {
      redirect_to_url( 'comments_moderation.php' );
    } else {
      redirect_to_url( $relative_url );
    }
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
      echo $lang_string[ 'error_delete' ] . $ok . '<p />';
    } else {
      echo $lang_string[ 'success_delete' ] . '<p />';
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