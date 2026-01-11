<?php
  // ---------------
  // INITIALIZE PAGE
  // ---------------
  require_once('scripts/sb_functions.php');
  global $logged_in;
  $logged_in = logged_in( true, true );
  
  read_config();
  
  require_once('languages/' . $blog_config[ 'blog_language' ] . '/strings.php');
  sb_language( 'add' );
  
  // ---------------
  // POST PROCESSING
  // ---------------
  global $ok;
  if ( array_key_exists( 'no', $_POST ) || array_key_exists( 'yes', $_POST ) ) {
  
    // ---------
    //  TRACKBACK
    // ---------
    if ( array_key_exists( 'no', $_POST ) ) {
      // User clicked the "Cancel" button
      redirect_to_url( 'index.php' );
      
    } else {
      // User clicked the "OK" button
      global $auto_discovery_confirm;
         
      if ( array_key_exists( 'yes', $_POST ) ) {
        $ad_array = $_POST[ 'ad_array' ];
        foreach ($_POST[ 'confirm' ] as $name => $value) {
          sb_tb_ping ( $ad_array[$name], $_POST[ 'title' ], $_POST[ 'permalink' ], $_POST[ 'excerpt' ] );
        }
        redirect_to_url( 'index.php' );
      }
    }
    
  } else {
  
    // -------------
    // ADD / EDIT ENTRY
    // -------------
    global $auto_discovery_confirm;
    
    // If editing an entry, store old entry date...
    $temp_date = substr($_POST['entry'],-13,6);
    $temp_time = substr($_POST['entry'],-6,6);
    $dd = substr($temp_date,-2,2);
    $mt = substr($temp_date,-4,2);
    $yy = substr($temp_date,-6,2);
    if ($yy >= 95) {
      $yy = '19' . $yy;
    } else {
      $yy = '20' . $yy;
    }
    $hh = substr($temp_time,-6,2);
    $mm = substr($temp_time,-4,2);
    $ss = substr($temp_time,-2,2);
    
    $oldtime = mktime($hh, $mm, $ss, $mt, $dd, $yy );
    $newtime = mktime($_POST['hour'], $_POST['minute'], $_POST['second'], $_POST['month'], $_POST['day'], $_POST['year'] );
    
    $ok = false;
    if ( $oldtime != $newtime ) {
      // Different date
      $entry = CONTENT_DIR.$_POST['y'].'/'.$_POST['m'].'/'.$_POST['entry'];
      if ( file_exists( $entry . ".txt" ) ) {
        $filename = $entry . ".txt";
      } elseif ( file_exists( $entry . ".txt.gz" ) ) {
        $filename = $entry . ".txt.gz";
      }
      
      // Move Assoicated Files
      move_entry($oldtime,$newtime);
      
      // Create New Entry
      $ok = write_entry( sb_stripslashes( $_POST[ 'blog_subject' ] ), sb_stripslashes( $_POST[ 'blog_text' ] ), sb_stripslashes( $_POST[ 'tb_ping' ] ), NULL, $_POST[ 'catlist' ], sb_stripslashes( $_POST[ 'blog_relatedlink' ] ), $newtime );
      
      // Delete Old Entry
      sb_delete_file($filename);
    } else {
      $entry = CONTENT_DIR.$_POST['y'].'/'.$_POST['m'].'/'.$_POST['entry'];
    
      // Update Entry
      $ok = write_entry( sb_stripslashes( $_POST[ 'blog_subject' ] ), sb_stripslashes( $_POST[ 'blog_text' ] ), sb_stripslashes( $_POST[ 'tb_ping' ] ), $entry, $_POST[ 'catlist' ], sb_stripslashes( $_POST[ 'blog_relatedlink' ] ), $oldtime );
    }
    
    if ( $ok === true ) {
    
       if( strlen($auto_discovery_confirm[ 'text' ]) > 0 ) {
         // Find the trackback URLs
         $ad_array = trackback_autodiscover( $auto_discovery_confirm[ 'text' ] );
        
        // Is there anything to be confirmed?
        if ( is_array( $ad_array ) ) {
           if ( count( $ad_array ) === 0 ) {
            redirect_to_url( 'index.php' );
           }
        } else {
          redirect_to_url( 'index.php' );
        }
       } else {
        redirect_to_url( 'index.php' );
       }
    }
   }
  
  // ------------
  // PAGE CONTENT
  // ------------
  function page_content() {
    global $lang_string, $blog_config, $ad_array, $auto_discovery_confirm;
    
    // SUBJECT
    $entry_array = array();
    $entry_array[ 'subject' ] = $lang_string[ 'title' ];
    
    // PAGE CONTENT BEGIN
    ob_start();
    
    if ( array_key_exists( 'no', $_POST ) || array_key_exists( 'yes', $_POST ) || $ok == false ) {
      // Display error message.
      global $ok;
      if ( $ok !== true ) {
        echo( $lang_string[ 'error' ] . $ok . '<p />' );
      } else {
        echo( $lang_string[ 'success' ] . '<p />' );
      }
      echo( '<a href="index.php">' . $lang_string[ 'home' ] . '</a><br /><br />' );
    } else {
      // Display Trackback confirmation.
      ?>
      
      <h2><?php echo( $lang_string[ 'title_ad' ] ); ?></h2>
      <?php echo( $lang_string[ 'instructions_ad' ] ); ?><p />
      
      <hr />
      
      <form action='add_cgi.php' method="post">
      
        <?php
         if ( is_array( $ad_array ) ) {
          echo "<table width=\"100%\">";
           for ( $k = 0; $k < count( $ad_array ); $k++ ) {
            echo "<tr><td><input type=\"checkbox\" name=\"confirm[$k]\" checked></td>\n";
            echo "<td>" . $ad_array[$k];
             echo "<input type=\"hidden\" name=\"ad_array[$k]\" value=\"" . $ad_array[$k] . "\">\n";
            echo "</td></tr>\n";
           }
           echo "</table><br />\n";
          }
        ?>
        
        <input type="hidden" name="title" value="<?php echo( $auto_discovery_confirm[ 'title' ] ); ?>" />
        <input type="hidden" name="permalink" value="<?php echo( $auto_discovery_confirm[ 'permalink' ] ); ?>" />
        <input type="hidden" name="excerpt" value="<?php echo( $auto_discovery_confirm[ 'excerpt' ] ); ?>" />
        <input type="submit" name="yes" value="<?php echo( $lang_string[ 'ok_btn' ] ); ?>" />
        <input type="submit" name="no" value="<?php echo( $lang_string[ 'cancel_btn' ] ); ?>" />
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
  
  <title><?php echo($blog_config[ 'blog_title' ]); ?> - <?php echo( $lang_string[ 'title' ] ); ?></title>
</head>
  <?php 
    // ------------
    // BEGIN OUTPUT
    // ------------
    theme_pagelayout();
  ?>
</html>
