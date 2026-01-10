<?php
include("view_func.php");

/**
 * This module contains all funtions used by all admin modules. I.e:
 *
 *  admin.php, add_gallery.php, modify_gallery.php, delete_gallery.php
 *
 * @package admin
 */


/**
 * load_gd tries to load gd library in case it is not automatically loaded.
 * Then checks the availability of some functions included in this library.
 *
 * Note: this function does not check if gd library is 2 or higher.
 *
 * @param bool $debug if true shows some debug messages. Used in installation.
 * @return bool true if library loaded, false if not.
 */

function load_gd($debug=false){

  if (!extension_loaded('gd')) {
    if ($debug) warning(_y("GD library not loaded. Trying to load it."));
    if (!function_exists('dl')){
      if ($debug)
	warning(_y("Function dynamic extension load not available."));
      return(false);
    }

    if (dl('php_gd2.dll')|| dl('php_gd.dll') ){
      if ($debug) msg(_y("php_gd(2).dll sucessfully loaded."));
    }
    else {
      if(!dl('gd.so'))	{
	if ($debug) warning(_y("GD library not available"));
	return(false);
      }
      if ($debug) msg(_y("gd.so sucessfully loaded."));
    }
  }

  //these functions are needed.
  if (!function_exists('getimagesize')) {
    if ($debug) warning(_y("Function getimagesize does not exist"));
      return(false);
  }
  if (!function_exists('imagecreatetruecolor')&&
      !function_exists('imagecreate')){
    if ($debug) warning(_y("Function imagecreate(truecolor) does not exist"));
    return(false);
  }
  if (!function_exists('imagecreatefromjpeg')) {
    if ($debug) warning(_y("Function imacreatefromjpeg does not exist"));
    return(false);
  }
  if ($debug) msg(_y("GD library is loaded."));
  return(true);
}

/**
 * 
 */

function get_dirs($dirname,  &$dir) {
    //echo "- $dirname<br />";
    $dir[] = $dirname;
    $handle = opendir($dirname);
    while( ( $name = readdir($handle)) !== false) {
	//echo "name: $name<br />";
	$full_name = $dirname . $name;
	if (is_dir($full_name)) {
	    //echo "name is a dir: $name ($full_name)<br />";
	    $yd = 'yapig_data';
	    if (($name!= ".") && ($name !="..") && 
		(strcmp(substr($name,0,strlen($yd)),$yd) != 0)) {
		//Append '/' if required 
		if (!preg_match("/\/\$/",$dirname)){
		    $dirname.='/';
		}
		
		if (!get_dirs($dirname . $name, &$dir)) {
		   // echo "Return False 2";
		    return false;
		}
	    }
	}
	
    }
    closedir($handle);
    return true;
}

/**
 * 
 * Gets all dirs of a directory
 * except '.','..' and the ones that begin with 'yapig_data'
 * 
 * @return array with the dirs or false if any problem
 */
function get_all_gid_dirs() {
    
    global $BASE_DIR;
    
    $list = array();
    if (! get_dirs($BASE_DIR, &$list)) {
	return false;
    }
    //debug($list,'get_all_dirs');

    return $list;
}


/**
 * Prints admin login form. Uses login_form.
 *
 * @return none
 * @global template directory
 *
 */


function login_form() {
    global $TEMPLATE_DIR;
    
    //Admin login form internationalization.
    $I_LOGIN=_y('Please login as administrator');
    $I_USER=_y('User');
    $I_PASSWORD=_y('Password');
    $I_SUBMIT=_y('Submit');
    include($TEMPLATE_DIR . 'login_form.php');
}

/**
 * Admin task bar contains all possible task the YaPiG admin can do.
 * This function prints this task bar. Uses admin_task_bar.php
 *
 * @global template location
 */

function print_admin_taskbar() {
    global $TEMPLATE_DIR;
    //I18n
    $I_ADMIN_TASKS=_y("Administrative tasks");
    $I_UPLOAD=_y('Upload files');
    $I_ADD=_y('Add gallery');
    $I_MODIFY=_y('Modify gallery');
    $I_DELETE=_y('Delete gallery');
    $I_CHANGE=_y('Configuration');
    $I_LOGOUT=_y('Logout');
    include ($TEMPLATE_DIR . 'admin_task_bar.php');
    
}

