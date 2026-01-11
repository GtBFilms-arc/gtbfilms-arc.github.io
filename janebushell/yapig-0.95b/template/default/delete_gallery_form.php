<?php

/**
 * delete_gallery_form.php 
 * This form is shown when deletion of a gallery is requested.
 *
 * + $I_SELECT_OPT
 * + $I_DEL_COMMENTS
 * + $I_DEL_IMG_COUNTERS
 * + $I_DEL_GAL_COUNTERS
 * + $I_NOT_LIST
 * + $I_DEL_THUMBS
 * + $I_DEL_ALL_FILES
 * + $I_SEND
 * + $D_GID
 * + I_DEL_IMG_CAPTIONS
 *
 * @package template
 */



echo <<<DGF

  <!-- BEGIN delete_gallery_form -->
  <div class="admin_data">
  <p>$I_SELECT_OPT</p>
  <form method="post"action="delete_gallery.php?action=delete&gid=$D_GID">
  <ul>

  <li>
  <input type="checkbox" checked="checked" name="d_comments" />
    $I_DEL_COMMENTS
  </li>     
  <li>
    <input type="checkbox" checked="checked" name="d_phid_stats" />
      $I_DEL_IMG_COUNTERS
  </li>
  <li>
     <input type="checkbox" checked="checked" name="d_gid_stats" />
      $I_DEL_GAL_COUNTERS
  </li>
  </ul>

  <ul>
  <li>
     <input type="checkbox" unchecked="unchecked"  name="d_gid" />
      $I_NOT_LIST
  </li>
    <li>
     <input type="checkbox" unchecked="unchecked" name="d_captions" />
      $I_DEL_CAPTIONS
   </li>

  <li>
     <input type="checkbox" unchecked="unchecked" name="d_thumbs" />
      $I_DEL_THUMBS
   </li>
   <li>
      <input type="checkbox" unchecked="unchecked" name="d_all" /> 
      $I_DEL_ALL_FILES
   </li>
   </ul>  

  <input type="submit" name="button" value="$I_SEND" class="formbutton">
  </form>
  </div>

  <!-- END delete_gallery_form -->
DGF;

?>
