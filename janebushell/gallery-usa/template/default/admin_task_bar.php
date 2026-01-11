<?php
/**
 * Administrator Task Bar. Shows all available tasks the admin can do 
 *
 * Used in admin_func.php
 * 
 * - $I_UPLOAD upload files
 * - $I_ADMIN_TASKS admin tasks
 * - $I_ADD add gallery 
 * - $I_MODIFY modify gallery
 * - $I_DELETE delete gallery
 * - $I_CHANGE change configuration
 * - $I_LOGOUT admin logout
 * @package template
 */

echo <<<MENU
  <!-- admin_task_bar BEGIN -->
  <div class="admin">
    <strong>$I_ADMIN_TASKS</strong>:
       <a href="upload.php">$I_UPLOAD</a> |
       <a href="admin.php?action=add">$I_ADD</a> |
       <a href="modify_gallery.php">$I_MODIFY</a> |
       <!-- <a href="admin.php?action=delete">$I_DELETE</a> | -->
       <a href="admin.php?action=change">$I_CHANGE</a> |
       <a href="admin.php?action=logout">$I_LOGOUT</a>
       </div>
  <!-- admin_task_bar END -->

MENU;

?>
