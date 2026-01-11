<?php

/**
 * modify_gid
 *
 * this file is used on YaPig's admin to show gallery modification
 * options
 * 
 * List of available variables:
 * + $D_THUMB thumbnail
 * + $D_GID gid
 * + $D_VISITS
 * + $D_COMMENTS
 * + $D_TITLE
 * + $D_AUTHOR
 * + $D_NUMBER_IMAGES
 * + $D_DATE
 * + $D_DESC
 * + $D_THUMB
 * + $I_MOD_INFO 'Modify gallery info'
 * + $I_SEL_THUMB 'Select thumbnail'
 * + $I_MOD_PHIDS 'Modify image list'
 * + $I_DEL_STUFF 'Delete gallery'
 * + $D_GID
 * + $I_NUMBER_IMAGES 'Number of images'
 * + $I_TITLE 'Title'
 * + $I_AUTHOR 'Author'
 * + $I_VIEW_GALLERY 'View this gallery'
 * + $I_GALLERY 'Gallery'
 * + $I_VISITS 'Visits'
 * + $I_COMMENTS 'Comments'
 * + $I_DATE 'Date'
 * + $I_DESCRIPTION 'Description'
 * + $I_MOVE_UP 'Up'
 * + $I_MOVE_DOWN 'Down'
 * + $I_MOVE_DOWN_DESC (Description)
 * + $I_MOVE_DOWN_DESC (Description)
 * 
 * @package template
 */

echo <<<SGZ
  <a name="$D_GID"></a>
  <table><tr>
   <td>   
    <table class="phidinfo"><tr><td>
     <a href="modify_gallery.php?action=move_up&amp;gid=$D_GID#$D_GID" title="$I_MOVE_UP_DESC">&uarr;<!--<small>$I_MOVE_UP </small>--></a>
    </td>
     </tr><tr><td></td></tr>
     <tr><td>
     <a href="modify_gallery.php?action=move_down&amp;gid=$D_GID#$D_GID" title="$I_MOVE_DOWN_DESC">&darr;<!--<small>$I_MOVE_DOWN</small> --></a>
    </td></tr></table>

     </td>



     <td><img src="$D_THUMB" alt="($D_THUMB)"></td>

<td>
<div>     
<a href="modify_gallery.php?action=view_info&amp;gid=$D_GID">$I_MOD_INFO</a><br />  
     <a href="modify_gallery.php?action=sel_thumb&amp;gid=$D_GID">$I_SEL_THUMB</a><br />
     <a href="modify_gallery.php?action=view_phids&amp;&gid=$D_GID">$I_MOD_PHIDS</a><br />
     <a href="delete_gallery.php?action=list&amp;gid=$D_GID">$I_DEL_STUFF</a>
</div>
  </td>
  </tr></table>

    <hr />
SGZ;

?>