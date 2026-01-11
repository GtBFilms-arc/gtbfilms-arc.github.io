<?php

echo <<<MOD_INFO

<!-- modify_phid_info.php BEGIN -->
<h2>$I_MOD_IMG</h2>
<table class="gidindex">
<tr>
    <td class="gidindextd">
    <img src="$D_THUMB_PATH" alt="($D_IMG_PATH)" />   
   </td>
   <td>
<div class="gidindexdata">
<b>{$I_PHID}</b>: $D_PHID <br />
<b>{$I_IMG_NAME}</b>: $D_IMG_PATH [<a href="$D_URL_REMOVE">$I_REMOVE</a>]<br />
<b>{$I_NUM_COMMENTS}</b>: $D_PHID_COMMENTS 
                         [<a href="$D_URL_CLEAR_COMMENTS">$I_CLEAR</a>]<br />
<b>{$I_NUM_VISITS}</b>: $D_PHID_VISITS 
                         [<a href="$D_URL_CLEAR_VISITS">$I_CLEAR</a>]<br />
<b>$I_ROTATE:</b> [<a href="$D_ROTATE_90_URL">+90</a>] 
                  [<a href="$D_ROTATE_N90_URL">-90</a>]<br />

[<a href="$D_URL_SET_THUMB">$I_SET_THUMB</a>]<br />


<form method="post" action="$D_MOD_CAPTION_URL">
<b>{$I_CAPTION}</b>:<input name="caption" type="text" size="60" maxsize="80" value="$D_CAPTION" />
  <input type="submit" name="send" class="formbutton" value="$I_CHANGE_CAPTION" />
</form>

</div>
    </td>
</tr></table>
<!-- modfy_phid_info.php END -->

MOD_INFO;
?>