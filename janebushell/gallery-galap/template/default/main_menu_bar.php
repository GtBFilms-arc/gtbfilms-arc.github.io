<?php
/**
 * Prints Main Menu bar
 * Called from functions.php
 *
 * @package template
*/
	
echo<<<MAINMENUBAR
	<!-- BEGIN main_menu_bar -->
<div class="main">
    <table  style="width:98%"><tr>
     <td>
          <b>$I_MAIN_MENU</b>:	
	  <a href="gallery.php" title="$I_GALLERY_INDEX">$I_GALLERY_INDEX</a> |
	  <a href="$D_HOME_PAGE" title="$I_HOME_PAGE">$I_HOME_PAGE</a> |
     </td>
     <td style="text-align:right">
              | <a href="admin.php" title="$I_ADMIN">$I_ADMIN</a> | 
          $D_ADMIN_LOGOUT
          $D_GALLERY_LOGOUT
    </td></tr></table>
</div>
	<!-- END main_menu_bar -->
	
MAINMENUBAR;
?>
