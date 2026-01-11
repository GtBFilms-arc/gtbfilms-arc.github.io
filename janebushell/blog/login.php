<?php
  // ---------------
  // INITIALIZE PAGE
  // ---------------
  require_once('scripts/sb_functions.php');
  global $logged_in;
  $logged_in = logged_in( false, true );
  
  read_config();
  
  require_once('languages/' . $blog_config[ 'blog_language' ] . '/strings.php');
  sb_language( 'login' );
  
  // ---------------
  // POST PROCESSING
  // ---------------
  
  // ------------
  // PAGE CONTENT
  // ------------
  function page_content() {
    global $lang_string, $logged_in, $theme_vars, $blog_theme;
    
    // SUBJECT
    $entry_array = array();
    $entry_array[ 'subject' ] = $lang_string[ 'title' ];
      
    // PAGE CONTENT BEGIN
    ob_start(); ?>    
    
    <?php echo( $lang_string[ 'instructions' ] ); ?><p />
    
    <hr />
    
    <form action="login_cgi.php" method="post" onsubmit="return validate(this)">
      <label for="user"><?php echo( $lang_string[ 'username' ] ); ?></label><br />
      <input type="text" name="user" size="40"><p />
      
      <label for="pass"><?php echo( $lang_string[ 'password' ] ); ?></label><br />
      <input type="password" name="pass" size="40"><p />
      
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
    if (theform.user.value=="" || theform.pass.value=="") {
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
