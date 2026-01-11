<?php

/**
 * Galleries Index. 
 *  
 *  This template file is used to show information
 *  about one single gallery. 
 * 
 *  Variables:
 *  $I_*: Internationalization. Translation. ### Todo put a link 
 * 
 *  - $D_URL: Destination URL
 *  - $D_THUMB: Thumbnail
 *  - $D_AUTHOR: Author of the gallery.
 *  - $D_NUMBER_OF_IMAGES: Number of images the gallery has.
 *  - $D_COMMENTS: Total number of comments the gallery has.
 *  - $D_DATE: When was this gallery modified or created.
 *  - $D_DESC: Description of the gallery.
 *  - $D_PASSWORD_FORM: Only shown if gallery requires authentification.
 *  - $D_ACTIONS: Only used by some admin tasks
 *  - $D_SSURL: Slideshow url
 * @package template
 */


echo<<<GID_INDEX

  <table class="gidindex"> 
  <tr>
  <td class="gidindextd"><a href="$D_URL" title="$I_VIEW_GALLERY"><img 
  src="$D_THUMB" alt="($I_GALLERY $D_GID)" class="thumb" /></a>
  </td>
  <td>
  <div class="gidindexdata">
  <b>{$I_TITLE}:</b> <strong><a href="$D_URL" title="$I_VIEW_GALLERY">$D_TITLE</a></strong> <small>(<a href="$D_SSURL" id="popuplnk" target="Slideshow" onclick="slideshow();" title="$I_SSHOW" >$I_SSHOW</a>)</small><br />
  <b>{$I_AUTHOR}:</b> $D_AUTHOR<br />
  <b>{$I_NUMBER_IMAGES}:</b> $D_NUMBER_IMAGES<br />
  <b>{$I_VISITS}:</b> $D_VISITS  <b>{$I_COMMENTS}:</b> $D_COMMENTS<br />
  <b>{$I_DATE}:</b> <small>$D_DATE</small><br />
  <b>{$I_DESCRIPTION}:</b><br />
  <i>$D_DESC</i>
  $D_PASSWORD_FORM
  $D_ACTIONS
  </div>
  </td>
  </tr>
  </table>
  <!-- gid_index END -->
  
GID_INDEX;

?>
