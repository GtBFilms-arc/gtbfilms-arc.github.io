<?
$blf = "cdata.html";
$ctext = "*Chat window refreshed by admin*<br>\n";
$file = fopen($blf, 'w');
fwrite($file, $ctext);
fclose($file);

echo "Chat window refreshed successfully. <a href=admin.php>Back</a>";
?>
