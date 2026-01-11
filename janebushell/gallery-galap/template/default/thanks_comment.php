<?php

/**
 * After adding a comment this page is shown
 * Used by add_comment.php
 * 
 * @package template
 */

echo <<<THANKS
  <html>
<!-- thanks_comment BEGIN -->
  <head>
  <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
  <meta http-equiv="Refresh" content="2; url=$D_REFRESH_URL" />
  <title>$I_THANKS</title>
  </head>
  <body>
  <div style="background-color: #EBEBEB; border-top: 1px solid #FFFFFF; border-left: 1px solid #FFFFFF; border-right: 1px solid #AAAAAA; border-bottom: 1px solid #AAAAAA;">
  <h4>$I_THANKS</h4>
  <p>$I_ADDED</p>
  <pre>
  <b>$I_TITLE</b>: $D_TITLE
  <b>$I_AUTHOR</b>: $D_AUTHOR
  <b>$I_EMAIL</b>: $D_MAIL
  <b>$I_WEB</b>: $D_WEB
  <b>$I_MESSAGE</b>:
  $D_MESSAGE</pre>
  <p>$I_IF_NOT_REFRESHED <a href="$D_REFRESH_URL">$I_PRESS_HERE</a></p>

  </div>
  </body>
  <!-- thanks_comment END -->
  </html>
THANKS;

?>