/*
 * Prints add/modify gallery information form. Used for adding a gallery
 * and for modifying gallery information such as title, author, description..
 *
 * @param string $D_ACTION destination url of the action attribute
 * @param string $D_TITLE gallery title
 * @param string $D_AUTHOR gallery author
 * @param string $D_DIR gallery dir
 * @param string $D_DESCRIPTION gallery description
 * @param string $D_PASSWORD gallery password
 * @global galleries base directory
 * @global directory where template is located
 */

function print_gallery_form($D_ACTION, $gallery_info=array()) {
    global $BASE_DIR,$TEMPLATE_DIR;
    
    $D_TITLE=$gallery_info['title'];
    $D_AUTHOR=$gallery_info['author'];
    $D_DESCRIPTION=$gallery_info['desc'];
    $D_PASSWORD=$gallery_info['gallery_password'];
    $D_DATE=$gallery_info['date'];
    if ($gallery_info['no_comments']=="on") {
	$D_CHECKED="checked=\"checked\"";
    }
    
    if (count($gallery_info) == 0) {
	if (!$list = get_all_gid_dirs()) {
	 echo "hello World";   
	}
    }
    $I_GALLERY_TITLE=_y('Gallery title');
    $I_AUTHOR = _y('Author');
    $I_DATE= _y('Date');
    $I_DATE_WARNING=_y("Leave it blank for today's date.");
    $I_LOCATION = _y('Location');
    $I_PASSWORD = _y('Password');
    $I_PASS_WARNING= _y('set this option for restricted access to gallery');
    $I_DESCRIPTION = _y('Description');
    $I_NO_COMMENTS=_y("Hide comments");
    $I_SEND=_y('Send');
    
    $D_BASE_DIR=$BASE_DIR;
    
    
    include($TEMPLATE_DIR . "gallery_form.php");
}

/////////// ACTIONS WITH GALLERIES ///////////////////



/**
 * Creates a thumbnail image from a jpeg file.
 *
 * It takes as arguments the paths to the source image and the path/name of
 * the destination image. Uses the global variables to set the destination
 * image size.
 *
 * @param string $path_dest Destination Path + name of output file
 * @param string $path_origen Source image path. Includes de name of the image
 *
 * @global max size in pixels
 * @global jpeg quality of thumbs
 *
 * @returns bool false if there was any problem :)
 *
 */

function create_thumb ($path_dest,$path_origen) {
    global $THUMB_SIZE, $THUMB_QUALITY, $GD_VERSION;
    
    echo  "<div> $path_origen";
    if (!file_exists($path_origen)) return(false);
    
    if (!($sz_orig = getimagesize ($path_origen))) return(false);
    
    
    $img_pixels=$sz_orig[0] * $sz_orig[1];
    if ($img_pixels>3900000){
	warning( $path_origen . _y(" is a huge image, it might cause memory issues."));
    }
    /*
     * Cut from getimagesize() documentation:
     * Returns an array with 4 elements. Index 0 contains the width of the
     * image in pixels. Index 1 contains the height. Index 2 is a flag
     * indicating the type of the image: 1 = GIF, 2 = JPG, 3 = PNG, 4 = SWF,
     * 5 = PSD, 6 = BMP, 7 = TIFF(intel byte order), 8 = TIFF(motorola
     * byte order), 9 = JPC, 10 = JP2, 11 = JPX, 12 = JB2, 13 = SWC,
     * 14 = IFF. These values correspond to the IMAGETYPE constants that
     * were added in PHP 4.3.
     */
    switch($sz_orig[2]) {
     case 1:
	//$im_orig=imagecreatefromgif ($path_origen);
	return(true);
	break;
     case 2:
	$im_orig=imagecreatefromjpeg ($path_origen);
	break;
     case 3:
       //warning(_y("PNG files not supported in this version"));
       //return(true);
       $im_orig=imagecreatefrompng ($path_origen);

	break;
     default: return(false);
    }
    //Now We calculate the thumbnail size.
    $ratio=$sz_orig[1]/$sz_orig[0];
    if ($ratio>1) {
	$height=$THUMB_SIZE;
	$width=(int)($THUMB_SIZE/$ratio);
    }
    else {
	$width=$THUMB_SIZE;
	$height=(int) ($THUMB_SIZE*$ratio);
    }

    if ($GD_VERSION==2) {// GD v2.0
      if(!($im_dest = imagecreatetruecolor ($width,$height)))
	return(false);
    }
    else{
      if(!($im_dest = imagecreate ($width,$height)))
	return(false);
    }



    echo " ({$sz_orig[0]}x{$sz_orig[1]}) -> <b>$path_dest ({$width}x{$height}))</b></div>";
    if ($GD_VERSION==2) {
	    if(!imagecopyresampled($im_dest,$im_orig,0,0,0,0,$width,$height,
			   $sz_orig[0],$sz_orig[1])) return(false);
    }
    else {
	  if(!imagecopyresized($im_dest,$im_orig,0,0,0,0,$width,$height,
			   $sz_orig[0],$sz_orig[1])) return(false);
    }

    if (!imagejpeg($im_dest, $path_dest, $THUMB_QUALITY)) return(false);
    return(true);
}

