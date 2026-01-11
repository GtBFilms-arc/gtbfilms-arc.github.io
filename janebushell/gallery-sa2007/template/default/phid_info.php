<?php
/**
 * Information about one image. It is the information shown below that image
 * 
 * @package template
 * 
 * Temporally exif data is stored in an array called $exif
 */

echo<<<PHID_INFO_SHOW
  <!-- phid_info_show BEGIN -->
  <div class="phidinfo">

PHID_INFO_SHOW;

//Exif data array
if ($exif['Valid']) {
    unset($exif['Valid']);
    echo "<div class=\"exif_data\"><strong>Exif Info</strong><br />";
    foreach ($exif as $key => $value) {
	echo "<b>$key</b>: <i>$value</i><br />";
    }
    echo "</div>";
}

echo <<<PHID_INFO_SHOW
  <b>{$I_FILENAME}:</b> $D_FILENAME<br />
  <b>{$I_FILESIZE}:</b> $D_FILESIZE KB<br />
  <b>{$I_IMAGE_SIZE}: </b>$D_WIDTH x $D_HEIGHT   pixels<br />
  <b>{$I_VISITS}:</b> $D_VISITS<br />
  <b>{$I_DESC}:</b> $D_DESC<br />  
  </div>
  <!-- phid_info_show END -->
  
PHID_INFO_SHOW;

?>

