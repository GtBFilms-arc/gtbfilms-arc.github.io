<?php
/**
 * Previous, up and next navigation bar.
 * 
 * This file is used when need to print a navigation bar with links
 * to previous resource, next resource and up level.
 * 
 * @package template
 */

echo <<<NV
  <!-- prev_up_next_bar BEGIN  -->
  <table class="main"  style="width:98%">
  <tr>
  <td style="text-align:left">&laquo;&laquo; $D_PREV</td>
  <td style="text-align:center"> $D_PAGE  $D_UP  $D_SSHOW  </td>
  <td style="text-align:right">$D_NEXT  &raquo;&raquo;</td>
  </tr>
</table>  
  <!-- prev_up_next_bar END -->
  
NV;
?>
