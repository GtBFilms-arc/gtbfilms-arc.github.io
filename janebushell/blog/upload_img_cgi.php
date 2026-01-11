<?php
  // ---------------
  // INITIALIZE PAGE
  // ---------------
  require_once('scripts/sb_functions.php');
  global $logged_in;
  $logged_in = logged_in( true, true );
  
  read_config();
  
  require_once('languages/' . $blog_config[ 'blog_language' ] . '/strings.php');
  sb_language( 'upload_img' );
  
  // ---------------
  // POST PROCESSING
  // ---------------
  
  for ($i=0;$i<count($_FILES['userfile']);$i++) {
  
    if ($ok == null) {
      $ok = false;
    }
    
    if (is_uploaded_file($_FILES['userfile']['tmp_name'][$i])) {
      if ( $_FILES[ 'userfile' ][ 'error' ][$i] == 0 ) {
        if (!file_exists(IMAGES_DIR)) {
          $oldumask = umask(0);
          @mkdir(IMAGES_DIR, 0777 );
          @umask($oldumask);
        }
              
        $uploaddir = IMAGES_DIR;
        $uploadfile = $uploaddir . preg_replace("/ /","_",$_FILES['userfile']['name'][$i]);
        
        if ( @getimagesize($_FILES['userfile']['tmp_name'][$i]) == FALSE ){
          echo('Image is not valid or not an image file.');
          exit;
        }
        
        if (strpos($uploadfile, ".") === false) {
          echo('File does not have an extension');
          exit;
        }
        
        if (strpos($uploadfile, ".") == 0) {
          echo('File begins with "."');
          exit;
        }
        
        if (strrpos($uploadfile, ".") == strlen($uploadfile)-1) {
          echo('File ends with "."');
          exit;
        }
        
        $extension = strtolower(substr(strrchr($uploadfile, "."), 1));
        
        if (strlen($extension) == 0) { // Not really needed...
          echo('File ends with "." and does not have an extension');
          exit;
        }
        
        // Allowed files
        $upload_valid_extentions = array( "jpg", "gif", "png" );
        $extension = strtolower(substr(strrchr($uploadfile, "."), 1));
        if (!in_array($extension, $upload_valid_extentions)) {
          echo('That filetype is not allowed');
          exit;
        }
        
        // Explicitly denied files (we don't really need this anymore...) - provided by ReZEN (rezen@xorcrew.net)
        $upload_denied_extentions = array( "exe", "pl", "php", "php3", "php4", "php5", "phps", "asp","cgi", "html", "htm", "dll", "bat", "cmd" );
        foreach ($upload_denied_extentions AS $denied_extention) {
          if($denied_extention == $extension) {
            echo('That filetype is not allowed');
            exit;
          }    
        }
    
        if ( move_uploaded_file($_FILES['userfile']['tmp_name'][$i], $uploadfile ) ) {
          chmod( $uploadfile, 0777 );
          $ok = true;
        } else {
          $ok = false;
        }
      }
    }
  }
  if ( $ok === true ) {
    redirect_to_url( 'index.php' );
  }
  // ------------
  // PAGE CONTENT
  // ------------
  function page_content() {
    global $lang_string, $user_colors;
    if ( $ok !== true ) {
      echo( $lang_string[ 'error' ] . $ok . '<p />' );
    }
    //echo(count($_FILES['userfile']));
    //print_r($_FILES['userfile']);
    //echo($_FILES['userfile']['name'][0]);
    echo( '<a href="index.php">' . $lang_string[ 'home' ] . '</a><br /><br />' );
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
