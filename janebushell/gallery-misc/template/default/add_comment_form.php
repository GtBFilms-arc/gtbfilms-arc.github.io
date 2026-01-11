<?php

/**
 * Form that appears in each image page. This is a simple form
 * for posting comments.
 * 
 * Used by view_func.php
 * 
 * $I_* text translated into config selected language
 * 
 * $D_URL: Destination URL
 * 
 * @package template
 */

echo <<<FORMU
<!-- add_comment_form begin -->
 <h1>$I_ADD_COMMENT</h1>
<div>
<form method="post" action="$D_URL"  onsubmit="javascript:return formCheck(this)">
  <table width="90%" cellspacing="2" cellpadding="0" class="commentsform">
    <tr>
      <td  valign="top">* $I_TITLE:</td>
      <td>
        <input type="text" name="tit" size="40" value="$D_TITLE" maxlength="40" />
      </td>
    </tr>
    <tr>
      <td valign="top">* $I_AUTHOR:</td>
      <td>
        <input type="text" name="aut" size="15" value="$D_AUTHOR" maxlength="30" /> 
        $I_EMAIL: 
        <input type="text" name="mail" size="15" value="$D_EMAIL" maxlength="40" />
      </td>
    </tr>
    <tr>
      <td valign="top">$I_WEB:</td>
      <td>
        <input type="text" name="web" size="30" value="$D_WEB" maxlength="30" />
      </td>
    </tr>
    <tr>
      <td valign="top">*$I_COMMENT:</td>
      <td>
          <textarea name="msg" cols="60" rows="5">$D_COMMENT</textarea>
      </td>
    </tr>
    <tr>
      <td valign="top">&nbsp;</td>
      <td>
      <input type="hidden" name="date" value="$D_DATE" />
        <input type="submit" name="send" value="$I_SEND" class="formbutton" />
        <small>[$I_REQUIRED] </small></td>
    </tr>
  </table>
</form>
</div>
  <!-- add_comment_form end -->
FORMU;

?>
