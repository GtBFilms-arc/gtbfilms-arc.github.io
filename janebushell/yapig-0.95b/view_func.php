<?php

/**
 * Functions that use the gallery and image viewer. Basically, these functions
 * do things like create the index of galleries (view the thumbnail and
 * information about a gallery), the index of thumbnails of a particular
 * gallery (thumbnails and links to view the image bigger). As well, there
 * are functions to create the navigation bars 
 * (next or previous gallery||image) and things like that.
 * 
 * @package viewer
 * 
 */


/**
 * Requires general functions and config variables
 */
require_once('functions.php');
require_once('config.php');
require_once('exif.php');

/**
 * Displays the index of galleries
 *
 * This function displays an index of all galleries. It prints main information
 * of each gallery and a thumbnail
 *
 * @param string $base_url Destination link will be "$base_url . $gid" 
 * @param string $gid_dirs_file file with gid and directories (ie $GID_DIRS) 
 * @param string $actions Aditional HTML, 
 * @return bool true if all went ok
 */

function index_of_galleries($base_url,$gid_dirs_file,$actions=''){
  global $BASE_DIR,$EQUAL,$GID_INFO_FILE,$GID_STATS, $PHID_STATS,$PHID_COMMENTS,$TOTAL_LIST;
  global $HTTP_SESSION_VARS,$TEMPLATE_DIR, $INDEX_COLUMNS;

  //Now get list of gallery ids and directorys;
  if (!($gid_list=get_all_data($gid_dirs_file))) return(false);

    echo "<table class=\"gidindextable\">";
    $j=0; //counter for knowing the number of displaying gallery.
    foreach ($gid_list as $gid => $gid_dir) {
	
	//Control if is a new row of galleries
	if (($j % $INDEX_COLUMNS)==0) {
	    echo "<tr><td>";
	}else {
	    echo "<td>";
	}
		
	$D_PASSWORD_FORM=''; //Set to NULL
	$gid_dir=$BASE_DIR . $gid_dir; 
	if (!is_dir($gid_dir)) {  
	    warning(_y("Gallery does not exist: ") . $gid);
	    continue;	 
	}
		
	if (!file_exists($gid_dir . $GID_INFO_FILE)) return(false);
	require( $gid_dir . $GID_INFO_FILE);
	  
	//Convert info loaded from $GID_INFOFILE in standard template names
	$D_URL=$base_url .$gid;
	$D_VISITS=get_data($GID_STATS,$gid);
	$D_COMMENTS=get_data($gid_dir. $PHID_COMMENTS, $TOTAL_LIST);
	$D_TITLE=$gid_info['title'];
	$D_AUTHOR=$gid_info['author'];
	$D_NUMBER_IMAGES=$gid_info['num_images'];
	$D_DATE=$gid_info['date'];
	$D_DESC=stripslashes($gid_info['desc']);
	$D_THUMB=get_thumb_path($gid_dir, $gid_info['thumb_phid']);
       
	$D_SSURL='slideshow.php?gid=' . $gid;
	$D_ACTIONS= $actions;

	$I_NUMBER_IMAGES=_y('Number of images');
	$I_TITLE=_y('Title');
	$I_AUTHOR=_y('Author');
	$I_VIEW_GALLERY=_y('View this gallery');
	$I_GALLERY=_y('Gallery');
	$I_VISITS=_y('Visits');
	$I_COMMENTS=_y('Comments');
	$I_DATE=_y('Date');
	$I_DESCRIPTION=_y('Description');
       $I_SSHOW=_y('View slideshow');

	
	//If gallery has a password then show password form	
	if (!check_gallery_password($gid_info['gallery_password']) 
	    && !check_admin_login())
	  {
	    //User didnt introduced the password.
	    $form_action=$D_URL;
	    $D_THUMB= $TEMPLATE_DIR . "password.jpg";
	    $I_ALERT=_y('This Gallery requires password. Please fill the password field and press the validate button. Thank you.');
	    $D_URL="javascript:alert('$I_ALERT');";
	    $I_VALIDATE=_y('Validate');
	    $I_GALLERY_PASSWORD=_y('Gallery password');

	    $D_PASSWORD_FORM=<<<PWFORM
	      
        <form class="pwform" method="POST" action="$form_action">
	{$I_GALLERY_PASSWORD}:
	<input type="password" value="" name="form_pw" />
        <input type="submit" value="$I_VALIDATE" class="formbutton" />
         </form>
PWFORM;
	  }
	
	include($TEMPLATE_DIR . 'gid_index.php');
	
	//Is the last column of this row of galleries?
	if (($j % $INDEX_COLUMNS)==($INDEX_COLUMNS-1))
	  echo "</td></tr>"; //Yes, it is.
	else echo "</td>"; //No, it isn't.
	
	$j++; 
    }
    echo "</table>";
    return(true);
}

