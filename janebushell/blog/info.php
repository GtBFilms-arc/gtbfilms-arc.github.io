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
  if ( array_key_exists( 'info_keywords', $_POST ) && array_key_exists( 'info_description', $_POST ) && array_key_exists( 'info_copyright', $_POST ) ) {
    global $ok;
    $ok = write_metainfo( sb_stripslashes( $_POST[ 'info_keywords' ] ),
                sb_stripslashes( $_POST[ 'info_description' ] ),
                sb_stripslashes( $_POST[ 'info_copyright' ] ),
                $_POST[ 'tracking_code' ] );
                
    if ( $ok === true ) { 
      redirect_to_url( 'index.php' );
    }
  }
  
  // -----------
  // PAGE CONTENT
  // -----------
  function page_content() {
    global $lang_string, $blog_config, $theme_vars, $blog_theme;
  
    // SUBJECT
    $entry_array = array();
    $entry_array[ 'subject' ] = $lang_string[ 'title' ];
    
    // PAGE CONTENT BEGIN
    ob_start();
    
    if ( array_key_exists( "info_keywords", $_POST ) && array_key_exists( "info_description", $_POST ) && array_key_exists( "info_copyright", $_POST ) ) {  
      // Check to see if we're posting data...
      global $ok;
      if ( $ok !== true ) {
        echo( $lang_string[ 'error' ] . $ok . '<p />' );
      } else {
        echo( $lang_string[ 'success' ] . '<p />' );
      }
      echo( '<a href="index.php">' . $lang_string[ 'home' ] . '</a>' );
    } else {
      ?>
      
      <?php echo( $lang_string[ 'instructions' ] ); ?><p />
      
      <form action="info.php" method="post" name="info" name="info">
        
        <label for="info_keywords"><?php echo( $lang_string[ 'info_keywords' ] ); ?></label><br />
        <textarea style="width: <?php global $theme_vars; echo( $theme_vars[ 'max_image_width' ] ); ?>px;" id="text" name="info_keywords" rows="5" cols="50" autocomplete="OFF"><?php echo($blog_config[ 'info_keywords' ]); ?></textarea><br /><br />
        
        <label for="info_description"><?php echo( $lang_string[ 'info_description' ] ); ?></label><br />
        <textarea style="width: <?php global $theme_vars; echo( $theme_vars[ 'max_image_width' ] ); ?>px;" id="text" name="info_description" rows="5" cols="50" autocomplete="OFF"><?php echo($blog_config[ 'info_description' ]); ?></textarea><br /><br />
        
        <label for="info_copyright"><?php echo( $lang_string[ 'info_copyright' ] ); ?></label><br />
        <textarea style="width: <?php global $theme_vars; echo( $theme_vars[ 'max_image_width' ] ); ?>px;" id="text" name="info_copyright" rows="5" cols="50" autocomplete="OFF"><?php echo($blog_config[ 'info_copyright' ]); ?></textarea><br /> <br />

        <label for="tracking_code"><?php echo( $lang_string[ 'tracking_code' ] ); ?></label><br />
        <textarea style="width: <?php global $theme_vars; echo( $theme_vars[ 'max_image_width' ] ); ?>px;" id="text" name="tracking_code" rows="5" cols="50" autocomplete="OFF"><?php echo($blog_config[ 'tracking_code' ]); ?></textarea><br />

        <hr />

        <input type="submit" name="submit" value="<?php echo( $lang_string[ 'submit_btn' ] ); ?>" />
      </form>
      
      <?php 
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
  
  <script type="text/javascript">
    <!--
    function validate(theform) {
      if (theform.blog_title.value=="" || theform.blog_author.value=="" || theform.blog_footer.value=="" ) {
        alert("<?php echo( $lang_string[ 'form_error' ] ); ?>");
        return false;
      } else {
        return true;
      }
    }
    //-->
  </script>
  <title><?php echo( $blog_config[ 'blog_title' ] ); ?> - <?php echo( $lang_string[ 'title' ] ); ?></title>
</head>
  <?php 
    // ------------
    // BEGIN OUTPUT
    // ------------
    theme_pagelayout();
  ?>
</html>
