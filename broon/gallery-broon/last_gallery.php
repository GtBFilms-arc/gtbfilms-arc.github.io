<?php
/**	
 *
 * Show Last gallery thumbnail
 * 
 * Sample of usage. In the file you want to view the thumbnail
 * add these lines:
 * 
 * <?php
 *   $YAPIG_PATH='yapig/'; //Path to yapig
 *   include($YAPIG_PATH . 'last_gallery.php');
 * 
 *   //Then you have these options:
 * 
 *   //Create a link to index of galleries. Displaying the last gallery thumbnail
 *   last_gallery('index'); 
 * 
 *   //Create a link to the last gallery added
 *   last_gallery('gallery'); 
 * 
 *   //Creates a link to the index of galleries and display a random thumbnail
 *    random('index'); 
 *   
 * 
 *  
 * ?>
 * Note: the file must have .php extension. 
 * 
 * You have a sample file called sample.php on Yapig distribution.
 * 
 */


if (isset($_GET['YAPIG_PATH']) || isset($_POST['YAPIG_PATH'])) {
    die;
}

require_once($YAPIG_PATH . 'config.php');
require_once($YAPIG_PATH . 'global.php');
require_once($YAPIG_PATH . $BASE_DIR . 'global-gen.php');
require_once($YAPIG_PATH . 'datafile_func.php');
require_once($YAPIG_PATH . 'functions.php');


/**
 * Shows last gallery thumbnail with a link to 
 * 
 *  + the index of galleries
 *  + index of thumbnails of the last gallery added
 * 
 * @param $string link_to values must be 'index' or 'gallery'
 * 
 */
function last_gallery($link_to='index'){
   global $YAPIG_PATH, $GID_DIRS, $BASE_DIR, $GID_INFO_FILE;
    
    if(!$all_galleries = get_all_data($YAPIG_PATH . $GID_DIRS)) {
	error("Problem 1");	      
    }

    $k=key($all_galleries);
    $last= $all_galleries[$k];  
    $gid_dir= $YAPIG_PATH . $BASE_DIR . $last;
    if (!file_exists($gid_dir . $GID_INFO_FILE)) {
     error("Problem 2");
    }
    include( $gid_dir. $GID_INFO_FILE);
   
    $thumb= get_thumb_path($gid_dir,$gid_info['thumb_phid'],true);

      switch ($link_to) {
       case 'gallery': 
	  $link= $YAPIG_PATH .'view.php?gid=' . $k;
	  break;
       default: $link= $YAPIG_PATH .'gallery.php';
	  break;
      }
    
     echo "<a href=\"$link\"><img src=\"$thumb\" alt=\"Yapig\"/></a>"; 	
}
?>