/**
 * Shows a table with the thumbnails of the gid specified
 *
 * This function shows the thumbnails of the images of a gallery. It shows
 * number of comments and number of visits of each image. It also makes a link
 * to allow viewing the bigger image.
 *
 * @param int $gid number of the gid gallery.
 *
 **/

function gallery_thumbs ($gid,$page) {
    global $BASE_DIR, $GID_DIRS, $GID_INFO_FILE, $PHID_FILENAMES,
      $EQUAL, $PHID_STATS, $PHID_COMMENTS, $THUMB_PREFIX,
      $NUM_COLUMNS, $NUM_ROWS,$TEMPLATE_DIR,
      $PHID_CAPTIONS;

    if (!($gid_dir=get_data($GID_DIRS,$gid))) return(false);
    $gid_dir= $BASE_DIR . $gid_dir;
    if (!file_exists($gid_dir . $GID_INFO_FILE)) return(false);
    include($gid_dir . $GID_INFO_FILE);    
    if (!($phid_filenames=get_all_data($gid_dir . $PHID_FILENAMES)))
      return(false);
        
    $i=0;
    echo "<table>";

    // I18n -  
    $I_VIEW_BIGGER=_y('Enlarge image');
    $I_IMAGE=_y('Image');
    $I_HITS=_y('Hits');
    $I_COMMENTS=_y('Comments');
    
    if($NUM_ROWS>0){
	$img_per_page=$NUM_COLUMNS * $NUM_ROWS;

	$page_begin=$page*$img_per_page;
	$page_end=$page_begin + $img_per_page;
	$phid_filenames_len=count($phid_filenames);
	if ($phid_filenames_len < $page_end) {
	    $page_end=$phid_filenames_len;
	}
	if ($phid_filenames_len < $page_begin) {
	    $page_begin = 0;
	}
	//echo "page_begin=$page_begin;page_end=$page_end<br />";
	$i=0;
	foreach($phid_filenames as $phid => $filename) {
	    if (($i>=$page_begin) && ($i<$page_end) )
		$phid_filenames_tmp[$phid]=$filename;
	    $i++;
	}
	$phid_filenames=$phid_filenames_tmp;    
    }
    $i=0;
    foreach ($phid_filenames as $phid => $phid_filename) {
	//echo "phid: $phid; filename= $phid_filename<br />"; // Debug Line
	$phid_visits=get_data($gid_dir . $PHID_STATS, $phid);
	$phid_comments=get_data($gid_dir . $PHID_COMMENTS, $phid);
	$img_path=$gid_dir . $phid_filename;
	$thumb_name=$THUMB_PREFIX . $phid_filename;

	$D_IMAGENAME= $phid_filename;
	$D_URL="view.php?gid=$gid&amp;phid=$phid";
	if (!($D_DESC=get_data($gid_dir. $PHID_CAPTIONS, $phid)))
	    $D_DESC="";
	else $D_DESC.="<br />";

	//If thumbnail does not exist => default thumb
	
	if (!@file_exists( $gid_dir . $thumb_name)) 
	  $D_THUMBNAIL = $TEMPLATE_DIR . "not_available.jpg";
	else 
	  $D_THUMBNAIL= $gid_dir . rawurlencode($thumb_name);  
	if (($i%$NUM_COLUMNS)==0 ) {
	    if ($i == 0) { //Firs row.	
		echo "<tr><td>";
	    } else {
		echo "</tr><tr><td>";
	    }
	} else {
	    echo "<td>";
	}
	include($TEMPLATE_DIR . 'view_minimage.php');
	echo "</td>";
	$i++;
    }
    echo "</tr></table>";
    return(true);
}




