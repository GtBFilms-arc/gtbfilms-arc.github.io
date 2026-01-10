<?php
/** 
 * This is the information shown in the gallery index of one thumbnail
 * and the thumbnail itself.
 * 
 * Used by view.php called from functions.php
 * 
 * @package template
*/
echo <<<MINIIMAGE
  
 <!-- view_miniimage BEGIN -->
 <div class="minimage">
     <a href="$D_URL" title="$I_VIEW_BIGGER">
     <img src="$D_THUMBNAIL" class="thumb" alt="$I_IMAGE $i" /></a>
      <br />
     <div class="view">
       <a href="$D_URL" title="$I_VIEW_BIGGER">$D_IMAGENAME</a><br />
       <big>$D_DESC</big>
       <small>($I_HITS: $phid_visits $I_COMMENTS: $phid_comments)</small>
     </div>
 </div>
 <!-- end minimage -->
MINIIMAGE;
?>	
