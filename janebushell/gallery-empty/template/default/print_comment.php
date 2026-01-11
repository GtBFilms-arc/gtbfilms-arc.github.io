<?php

/**
 * This is the format of each comment.
 * 
 * Used by print_comment function in view_funct.php
 * 
 * + D_TITLE = Comment title
 * + D_LINKMAIL = User name + email link if available
 * + I_DATE = 'Date' translated
 * + D_DATE = Comment date 
 * + D_LINKWEB = Web page link if available.
 * + I_COMMENT = 'Comment' translated
 * + D_COMMENT = user comment
 * + ADMIN_HTML = html links for administration tasks
 * @package template
*/

  echo<<<COMENTARIO

     <!--print_comment BEGIN -->
      <div class="comment">
      <b>:: {$D_TITLE}</b> by <i>{$D_LINKMAIL}</i>  {$D_LINKWEB},  <i>$D_DATE</i> $D_ADMIN_HTML<br />
      <div style="margin: 2px;padding: 5px;border-top: thin dotted olive">
      {$D_COMMENT}
      </div>
      
      </div>
      
  <!-- print_comment END -->
  
COMENTARIO;

?>
