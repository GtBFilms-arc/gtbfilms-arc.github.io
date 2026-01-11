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
  
  // ------------
  // PAGE CONTENT
  // ------------
  function page_content() {
    global $lang_string, $blog_config;
    
    // SUBJECT
    $entry_array = array();
    $entry_array[ 'subject' ] = $lang_string[ 'title' ];
      
    // PAGE CONTENT BEGIN
    ob_start(); ?>

    <?php
    $formstate = $_REQUEST['formstate'];
    if($formstate !="showuploaders"){
echo <<<EOF
    <form action="upload_img.php" method="get">
      How many images do you wish to upload? <select id="howmany" name="howmany">
EOF;
    for($i=1;$i<=10;$i++){
      echo('<option value="'.$i.'">'.$i.'</option>');
    }
echo <<<EOF
  </select>
  <input name="formstate" type="hidden" value="showuploaders" />
  <input type="submit" value="next &raquo;" />
    </form>
EOF;
    } else {
      $howmany = $_REQUEST['howmany'];
      $formstate = "notshowuploaders";
      
      echo( $lang_string[ 'instructions' ] );
      echo("<p />");
    
      echo('<form enctype="multipart/form-data" action="upload_img_cgi.php" method="POST">');
      echo( $lang_string[ 'select_file' ] );
      echo("<br /><br />");
      for($i=1;$i<=$howmany;$i++){
        echo("<input name=\"userfile[]\" type=\"file\"><br />");      
      }
      echo("<input name=\"howmany\" type=\"hidden\" value=\"$howmany\" />");
      echo("<input type=\"submit\" value=\"".$lang_string[ 'upload_btn' ]."\">"); 
      echo("</form>");
    }   
    ?>
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
  
  <title><?php echo($blog_config[ 'blog_title' ]); ?> - <?php echo( $lang_string[ 'title' ] ); ?></title>
</head>
  <?php 
    // ------------
    // BEGIN OUTPUT
    // ------------
    theme_pagelayout();
  ?>
</html>