/**
 * This function creates all thumbnails
 *
 * files are stored in src_dir and will be stored in dest_dir prefixing $THUMB_PREFIX
 * to de image name.
 *
 * @param string $dest_dir  Path to destination dir
 * @param string $src_dir   Path to source dir
 * @param array $array_filenames  Contains the names of the source images (ex: file.jpg)
 *
 * @global string $THUMB_PREFIX
 * @return bool
 */

function create_all_thumbs ($dest_dir,$src_dir,$array_filenames) {
    global $THUMB_PREFIX;

    //First of all check if gd library is already loaded
     if(!load_gd(true)) {
      error(_y("GD library not loaded. Please read installation documentation"));
     }


    foreach($array_filenames as $src_file) {
	$src_path= $src_dir . $src_file;
	$dest_path= $dest_dir . $THUMB_PREFIX . $src_file;
	if (!create_thumb($dest_path,$src_path)) return(false);
    }
    return(true);
}

/**
 * Gets all image filenames of the dir
 *
 * This function scans all filenames that are in a directory and
 * returns the names of the image files that are cotained in.
 *
 * @param string $dir path of the dir.
 *
 * @global string preceding the name of the thumbnail
 * @global array with the valid extensions of the images
 * @return array With the image filenames that the directory contains.
 */

function get_all_image_filenames ($dir) {
    global $THUMB_PREFIX,$IMAGE_EXT;

    $list=array();
    if (!is_dir($dir)) return(false);
    if (!($dh = opendir($dir))) return(false);
    while (($file = readdir($dh)) !== false) {
	if (is_file($dir . $file)) {
	    $fileinfo = pathinfo($file);
	    if(in_array(strtolower($fileinfo["extension"]),$IMAGE_EXT)){
		if(!preg_match("/^$THUMB_PREFIX/",$fileinfo['basename'])){
		  array_push($list,$file);
		}
	    }
	}
    }
    closedir($dh);
sort($list); // Sort the list alphabetically
    return($list);
}

/**
 * creates phid filenames correspondence file with this line format:
 *  phid $EQUAL filename\n
 *
 * This is just an alias of create_data_file
 *
 * @param string $gid_dir directory where images are stored (with $BASE_DIR)
 * @param array $filenames array with this format: array[phid]=filename.
 * @global name of the created file
 * @return bool true if ok, false if not.
 *
 */

function create_phid_filenames_file($gid_dir,$filenames) {
    global $PHID_FILENAMES;
    return(create_data_file($gid_dir . $PHID_FILENAMES, $filenames));
}