/*
 * Prints exif information
 *
 */

function print_exif_info( $filepath, $phid) {
    global $PHID_STATS, $PHID_INFO,$PHID_CAPTIONS, 
      $TEMPLATE_DIR, $MAX_IMG_SIZE;

    $path_info=pathinfo($filepath);
    $sz = getimagesize ($filepath);

    $gid_dir=$path_info['dirname']. "/";

    $D_FILENAME=$path_info['basename'];
    $D_FILESIZE=(int)(filesize($filepath)/1024);
    $D_HEIGHT=$sz[1];
    $D_WIDTH=$sz[0];
    $D_MAX_IMG_SIZE=$MAX_IMG_SIZE;
    
    $D_VISITS=get_data($gid_dir . $PHID_STATS,$phid);
       
    if (($D_DESC=get_data($gid_dir . $PHID_CAPTIONS, $phid))==false)
	$D_DESC=_y('Image caption not set');

    $I_FILENAME=_y('Filename');
    $I_FILESIZE =_y('File size');
    $I_IMAGE_SIZE =_y('Image size');
    $I_VISITS =_y('Visits');
    $I_DESC =_y('Description');
    
    $all_exif = read_exif_data_raw($filepath, 0);
    $exif['Valid'] = false;
    if ($all_exif['ValidEXIFData']==1) {
	$exif['Valid'] = true;
	$exif['Model'] =str_replace("\0",'', $all_exif['IFD0']['Model']);
	$exif['Date'] = str_replace("\0",'',$all_exif['SubIFD']['DateTimeOriginal']);
	$exif['ExposureTime'] = $all_exif['SubIFD']['ExposureTime'];
	$exif['FocalLength'] = $all_exif['SubIFD']['FocalLength'];
	$exif['ShutterSpeedValue'] =  $all_exif['SubIFD']['ShutterSpeedValue'];
	$exif['ApertureValue'] =  $all_exif['SubIFD']['ApertureValue'];
	$exif['Flash'] = $all_exif['SubIFD']['Flash'];	
	//debug($exif);	
	//debug($all_exif, 'RawExifData');
   }
    include ($TEMPLATE_DIR . 'phid_info.php');
    

    /*
     * 
     //Temporally unavailable --
    echo "<div><b>Información exif:</b><br />\n";
    $exif = exif_read_data ($filepath,'IFD0');
    if ($exif===false) echo "No header data found.<br />\n";
    else {
	$exif = exif_read_data ($filepath,0,true);
	foreach($exif as $key=>$section) {
	    foreach($section as $name=>$val) {
		echo "$key.$name: $val<br />\n";
	    }
	}
    }
    */


} //End function



/**
 * prints comments form 
 * 
 * optionally can include as argument field, an array with this keys:
 *  +tit comment title.
 *  +aut author
 *  +date comment date
 *  +mail author mail
 *  +web author web
 *  +msg comment text
 * 
 * @param string $D_URL url of the action.
 * @param array $fields content of the fields,
 */

function print_form($D_URL,$fields=array()) {
global $TEMPLATE_DIR;
 
//echo "<pre>";print_r($fields);echo "</pre>"; //Debug Line;

 $D_TITLE=$fields['tit'];
 $D_AUTHOR=$fields['aut'];
 $D_EMAIL=$fields['mail'];
 $D_DATE=$fields['date'];
 $D_WEB=$fields['web'];
 $D_COMMENT=$fields['msg'];
   
 $I_ADD_COMMENT =_y('Add your comment!');
 $I_TITLE =_y('Comment title');
 $I_AUTHOR =_y('Author');
 $I_EMAIL =_y('Email');
 $I_WEB =_y('Website');
 $I_COMMENT =_y('Comment');
 $I_REQUIRED =_y('* = required fields');
 $I_SEND=_y('Send');

include($TEMPLATE_DIR . 'add_comment_form.php');

} //Fin funcion print form

