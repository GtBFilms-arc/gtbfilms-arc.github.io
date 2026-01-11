<?php
/**
 * 
 * @package template
 *
 * This file defines the contents of default index.html contents
 *
 * For avoiding fancy indexing of http server, yapig creates a file
 * called index.html witch has the contents of the INDEX_FILE variable
 * 
 * - $D_REFRESH_URL In default template this file contanins a meta 
 *                    refresh. This is the destination url.
 *
 * Internationalization strings:
 * 
 *  - $I_IF_NOT_REFRESHED 'If the page is not automatically refreshed.
 *  - $I_PRESS_HERE  'Press here';
 *                  
 *
 **/

$INDEX_FILE=<<<HERE_CONTENTS

<html>
<!-- index_file  BEGIN -->
  <head>
  <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
  <meta http-equiv="Refresh" content="2; url=$D_REFRESH_URL" />
</head>
<body>
  <div style="background-color: #EBEBEB; border-top: 1px solid #FFFFFF; border-left: 1px solid #FFFFFF; border-right: 1px solid #AAAAAA; border-bottom: 1px solid #AAAAAA;">
  <p>$I_IF_NOT_REFRESHED <a href="$D_REFRESH_URL">$I_PRESS_HERE</a></p>
  </div>
  </body>
  <!-- index_file END -->
  </html>

HERE_CONTENTS;

?>