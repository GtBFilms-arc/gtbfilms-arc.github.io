<?php
  // ---------------
  // INITIALIZE PAGE
  // ---------------
  require_once('scripts/sb_functions.php');
  global $logged_in;
  $logged_in = logged_in( true, true );
  
  read_config();
  
  // require_once('languages/' . $blog_config[ 'blog_language' ] . '/strings.php');
  // sb_language( 'index' );
  
  // ---------------
  // POST PROCESSING
  // ---------------
  
  global $pages;
      
  $pages = array();
  array_push( $pages, 'add' );
  array_push( $pages, 'add_block' );
  array_push( $pages, 'add_link' );
  array_push( $pages, 'add_static' );
  array_push( $pages, 'archives' );
  array_push( $pages, 'index' );
  array_push( $pages, 'categories' );
  array_push( $pages, 'colors' );
  array_push( $pages, 'comments' );
  array_push( $pages, 'contact' );
  array_push( $pages, 'delete' );
  array_push( $pages, 'delete_static' );
  array_push( $pages, 'emoticons' );
  array_push( $pages, 'errorpage-nocookies' );
  array_push( $pages, 'errorpage' );
  array_push( $pages, 'image_list' );
  array_push( $pages, 'info' );
  array_push( $pages, 'install00' );
  array_push( $pages, 'install01' );
  array_push( $pages, 'install02' );
  array_push( $pages, 'install03' );
  array_push( $pages, 'install04' );
  array_push( $pages, 'install05' );
  array_push( $pages, 'install06' );
  array_push( $pages, 'login' );
  array_push( $pages, 'logout' );
  array_push( $pages, 'options' );
  array_push( $pages, 'rating' );
  array_push( $pages, 'search' );
  array_push( $pages, 'set_login' );
  array_push( $pages, 'setup' );
  array_push( $pages, 'static' );
  array_push( $pages, 'stats' );
  array_push( $pages, 'themes' );
  array_push( $pages, 'trackbacks ' );
  array_push( $pages, 'upload_img' );
  array_push( $pages, 'forms' );
  
  if ( array_key_exists( 'blog_language1', $_POST ) ) {
  
    // Store all the data from language 1
    require_once('languages/' . $_POST[ 'blog_language1' ] . '/strings.php');
    
    $result_array = Array();
    for ( $i=0; $i < count( $pages ); $i++ ) {
      $lang_string = NULL;
      sb_language($pages[ $i ]);
      array_push( $result_array, implode( ',', array_keys( $lang_string ) ) );
    }
    $str = implode( '|', $result_array );
    
    sb_write_file( CONFIG_DIR.'temp_language1.txt', $str );
    redirect_to_url( 'languages_cgi.php?store_data=1&lang2=' . $_POST[ 'blog_language2' ] . '&lang1=' . $_POST[ 'blog_language1' ] );
    
  } else {
    if ( array_key_exists( 'store_data', $_GET ) ) {
    
      // Store all the data from language 2
      require_once('languages/' . $_GET[ 'lang2' ] . '/strings.php');
      
      $result_array = Array();
      for ( $i=0; $i < count( $pages ); $i++ ) {
        $lang_string = NULL;
        sb_language($pages[ $i ]);
        array_push( $result_array, implode( ',', array_keys( $lang_string ) ) );
      }
      $str = implode( '|', $result_array );
      
      sb_write_file( CONFIG_DIR.'temp_language2.txt', $str );
      redirect_to_url( 'languages_cgi.php?display_results=1&lang2=' . $_GET[ 'lang2' ] . '&lang1=' . $_GET[ 'lang1' ] );
      
    } else {
    
      // Display the results (see below in the page_content() function...)
      require_once('languages/' . $blog_config[ 'blog_language' ] . '/strings.php');
      sb_language( 'index' );
    }
  }
  
  // ------------
  // PAGE CONTENT
  // ------------
  function page_content() {
    global $lang_string, $user_colors;
    
    $lang_string[ 'title' ] = "Results";
    $lang_string[ 'instructions' ] = "Here are your results.";
  
    // SUBJECT
    $entry_array = array();
    $entry_array[ 'subject' ] = $lang_string[ 'title' ];
    
    // PAGE CONTENT BEGIN
    ob_start();
     
    // echo( "<h2>" . $lang_string[ 'title' ] . "</h2>" );
    echo( $lang_string[ 'instructions' ] . "<p />" );
    
    global $pages;
  
    ?>
    <hr />
    
    <?php 
    if ( array_key_exists( "display_results", $_GET ) ) {
    
      // Recall all the data
      $lang1_string = sb_read_file( CONFIG_DIR."temp_language1.txt" );
      $lang2_string = sb_read_file( CONFIG_DIR."temp_language2.txt" );
      
      $lang1_array = explode( "|", $lang1_string );
      $lang2_array = explode( "|", $lang2_string );
      
      $pages = array();
      array_push( $pages, 'add' );
      array_push( $pages, 'add_block' );
      array_push( $pages, 'add_link' );
      array_push( $pages, 'add_static' );
      array_push( $pages, 'archives' );
      array_push( $pages, 'index' );
      array_push( $pages, 'categories' );
      array_push( $pages, 'colors' );
      array_push( $pages, 'comments' );
      array_push( $pages, 'contact' );
      array_push( $pages, 'delete' );
      array_push( $pages, 'delete_static' );
      array_push( $pages, 'emoticons' );
      array_push( $pages, 'errorpage-nocookies' );
      array_push( $pages, 'errorpage' );
      array_push( $pages, 'image_list' );
      array_push( $pages, 'info' );
      array_push( $pages, 'install00' );
      array_push( $pages, 'install01' );
      array_push( $pages, 'install02' );
      array_push( $pages, 'install03' );
      array_push( $pages, 'install04' );
      array_push( $pages, 'install05' );
      array_push( $pages, 'install06' );
      array_push( $pages, 'login' );
      array_push( $pages, 'logout' );
      array_push( $pages, 'options' );
      array_push( $pages, 'rating' );
      array_push( $pages, 'search' );
      array_push( $pages, 'set_login' );
      array_push( $pages, 'setup' );
      array_push( $pages, 'static' );
      array_push( $pages, 'stats' );
      array_push( $pages, 'themes' );
      array_push( $pages, 'trackbacks ' );
      array_push( $pages, 'upload_img' );
      array_push( $pages, 'forms' );
      
      // Compare
      for ( $i = 0; $i < count( $pages ); $i++ ) {
        $lang1_keys = explode( ",", $lang1_array[ $i ] );
        $lang2_keys = explode( ",", $lang2_array[ $i ] );
        
        echo( "Verifying <b>\"" . $pages[ $i ] . "\"</b> ( " . count( $lang1_keys ) . " items / " . count( $lang1_keys ) . " items )" );
        $ok = true;
        for ( $j = 0; $j < count( $lang1_keys ); $j++ ) {
          if ( in_array( $lang1_keys[ $j ], $lang2_keys ) ) {
            // Key is in the array
          } else {
            // Key is not in the array
            echo( "\n<br/>&nbsp;&nbsp;Missing <b>\"" . $lang1_keys[ $j ] . "\"</b> from <b>\"" . $_GET[ "lang2" ] . "\"</b>" );
            $ok = false;
          }
        }
        
        for ( $j = 0; $j < count( $lang2_keys ); $j++ ) {
          if ( in_array( $lang2_keys[ $j ], $lang1_keys ) ) {
            // Key is in the array
          } else {
            // Key is not in the array
            echo( "\n<br/>&nbsp;&nbsp;Missing <b>\"" . $lang2_keys[ $j ] . "\"</b> from <b>\"" . $_GET[ "lang1" ] . "\"</b>" );
            $ok = false;
          }
        }
        
        if ( $ok ) {
          echo( " <b>(ok)</b>\n" );
        }
        
        echo( "<br /><br />\n" );
        
      }
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
