<?php
  // ---------------
  // INITIALIZE PAGE
  // ---------------
  require_once('scripts/sb_functions.php');
  global $logged_in;
  $logged_in = logged_in( false, true );
  
  read_config();
  
  require_once('languages/' . $blog_config[ 'blog_language' ] . '/strings.php');
  sb_language( 'search' );
  
  // ---------------
  // POST PROCESSING
  // ---------------
  
  global $search_string;
  $search_string = $_GET[ 'q' ];
  
  // ------------
  // PAGE CONTENT
  // ------------
  function page_content() {
    global $lang_string, $blog_config, $search_string;
    
    // SUBJECT
    $entry_array = array();
    $entry_array[ 'subject' ] = $lang_string[ 'title' ];
    
    // PAGE CONTENT BEGIN
    ob_start();
    
    echo ( str_replace( '%string', @htmlspecialchars( $search_string, ENT_QUOTES, $lang_string[ 'php_charset' ] ), $lang_string[ 'instructions' ] ) . '<br />' );
    
    echo( '<hr />' );
      
    $output = search( $search_string, @$_GET[ 'n' ] );
    
    if ( $output ) {
      echo ( $output );
    } else {
      echo( $lang_string[ 'not_found' ] );
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
  
  <title><?php echo($blog_config[ 'blog_title' ]); ?></title>
</head>
  <?php 
    // ------------
    // BEGIN OUTPUT
    // ------------
    theme_pagelayout();
  ?>
</html>
