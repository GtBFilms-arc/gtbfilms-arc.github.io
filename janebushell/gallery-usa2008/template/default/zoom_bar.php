<?php

echo<<<ZOOMBAR

<!-- zoom_bar.php begin -->
<script type="text/javascript">

//You can control this parameter changing $MAX_IMG_SIZE value on config.php. 
//Set it to 0 to avoid autoresizing.

var max_size=$D_MAX_IMG_SIZE;  
</script>

   <div class="main" style="text-align:center"> 
   <a href="#img" onclick="zoom('myimage',2)">$D_ZOOM_IN</a> |
   <a href="#img" onclick="setsize('myimage',$D_WIDTH,$D_HEIGHT)">$D_REAL</a> |
   <a href="#img" onclick="zoom('myimage', 0.5)">$D_ZOOM_OUT</a> |
</div><!--zoom_bar.php end -->
ZOOMBAR;

?>