/*
 * Prints one comment
 */
function print_comment($line, $D_ADMIN_HTML="") {
     global $TEMPLATE_DIR, $SEPARATOR;
     
     list($title,$author,$date,$mail,$web,$comment)=array_values(explode($SEPARATOR,$line));

    $mail=trim($mail);
    if (strlen($mail)>0)
      $D_LINKMAIL="<a href=\"mailto:{$mail}_NOSPAM_\">$author</a>";
    else $D_LINKMAIL=$author;
    if (strlen($web)) $D_LINKWEB="web: <a href=\"$web\">$web</a>";
     $D_TITLE=$title;
     $D_AUTHOR=$author;
     $D_DATE=$date;
     $D_COMMENT=$comment;
     $I_DATE=_y('Date');
     $I_COMMENT=_y('Comment');

    include($TEMPLATE_DIR . 'print_comment.php');

} //Fin print_comment


function print_all_comments($file,$gid, $phid) {
    global $SEPARATOR ;

    heading(_y("Image comments"));
    if (!file_exists($file)||(filesize($file)==0))  {
	heading(_y("There are no messages! Give us your opinion!"),4);
    }
    else {
    	//We read the file. In each line there is a comment.
	if (($fd=fopen ($file,"r"))===false) {
	    error(_y("Opening comments file"));
	}
	while(!feof($fd)) {
	    $line=fgets($fd,2048);
             //Separe fields in each line
	    if(strlen($line)<3) continue; //Just if there is a blank line.
	    print_comment($line);
	} //fin while
	fclose($fd);
    }//fin else
    $url="add_comment.php?gid=$gid&amp;phid=$phid";
    print_form($url);
}
/**
 * prints gallery navigation bar which consist in next previous
 * and index of galleries links
 * 
 * @param integer gid gallery identifier
 * @global string gid => directory data file
 * @global string user template directory
 * @return bool true if ok, false if not :D
*/

function print_gallery_navigation_bar( $gid, $base_url="view.php?hit=yes&amp;") {
  global $GID_DIRS,$TEMPLATE_DIR, $NUM_ROWS;

  $D_PREV=_y("Previous gallery");
  $D_NEXT=_y("Next gallery");
  $D_UP="<a href=\"{$base_url}\">". _y("Gallery index")."</a>";


    
    
  if (!($nav_info=get_prev_and_next($GID_DIRS,$gid))){
    warning(_y("could not display gallery navigation bar"));
    return(false);
  }
  if (!($nav_info[0]<0)) {
    $D_PREV="<a href=\"{$base_url}gid=".$nav_info[0]."\">$D_PREV</a>";      
  }
  if (!($nav_info[2]<0)) {
    $D_NEXT="<a href=\"{$base_url}gid=".$nav_info[2]."\">$D_NEXT</a>";      
  }
    $D_SSHOW="<a href=\"slideshow.php?gid=$gid\" id=\"popuplnk\" target=\"Slideshow\" onclick=\"slideshow();\">". _y('[Slideshow]')."</a>";
  include($TEMPLATE_DIR . "prev_up_next_bar.php");
  return(true);
}


/**
 * prints gallery navigation bar which consist in next photo,
 * previous photo and gallery index links.
 * 
 * @param integer gid this gallery identifier
 * @param integer phid this photo identifier
 * @param string gid_dir $BASE_DIR + gallery_dir
 * @global string gid => directory data file
 * @global string user template directory
 * @return bool true if ok, false if not :D
*/

