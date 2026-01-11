<?php

/**
  *
  */


session_start();
require_once('functions.php');
require_once('locale.php');
require_once('config.php');
require_once('view_func.php');

//_GET[varname] => varname
$gid=$_GET['gid'];
$phid=$_GET['phid'];
$interval=$_GET['interval'];
$paused=$_GET['paused'];

if (!isset($gid)) $gid='1';

if (!($dir=get_data($GID_DIRS,$gid))) 
    error(_y('Retrieving gallery directory'));

$gid_dir= $BASE_DIR . $dir;
include($gid_dir . $GID_INFO_FILE);

if (!isset($phid)) $phid= get_first_key($gid_dir . $PHID_FILENAMES);
if (!isset($interval)) $interval=$SS_INTERVAL; //SS_INTERVAL defined on config.php
if (!isset($paused)) $paused='0';

//echo "SS_INTERVAL: ". $SS_INTERVAL . " interval: ". $interval;
//Obtain gallery directory and information about this gallery.

if (!check_gallery_password($gid_info['gallery_password'],$form_pw)){ //if $
    include($TEMPLATE_DIR . 'face_begin.php');
    error(_y("Password incorrect."));
}


$phid_filename= get_data($gid_dir . $PHID_FILENAMES, $phid);
$img_path=$gid_dir . $phid_filename;
if (!file_exists($img_path)) {
    include($TEMPLATE_DIR . 'face_begin.php');
    error("Incorrect arguments");
}
$phid_comments=get_data($gid_dir . $PHID_COMMENTS, $phid);

$img_url=$gid_dir . rawurlencode($phid_filename);


$images=$gid_info['num_images'];
$current=get_position($gid_dir . $PHID_FILENAMES, $phid);
$position=$current . "/".$images;

$pn = get_prev_and_next($gid_dir . $PHID_FILENAMES, $phid);
$phid_next=$pn[2];
$last_slide=0;
if ( $phid_next < 0 ) {
    $last_slide=1;
}


$the_url  = getenv("PHP_SELF") ? getenv("PHP_SELF") : $_SERVER["PHP_SELF"];
$the_url .= "?gid=" . $gid;
$the_url .= "&interval=" . $interval;


$I_TITLE= _y("Slideshow") . " - " . $gid_info['title'];
     include($TEMPLATE_DIR . 'face_begin.php');
     heading((_y("Slideshow"). " - " . $gid_info['title']));
/* 
 //echo "MAX_SS_IMG_SIZE $MAX_SS_IMG_SIZE";
     //Setting MAX_IMG_SIZE (view config.php for doc)
     if ($MAX_SS_IMG_SIZE > 0) {
	 $sz_orig = getimagesize($img_path); 
	 //debug($sz_orig);
	   $ratio=$sz_orig[1]/$sz_orig[0];
	 if ($sz_orig[0]> $MAX_SS_IMG_SIZE || $sz_orig[0]> $MAX_SS_IMG_SIZE) {	    
	     if ($ratio>1) {
		 $height=$MAX_SS_IMG_SIZE;
		 $width=(int)($MAX_SS_IMG_SIZE/$ratio);
	     }
	     else {
		 $width=$MAX_SS_IMG_SIZE;
		 $height=(int) ($MAX_SS_IMG_SIZE*$ratio);
	     }
	     //Now construct img_size html code
	     $img_size= "style=\"width:$width;height:$height\" ";
	 }
     }
*/
     if (!($D_DESC=get_data($gid_dir. $PHID_CAPTIONS, $phid)))
	    $D_DESC="";
	else $D_DESC.="<br />";
     if (!($paused) & !($last_slide))
     {
     ?>
		<META http-equiv="refresh" content="<?php print $interval; ?>; URL=<?php print $the_url . "&phid=" . $phid_next; ?>">
	<?php 
 
     }

     echo "<div class=\"center\">$position</div>";
     echo "<div class=\"viewimage\"><a name=\"img\"></a><img src=\"".$img_url. "\" ". $img_size . "alt=\"$phid_filename\" id=\"myimage\" class=\"image\" onload=\"preload('$img_url')\"/></div>";
     echo "<div class=\"comment\">$phid_filename<br />$D_DESC</div>";
     add_listed_visit( $gid_dir . $PHID_STATS, $phid);
     $MAX_IMG_SIZE=$MAX_SS_IMG_SIZE;
     print_zoom_bar($img_path);
?>
  

<SCRIPT language="JavaScript">
		function next_image()
		{
		location.href='<?php print $next_url . "&paused=1"; ?>'
		}
		
		</SCRIPT>
<br>

<form action=""><center> <input type="button" value="Close Window" onClick="window.close()"> 

<?php
if (!$last_slide)
{
if (!$paused)
{
?>
 <input type="button" value="Pause" onClick="location.href='<?php print $the_url . "&phid=" . $phid . "&paused=1";?>'"> </center></form>
<?php
}
else
{ 
?>
<input type="button" value="Continue" onClick="location.href='<?php print $the_url . "&phid=" . $phid_next;?>'"> </center></form>
<?php
}
}
else
{
?>
<input type="button" value="Restart" onClick="location.href='<?php print $the_url . "&phid=0";?>'"> </center></form>
<?php
}
?>
