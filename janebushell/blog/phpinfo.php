<?php
  // ---------------
  // INITIALIZE PAGE
  // ---------------
  require_once('scripts/sb_functions.php');
  global $logged_in;
  $logged_in = logged_in( true, true );
  
  read_config();
  
  require_once('languages/' . $blog_config[ 'blog_language' ] . '/strings.php');
  sb_language( 'info' );

  // ---------------
  // POST PROCESSING
  // ---------------

  // -----------
  // PAGE CONTENT
  // -----------

  // ----
  // HTML
  // ----
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=<?php echo( $lang_string[ 'html_charset' ] ); ?>" />

  <link rel="stylesheet" type="text/css" href="themes/<?php echo( $blog_theme ); ?>/style.css" />
  <?php require_once('themes/' . $blog_theme . '/user_style.php'); ?>
  <title><?php echo( $blog_config[ 'blog_title' ] ); ?> - <?php echo( 'PHP Version: ' . phpversion() ); ?></title>
</head>
  <?php 
    phpinfo();
  ?>
</html>