/**
 * Creates file which contains information about the gallery.Called
 * $GID_INFO_FILE which is a php file that contains
 *
 * $new array has these keys:
 *
 * - root_gid: not used
 * - title: gallery title
 * - author: author of the gallery.
 * - gallery_password: password of the gallery.
 * - thumb_phid : thumbnail phid.
 * - no_comments: do not allow, comments (1|0)
 * - num_images : number of images
 * - num_subgids: number of sub galleries. Not used
 * - date: date of creation
 * - desc: lines describing the contents of the gallery.
 *
 * if file does not exist => all keys need to be set.
 * @param string $gallery_path path to gallery, includes $BASE_DIR
 * @param array $new array with gallery information
 * @return bool true if ok, false if error :D
*/
function create_info_file($gallery_path,$new,$update=true){
    global $GID_INFO_FILE, $TEMPLATE_DIR;

    //gif_path=gid_info_file path
    $gif_path=$gallery_path . $GID_INFO_FILE;

    //If file exists => only update values included in new)
    //If it not an update => overwrite file.
    if (!file_exists($gif_path)||(!$update)){
      $info=$new;
    }
    else {
	include($gif_path);
	foreach ($gid_info as $key => $value) {
	  //echo "{gid_info[$key]}=> $value; new[{$new[$key]}] [$set] <br />\n";
	  if (isset($new[$key]))  $info[$key]=$new[$key];
	  else
	    $info[$key]=$gid_info[$key];
	}//end foreach
    }

    //echo "<pre>";print_r($info);echo"</pre>";
    if (!($fd=fopen($gallery_path . $GID_INFO_FILE,"w+"))) return(false);
    fputs($fd,"<?php\n");
    fputs($fd,"\$gid_info['root_gid']=\"" . $info['root_gid']. "\";\n");
    fputs($fd,"\$gid_info['title']=\"". str_replace("\\'","'",$info['title']). "\";\n");
    fputs($fd,"\$gid_info['author']=\"". str_replace("\\'","'",$info['author']). "\";\n");
    fputs($fd,"\$gid_info['gallery_password']=\"".
	       $info['gallery_password']. "\";\n");
    fputs($fd,"\$gid_info['thumb_phid']=\"" . $info['thumb_phid'] . "\";\n");
    fputs($fd,"\$gid_info['no_comments']=\"" . $info['no_comments'] . "\";\n");
    fputs($fd,"\$gid_info['num_images']=\"". $info['num_images']. "\";\n");
    fputs($fd,"\$gid_info['num_subgids']=\"". $info['num_subgids']. "\";\n");
    fputs($fd,"\$gid_info['date']=\"". $info['date']. "\";\n");
    fputs($fd,"\$gid_info['desc']=\"". str_replace("\\'","'",$info['desc']). "\";\n");
    fputs($fd,"?>");
    fclose($fd);
    return(true);
}

/**
 * Gets the value of the gid counter and increases it one unit.
 * @global counter file which is incremented each time this func is called
 * @return integer valid gallery identificator., that is,one not being used.
 */

function get_gid_counter() {
    global  $GID_COUNTER_FILE;

    if (!file_exists($GID_COUNTER_FILE)){
	if (!($fd=fopen($GID_COUNTER_FILE,'w'))){
	    error(_y("Opening gid counter file: " . $GID_COUNTER_FILE));
	}
	fputs($fd,'1');
	fclose($fd);
	return(1);
    }
    if (!($fd=fopen($GID_COUNTER_FILE,'r+'))){
	error(_y("Opening gid counter file: " . $GID_COUNTER_FILE));
    }
    $counter=fgets($fd,10);
    $counter++;
    rewind($fd);
    fputs($fd,$counter);
    fclose($fd);
    return($counter);

}
/**
 * Checks if dir is being used by other gallery.
 *
 * Searchs in $GID_DIR file if $dir is being used by another
 * gallery.
 *
 * @param string $dir to check. Ended in '/'. Without $BASE_DIR
 * @global gid dirs correspondence file
 * @global field separator
 * @return bool true if dir is being used. false if not.
 */
function gid_dir_being_used($dir) {
 global $GID_DIRS, $EQUAL;

    //If it is first gallery => not being used
    if(!file_exists($GID_DIRS)) return(false);
    if(filesize($GID_DIRS)==0) return(false);
    //If could not load => Print warning.
    if (!($tmp=get_all_data($GID_DIRS))) {
	warning("gid_dir_being_used: Check file permissions of: " . $GID_DIR);
	return(false);
    }
    if (in_array($dir, $tmp)) return(true);
    return(false);
}

/**
 * Adds $dir to file $GID_DIRS, which contains gid->dir correspondence
 *
 * @param string $dir dir name without $BASE_DIR
 * @global gid->dirs correspondence filename.
 * @global gid->dirs field separator
 * @return bool true if ok, false if error.
 */

function add_to_gid_dirs($dir) {
    global $GID_DIRS, $EQUAL;

    //Load file
    $tmp=array();
    if (file_exists($GID_DIRS)) $tmp=file($GID_DIRS);
    //Update file
    if (!($fd=fopen($GID_DIRS,"w+"))) return(false);
    fputs($fd, get_gid_counter() . $EQUAL . $dir . "\n");
    foreach ($tmp as $line)
      fputs($fd,$line);
    fclose($fd);
    return(true);
}

