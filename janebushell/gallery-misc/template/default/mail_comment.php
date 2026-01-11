<?php

/**
 * After adding a comment you can send this email
 * Used by add_comment.php
 * 
 * @package template
 */

$mail_contents=<<<MC
  
  -----------------------------------------------------------
  $I_GALLERY: $D_GALLERY
  $D_LINK
  
  ------------------------------------------------------------
  $I_ADDED
  
  $I_TITLE: $D_TITLE
  $I_AUTHOR: $D_AUTHOR
  $I_EMAIL: $D_MAIL
  $I_WEB: $D_WEB
  $I_MESSAGE:
  $D_MESSAGE
  --------------------------------------------------------------
  User-Agent: $D_UA
  IP: $D_IP
  
MC;

?>
