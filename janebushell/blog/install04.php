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
  sb_language( 'install04' );
  
  // $ok = create_password( sb_stripslashes( $_POST['user'] ), sb_stripslashes( $_POST['pass'] ) );
  // $logged_in = $ok;
  
  // ------------
  // PAGE CONTENT
  // ------------
  function page_content() {
    global $lang_string, $ok, $blog_config;
    
    // SUBJECT
    $entry_array = array();
    $entry_array[ 'subject' ] = $lang_string[ 'title' ];
    
    // PAGE CONTENT BEGIN
    ob_start();
    
    echo( $lang_string[ 'instructions' ] . '<p />' );
    
    $hashedUser = crypt( sb_stripslashes( $_POST['user'] ) );
    $hashedPass = crypt( sb_stripslashes( $_POST['pass'] ) );
    ?>
    
    <label for="phpfile"><?php echo( $lang_string['code'] ); ?></label><br />
<textarea style="width: <?php global $theme_vars; echo( $theme_vars[ 'max_image_width' ] ); ?>px;" name="phpfile" rows="6" cols="40">&lt;?php
  // Save file as 'password.php' and FTP it into the 'config' directory.
  $username = '<?php echo( $hashedUser ); ?>';
  $password = '<?php echo( $hashedPass ); ?>';
?&gt;</textarea>
    
    <?php 
    echo( '<p />' );
    echo( '<a href="install05.php?blog_language=' . $blog_config[ 'blog_language' ] . '">' . $lang_string['continue'] . '</a><p />' );
    echo( $lang_string['information'] );
    echo( '<p />' );
    
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