/**
 * shows modify gid options (links)
 *
 * @param string $gid_dir gallery directory with $BASE_DIR
 * @param integer $gid gallery identificator
 * @param bool $gallery_bar if true then prints prev and next gallery bar.
 *
 * @global file with info about the contents of the gallery
 * @global template dir
 * @global phid comments stats file.
 * @global gid stats file.
 * @global total counter stats identifier.
 * @return bool true if ok false if not.
 */

function print_modify_gid($gid_dir,$gid,$gallery_bar=false){
  global $GID_INFO_FILE,$TEMPLATE_DIR,$PHID_COMMENTS,$GID_STATS,$TOTAL_LIST;

  if (!file_exists($gid_dir . $GID_INFO_FILE)) return(false);

  include ($gid_dir . $GID_INFO_FILE);
  
  $D_VISITS=get_data($GID_STATS,$gid);
  $D_COMMENTS=get_data($gid_dir. $PHID_COMMENTS, $TOTAL_LIST);
  $D_TITLE=$gid_info['title'];
  $D_AUTHOR=$gid_info['author'];
  $D_NUMBER_IMAGES=$gid_info['num_images'];
  $D_DATE=$gid_info['date'];
  $D_DESC=$gid_info['desc'];
  $D_THUMB=get_thumb_path($gid_dir, $gid_info['thumb_phid'],true);
  $I_MOD_INFO=_y('Modify gallery info');
  $I_SEL_THUMB=_y('Select thumbnail');
  $I_MOD_PHIDS=_y('Modify imagelist');
  $I_DEL_STUFF=_y('Delete gallery');
  $D_GID=$gid;
  $I_NUMBER_IMAGES=_y('Number of images');
  $I_TITLE=_y('Title');
  $I_AUTHOR=_y('Author');
  $I_VIEW_GALLERY=_y('View this gallery');
  $I_GALLERY=_y('Gallery');
  $I_VISITS=_y('Visits');
  $I_COMMENTS=_y('Comments');
  $I_DATE=_y('Date');
  $I_DESCRIPTION=_y('Description');
  $I_MOVE_UP=_y('Up');
  $I_MOVE_DOWN=_y('Down');
  $I_MOVE_UP_DESC=_y('Move up this gallery on index');
  $I_MOVE_DOWN_DESC=_y('Move down this gallery on index');

  if ($gallery_bar) {
    print_gallery_navigation_bar($gid,"modify_gallery.php?action=view_phids&amp;");
  }
  include($TEMPLATE_DIR . 'modify_gid.php');
  return(true);
}


/**
 * Shows form when admin wants to delete a gallery.
 *
 * Uses 'delete_gallery_form.php'
 *
 * @param integer $D_GID gallery identificator
 * @global template directory
 *
 */
function print_delete_gallery_form($D_GID){
  global $TEMPLATE_DIR;

  $I_SELECT_OPT=_y('Select deletion options:');
  $I_NOT_LIST=_y('Do not list gallery in gallery index any more.');
  $I_DEL_COMMENTS=_y('Clear all image comments.');
  $I_DEL_IMG_COUNTERS=_y('Clear all image visit counters.');
  $I_DEL_GAL_COUNTERS=_y('Clear gallery visit counters.');
  $I_DEL_THUMBS=_y('Delete gallery thumbnails.');
  $I_DEL_ALL_FILES=_y('Delete all files in directory.');
  $I_DEL_CAPTIONS=_y('Delete all image captions.');  
  $I_SEND=_y('send');

  include($TEMPLATE_DIR . 'delete_gallery_form.php');
}

/**
 * Obtains the images from a list that do not have a thumbnail already
 * created
 *
 * @param array $all_list list with filenames
 * @param string $gid_dir is the directory where images are stored
 * @return mixed false if problem. array with images without thumb if ok.
 */

function get_images_without_thumb($gid_dir,$all_list) {
  global $THUMB_PREFIX;

  if (!is_array($all_list)) return(false);
  foreach ($all_list as $one_image) {
    if (!@file_exists($gid_dir . $THUMB_PREFIX . $one_image)) {
      $return_list[]=$one_image;
    }
  }
  if (!is_array($return_list)) return(false);
  return($return_list);

}

