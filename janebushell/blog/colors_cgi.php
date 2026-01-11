<?php
  // ---------------
  // INITIALIZE PAGE
  // ---------------
  require_once('scripts/sb_functions.php');
  global $logged_in;
  $logged_in = logged_in( true, true );
  
  read_config();
  
  require_once('languages/' . $blog_config[ 'blog_language' ] . '/strings.php');
  sb_language( 'colors' );
  
  // ---------------
  // POST PROCESSING
  // ---------------
  $color_def = theme_default_colors();
  $post_array = array();
  array_push( $post_array, 'name' );
  if ( array_key_exists( 'save_btn', $_POST ) == true && $_POST[ 'scheme_name' ] != '' && $_POST[ 'scheme_file' ] != '' ) {
    $str = str_replace( '|', ':', sb_stripslashes( $_POST[ 'scheme_name' ] ) );
    array_push( $post_array, $str );
  } else {
    array_push( $post_array, 'custom' );
  }
  
  for ( $i = 0; $i < count( $color_def ); $i++ ) {
    $id = $color_def[$i][ 'id' ];
    $color = sb_stripslashes( $_POST[ $id ] );
    array_push( $post_array, $id );
    array_push( $post_array, $color );
  }
  
  // Check if we should save color scheme, or just update colors on web site.
  if ( array_key_exists( 'save_btn', $_POST ) == true && $_POST[ 'scheme_name' ] != '' && $_POST[ 'scheme_file' ] != '' ) {
    $filename = sb_stripslashes( $_POST[ 'scheme_file' ] );
    $filename = preg_replace( '/(\s|\\\|\/|%|#)/', '_', $filename ); // Replace whitespaces [\n\r\f\t ], slashes, % and # with _
    $ok = write_colors( $post_array, $filename );
  } else {
    $ok = write_colors( $post_array, NULL );
  }
  
  if ( $ok === true ) {
    redirect_to_url( 'colors.php' );
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
      echo( $lang_string[ 'error' ] . $ok . '<p />' );
    } else {
      echo $lang_string[ 'success' ] . '<p />';
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
