<?php

/**
 * Login FORM for admin user.
 * 
 * Used by admin.php 
 *
 * @package template
 */
echo <<<LOGIN
  <!-- login_form BEGIN -->
      <h4>$I_LOGIN:</h4>
      <form name="adminform" method="post" action="admin.php">
      <table><tr><td>
      {$I_USER}:
          </td><td>
             <input type="text" value="" name="username" />
      </td></tr><tr><td>{$I_PASSWORD}:</td>
            <td>
             <input type="password" value="" name="userpass" />
            </td>
            </tr>
       <tr><td></td><td><input type="submit" value="$I_SUBMIT" class="formbutton" /></td></tr>
        </table>
  <script type="text/javascript">
  document.adminform.username.focus();
  </script>
        </form>
  
  <!-- login_form END -->
LOGIN;
?>
