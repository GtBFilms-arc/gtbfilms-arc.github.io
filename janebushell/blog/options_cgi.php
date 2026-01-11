<?php
  // ---------------
  // INITIALIZE PAGE
  // ---------------
  require_once('scripts/sb_functions.php');
  global $logged_in;
  $logged_in = logged_in( true, true );
  
  read_config();
  
  require_once('languages/' . $blog_config[ 'blog_language' ] . '/strings.php');
  sb_language( 'options' );
  
  // ---------------
  // POST PROCESSING
  // ---------------
  $key_array = array( 'lDate_slotOne',
            'lDate_slotOneSeparator',
            'lDate_slotTwo',
            'lDate_slotTwoSeparator',
            'lDate_slotThree',
            'lDate_slotThreeSeparator',
            'lDate_slotFour',
            'lDate_slotFourSeparator',
            'lDate_leadZeroDay',
            'sDate_order',
            'sDate_separator',
            'sDate_leadZeroDay',
            'sDate_leadZeroMonth',
            'sDate_fullYear',
            'time_clockFormat',
            'time_leadZeroHour',
            'time_AM',
            'time_PM',
            'time_separator',
            'eFormat_slotOne',
            'eFormat_separator',
            'eFormat_slotTwo',
            'server_offset',
            'mFormat' );
  
  $array = array();
  for ( $i = 0; $i < count( $key_array ); $i++ ) {
    array_push( $array, str_replace( '|', ':', $_POST[ $key_array[$i] ] ) );
  }
  
  global $ok;
  $ok = write_dateFormat( $array );
  
  if ( $ok === true ) {
    redirect_to_url( 'index.php' );
  }
  
  // ------------
  // PAGE CONTENT
  // ------------
  function page_content() {
    global $lang_string, $blog_config, $ok;
    
    // SUBJECT
    $entry_array = array();
    $entry_array[ 'subject' ] = $lang_string[ 'title' ];
    
    // PAGE CONTENT BEGIN
    ob_start();
    
    if ( $ok !== true ) {
      echo( $lang_string[ 'error' ] . $ok . '<p />' );
    } else {
      echo( $lang_string[ 'success' ] . '<p />' );
    }
    
    echo( '<a href="index.php">' . $lang_string[ 'home' ] . '</a>' );
    
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
