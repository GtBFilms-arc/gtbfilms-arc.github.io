<?
include("chatconfig.php");
?>
<body  bgcolor="<? echo $col1; ?>">
<table>
<tr>
       <td><IFRAME SRC="cdataload.php" TITLE="centerfile" frameborder=0 scrolling="auto" width="456" height="350">
<!-- Alternate content for non-supporting browsers -->
<h3>WARNING: <BR>
YOUR BROWSER DOES NOT SUPPORT IFRAMES,
<BR>
AND WILL NOT ALLOW DSCHAT TO FUNCTION CORRECTLY.<br><br>
TURN FRAMES ON TO VIEW DSCHAT CORRECTLY.
</IFRAME></td>
</tr>
<tr>
       <td><hr><form method="POST" action="send.php"><input name="ctext" type="text" value="" size=63> <input type="submit" value="Send"></form></td>
</tr>
</table>
</body>
