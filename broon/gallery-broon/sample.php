<html><head><title>Sample Last Gallery</title></head><body>
<h1>Link Yapig from other pages</h1>
<p>Here you have a sample of how you can link your gallery from
other pages</p>
<h4>Link to the gallery Index</h4>			

<?php
//Path to Yapig
$YAPIG_PATH='./';
require_once($YAPIG_PATH .'last_gallery.php');
//the argument must be 'index' if you want a link to the gallery index
last_gallery('index');
?>
	
<h4>Link to view the index of thumbnails of the last gallery added</h4>
	
<?php	
//the argument must be 'gallery' if you want a link to the index
//of thumbnails of the last gallery added.
last_gallery('gallery');
?>
	
</body>
</html>		