function print_navigation_bar ($gid,$phid,$gid_dir,$base_url='view.php?') {
  global $PHID_FILENAMES,$TEMPLATE_DIR,$NUM_COLUMNS,$NUM_ROWS, $GID_INFO_FILE;

    $D_PREV=_y('Previous image');  
    $D_NEXT=_y('Next image');  

    //Get Current Page
    if ($NUM_ROWS!=0) {
	$img_per_page = $NUM_COLUMNS*$NUM_ROWS;
	$page=(int) ($phid/($img_per_page));
    }

   include($gid_dir . $GID_INFO_FILE);
    $images=$gid_info['num_images'];
    $current=get_position($gid_dir . $PHID_FILENAMES, $phid);
    $D_PAGE = "(" . $current . "/".$images . ")";
    
    
      
    $D_UP="<a href=\"{$base_url}gid=$gid&amp;page=$page\">". _y('Up')."</a>";  
$D_SSHOW="<a href=\"slideshow?gid=$gid&amp;phid=$phid\" id=\"popuplnk\" target=\"Slideshow\" onclick=\"slideshow();\">". _y('[Slideshow]')."</a>";
    if (!($nav_info=get_prev_and_next($gid_dir . $PHID_FILENAMES,$phid))){
      warning(_y("could not display gallery navigation bar"));
      return(false);
    }
    if (!($nav_info[2]<0)) {
      $D_NEXT="<a href=\"{$base_url}gid=$gid&amp;phid=". $nav_info[2] . 
	"\" title=\"$D_NEXT\">$D_NEXT</a>";
    }
    if (!($nav_info[0]<0)) {
      $D_PREV="<a href=\"{$base_url}gid=$gid&amp;phid=" . $nav_info[0] . 
	"\" title=\"$D_PREV\">$D_PREV</a>";
    }
    
    include($TEMPLATE_DIR . "prev_up_next_bar.php");
    return(true);
}


function print_page_navigation_bar($gid_dir,$gid,$actual_page){
    global $NUM_COLUMNS, $NUM_ROWS, $GID_INFO_FILE, $TEMPLATE_DIR;
    
    //$NUM_IMG=0 => only one big page.
    if ($NUM_ROWS==0) return(true);
    //Calculate number of images/page
    $img_per_page= $NUM_ROWS * $NUM_COLUMNS;
    
    //Check if there are enougth images for more than one page.
    include($gid_dir . $GID_INFO_FILE);
    //debug($gid_info);
   // echo "images/page: $img_per_page num_images: " . $gid_info['num_images'] . " <br />";
    if ($img_per_page>=$gid_info['num_images']) {
	return(true);
    }
    $D_PREV=_y('Previous page');
    $D_NEXT=_y('Next page');
    
    $last=(int)(($gid_info['num_images']+1)/$img_per_page) ;

    if($actual_page > $last) { 
	$actual_page = 0; 
    }
    $D_PAGE = _y('Page')  ." ". ($actual_page+1) .  "/" . ($last + 1) ;    

    if ($actual_page<$last){
	$D_NEXT="<a href=\"view.php?gid=$gid&amp;page=".($actual_page+1) . 
	  "\" title=\"$D_NEXT\">$D_NEXT</a>";
    }
    if ($actual_page>0) {
	$D_PREV="<a href=\"view.php?gid=$gid&amp;page=".($actual_page-1) . 
	  "\" title=\"$D_PREV\">$D_PREV</a>";
    }  
    
    include($TEMPLATE_DIR . "prev_up_next_bar.php");
 
    return(true);
    
}

function print_zoom_bar($img_path) {
  global $TEMPLATE_DIR,$MAX_IMG_SIZE;
  if(!($sz=getimagesize($img_path)))
     return(false);
  
  $D_WIDTH=$sz[0];
  $D_HEIGHT=$sz[1];
  $D_MAX_IMG_SIZE=$MAX_IMG_SIZE;

  $D_ZOOM_IN=_y('Zoom in');
  $D_ZOOM_OUT=_y('Zoom out');
  $D_REAL=_y('Original size');

  include ($TEMPLATE_DIR . 'zoom_bar.php');
  return(true);
}

?>
