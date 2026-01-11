<?php
  // ---------------
  // INITIALIZE PAGE
  // ---------------
  require_once('scripts/sb_functions.php');

  global $logged_in;
  $logged_in = logged_in( false, true );
  
  if ( !session_id() ) {
    session_start();
  }
  $_SESSION[ 'capcha_contact' ] = sb_get_capcha();
  
  read_config();
  
  require_once('languages/' . $blog_config[ 'blog_language' ] . '/strings.php');
  sb_language( 'contact' );
  
  // ---------------
  // POST PROCESSING
  // ---------------
  
  // The user must have cookies enabled in order to send contacts - this helps with blank emails
  if (!isset($_SESSION['cookies_enabled'])) {
    redirect_to_url('errorpage-nocookies.php');
    // header('location: http://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']).'errorpage-nocookies.php');
  }
  
  // ------------
  // PAGE CONTENT
  // ------------
  function page_content() {
    global $lang_string, $blog_config, $theme_vars; 
    
    // SUBJECT
    $entry_array = array();
    $entry_array[ 'subject' ] = $lang_string[ 'title' ];
    
    // PAGE CONTENT BEGIN
    ob_start(); ?>
    
    <?php echo ( $lang_string[ 'instructions' ] ); ?><p />
    <form action="contact_cgi.php" method="post" onsubmit="return validate(this)">
  
    <label for="name"><?php echo( $lang_string[ 'name' ] ); ?></label><br />
    <input type="text" name="name" id="name" size="40" /><br /><br />
    <label for="email"><?php echo( $lang_string[ 'email' ] ); ?></label><br />
    <input type="text" name="email" id="email" size="40" /><br /><br />
    <label for="subject"><?php echo( $lang_string[ 'subject' ] ); ?></label><br />
    <input type="text" name="subject" id="subject" size="40" /><br /><br />
    <label for="text"><?php echo( $lang_string[ 'comment' ] ); ?></label><br />
    <textarea style="width: <?php global $theme_vars; echo( $theme_vars[ 'max_image_width' ] ); ?>px;" id="text" name="comment" rows="20" cols="50" autocomplete="OFF"></textarea><br /><br />
    
    <?php
    if ( $blog_config['blog_enable_capcha'] == 0 ) {
      echo('<!-- Anti-spam disabled -->');
      echo('<input type="hidden" name="capcha_contact" id="capcha_contact" value="' . $_SESSION[ 'capcha_contact' ] . '" autocomplete="OFF" maxlength="6" /><br /><br />'); 
    } else {
      echo('<label for="capcha_contact">');
      if ( function_exists('imagecreate') && $blog_config[ 'blog_enable_capcha_image' ] ) {
        echo ( $lang_string[ 'contact_capcha' ] . '<br /><img src="capcha.php?entry=contact" />' );
      } else {
        echo ( $lang_string[ 'contact_capcha' ] . '<b>' . sb_str_to_ascii( $_SESSION[ 'capcha_contact' ] ) . '</b>' );
      }
      echo('</label><br />');
      echo('<input type="text" name="capcha_contact" id="capcha_contact" value="" autocomplete="OFF" maxlength="6" /><br /><br />');
    } 
    ?>
    
    <hr />
  
    <input type="submit" name="submit" value="<?php echo( $lang_string[ 'submit_btn' ] ); ?>" />
    </form>
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
  
  <script type="text/javascript">
  <!--
  function validate(theform) {
    if (theform.subject.value=="" || theform.comment.value=="" || theform.email.value=="") {
      alert("<?php echo( $lang_string[ 'form_error' ] ); ?>");
      return false;
    } else {
      return true;
    }   
  }
  //-->
  </script>
  <title><?php echo($blog_config[ 'blog_title' ]); ?> - <?php echo( $lang_string[ 'title' ] ); ?></title>
</head>
  <?php 
    // ------------
    // BEGIN OUTPUT
    // ------------
    theme_pagelayout();
  ?>
</html>
