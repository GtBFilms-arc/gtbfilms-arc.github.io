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
  sb_language( 'install02' );
  
  // ------------
  // PAGE CONTENT
  // ------------
  function create_folder( $dir ) {
    global $lang_string;
    
    echo( 'Making <b>' . $dir . '</b> folder: ' );
    
    if ( !file_exists( $dir ) ) {
      // Creating Folder
      $oldumask = umask( 0 );
      $ok = mkdir( $dir, 0777 );
      umask( $oldumask );
      
      if ( !file_exists( $dir ) ) {
        // Failed
        echo( '<b style="color: red;">' . $lang_string['folder_failed'] . '</b><br />' );
        return( -1 );
        
      } else {
        // Worked
        echo( '<b style="color: green;">' . $lang_string['folder_success'] . '</b><br />' );
        return( 0 );
      }
      
    } else {
      // Folder Already Exists
        echo( '<b style="color: green;">' . $lang_string['folder_exists'] . '</b><br />' );
      return( 0 );
    }
  }
  
  function page_content() {
    global $lang_string, $blog_config;
    
    // SUBJECT
    $entry_array = array();
    $entry_array[ 'subject' ] = $lang_string[ 'title' ];
    
    // PAGE CONTENT BEGIN
    ob_start();
    
    echo( $lang_string[ 'instructions' ] . '<p />' );
    
    echo( '<hr />' );
    
    $result = create_folder( CONFIG_DIR );
    $result = $result + create_folder( CONTENT_DIR );
    $result = $result + create_folder( IMAGES_DIR );

    // Create a .htaccess file as part of the install process...
    $htaccess_str = "IndexIgnore *

<Files .htaccess>
order allow,deny
deny from all
</Files>

<Files *.txt>
order allow,deny
deny from all
</Files>";

    sb_write_file( CONFIG_DIR.".htaccess", $htaccess_str );
    sb_write_file( CONTENT_DIR.".htaccess", $htaccess_str );
    sb_write_file( IMAGES_DIR.".htaccess", $htaccess_str );
    
    echo( '<hr />' );
    echo( '<br />' );
    
    if ( $result < 0 ) {
      echo( $lang_string['help'] . '<p />' );
      echo( '<a href="install02.php?blog_language=' . $blog_config[ 'blog_language' ] . '">' . $lang_string['try_again'] . '</a><p />' );
    } else {
      echo( $lang_string['success'] . '<p />' );
      echo( '<a href="install03.php?blog_language=' . $blog_config[ 'blog_language' ] . '">' . $lang_string['continue'] . '</a><p />' );
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
  
  <title><?php echo($blog_config[ 'blog_title' ]); ?> - <?php echo( $lang_string[ 'title' ] ); ?></title>
</head>
  <?php 
    // ------------
    // BEGIN OUTPUT
    // ------------
    theme_pagelayout();
  ?>
</html>