/**
 * Creates a new file with the contents of $TEMPLATE_DIR . index_file.php 
 * (default template content of this file is a meta refresh).
 * User should include a refresh url where this file will point.
 *
 *
 * Default output filename is index.html.
 *
 * @param string $dir_path Path where filename will be created
 * @param string $refresh_url URL where filename will be redirected.
 * @param string $filename Output filename.
 * @global TEMPLATE_DIR Template directory.
 * @return boolean True if success.
 *
 */

function new_index_file($dir_path, $refresh_url='../', $filename='index.html'){
  global $TEMPLATE_DIR;

  if (file_exists($dir_path . $filename)) {
      return true;
  }
    
  if (!($fd=fopen($dir_path . $filename,"w+"))) {
      return (false);
  }
  
  //Follow yapig template variable name conventions.
  $D_REFRESH_URL=$refresh_url;

  //Interface variables
  $I_IF_NOT_REFRESHED=_y('If the page is not automatically refreshed.');
  $I_PRESS_HERE=_y('Press here');

  include($TEMPLATE_DIR . 'index_file.php');
  //index_file defines a variable called INDEX_FILE
  //with the contents of filename.
  
  fputs($fd,$INDEX_FILE);
  fclose($fd);
  return(true);
}


/**
 * rotates an image. Got from PHP.net
 *
 * @link http://es.php.net/manual/es/function.imageinterlace.php
 * 
 * @param string $imagePath - path to image; rotated image overwriting the 
 * old one
 * @param int $rtt MUST be 90 or -90 - cw/ccw
 */

function rotate_image($imagePath, $rtt=90){
    
    /*
     if(preg_match("/\.(png)/i", $imagePath)) 
     $src_img=ImageCreateFromPNG($imagePath);
     elseif(preg_match("/\.(jpg)/i", $imagePath)) 
     $src_img=ImageCreateFromJPEG($imagePath);
     elseif(preg_match("/\.(bmp)/i", $imagePath)) 
     $src_img=ImageCreateFromWBMP($imagePath);
     */

    //Check if exists
    if (!file_exists($imagePath)) {
	return(false);
    }
    
    //First of all check if GD rotateimage exists, if not => Use custom one
   //TODO
   
    $size=GetImageSize($imagePath);
    $width= $size[0];
    $height= $size[1];

    if (!($src_img=ImageCreateFromJPEG($imagePath))) {
	return false;
    }
    
    if ($GD_VERSION==2) {// GD v2.0
	if(!($im_dest = imagecreatetruecolor ($height, $width)))
	  return(false);
    }
    else {
	
	if(!($im_dest = imagecreate ($height, $width))) {
	    return(false);
	}
    }
  //echo "$size[1] x $size[0]";
  
  $dst_img=ImageCreateTrueColor($size[1],$size[0]);
  if($rtt==-90){
    $t=0;
    $b=$size[1]-1;
    while($t<=$b){
      $l=0;
      $r=$size[0]-1;
      while($l<=$r){
	imagecopy($dst_img,$src_img,$t,$r,$r,$b,1,1);
	imagecopy($dst_img,$src_img,$t,$l,$l,$b,1,1);
	imagecopy($dst_img,$src_img,$b,$r,$r,$t,1,1);
	imagecopy($dst_img,$src_img,$b,$l,$l,$t,1,1);
	$l++;
	$r--;
      }
      $t++;
      $b--;
    }
  }
  elseif($rtt==90){
    $t=0;
    $b=$size[1]-1;
    while($t<=$b){
      $l=0;
      $r=$size[0]-1;
      while($l<=$r){
	imagecopy($dst_img,$src_img,$t,$l,$r,$t,1,1);
	imagecopy($dst_img,$src_img,$t,$r,$l,$t,1,1);
	imagecopy($dst_img,$src_img,$b,$l,$r,$b,1,1);
	imagecopy($dst_img,$src_img,$b,$r,$l,$b,1,1);
	$l++;
	$r--;
      }
      $t++;
      $b--;
    }
  }
  ImageDestroy($src_img);
  ImageInterlace($dst_img,0);
  @ImageJPEG($dst_img,$imagePath);
  return(true);
}


?>
