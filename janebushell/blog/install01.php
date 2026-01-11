<?php
  // ---------------
  // INITIALIZE PAGE
  // ---------------
  require_once('scripts/sb_functions.php');
  global $logged_in;
  $logged_in = logged_in( true, false );
  
  read_config();
  
  // ---------------
  // POST PROCESSING
  // ---------------
  
  // Validate Language
  $temp_lang = '';
  if ( isset( $_POST['blog_language'] ) ) {
    $temp_lang = sb_stripslashes( $_POST['blog_language'] );
  } else if ( array_key_exists( 'blog_language', $_GET ) ) {  
    $temp_lang = sb_stripslashes( $_GET['blog_language'] );
  }
  if (validate_language($temp_lang) == false) {
    $temp_lang = 'english';
  }
  
  global $blog_config;
  $blog_config[ 'blog_language' ] = $temp_lang;
  
  require_once('languages/' . $blog_config[ 'blog_language' ] . '/strings.php');
  sb_language( 'install01' );
  
  // ------------
  // PAGE CONTENT
  // ------------
  function page_content() {
    global $lang_string, $blog_config;
    
    // SUBJECT
    $entry_array = array();
    $entry_array[ 'subject' ] = $lang_string[ 'title' ];
    
    // PAGE CONTENT BEGIN
    ob_start();
    
    echo( $lang_string[ 'instructions' ] . '<p />' );
    
    echo( '<a href="install02.php?blog_language=' . $blog_config[ 'blog_language' ] . '">' . $lang_string['begin'] . '</a><p />' );
    
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
