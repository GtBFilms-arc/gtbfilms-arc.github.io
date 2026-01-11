<?php
/**
 * Admin form. This form is used in YaPiG's admin when administrator
 * wants to create a new gallery or modify information of an existent
 * gallery.
 * 
 * Used by admin_func.php
 * 
 * Variables:
 * $I_* Strings translated into config.php selected language.
 * 
 * @package template
 */

    echo <<<ADD_FORM
      <!- gallery_form BEGIN -->
      <form method="post" action="{$D_ACTION}">
      <table>
      <tr>
      <td>{$I_GALLERY_TITLE}:</td>
      <td>
      <input type="text" value="$D_TITLE" name="title" />
      </td>
      </tr>
      
      <tr>
      <td>{$I_AUTHOR}:</td>
      <td>
      <input type="text" value="$D_AUTHOR" name="author" />
      </td>
      </tr>
      
      <tr>
      <td>{$I_DATE}</td>
      <td>
      <input type="text" value="$D_DATE" name="date" /> 
      <small>($I_DATE_WARNING)</small>
      </td>
      </tr>

      <tr>
      <td>{$I_LOCATION}:</td>
      <td>
      $D_BASE_DIR <input type="text" value="$D_DIR" name="dir" />
      </td>
      </tr>
      
      <tr>
      <td>{$I_PASSWORD}:</td>
      <td>
        <input type="password" value="$D_PASSWORD" name="gallery_password" /> <small>($I_PASS_WARNING)</small>
      </td>
      </tr>

      <tr>
      <td>{$I_DESCRIPTION}:</td>
      <td>
        <textarea name="desc" cols="50" rows="5">$D_DESCRIPTION</textarea>
      </td>
      </tr>

      <tr>
      <td>{$I_NO_COMMENTS}:</td>
      <td>
      <input type="checkbox" name="no_comments" $D_CHECKED />
      </td>
      </tr>

      $replace_thumbs

      <tr>
      <td valign="top">&nbsp;</td>
      <td>
      <input type="submit" name="send" value="$I_SEND" class="formbutton">
      </td>
      </tr>
    </table>

      </form>
  <!-- gallery_form END -->
ADD_FORM;

?>
