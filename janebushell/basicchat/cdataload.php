<?
include("chatconfig.php");
?>
<meta http-equiv="Refresh" content="<? echo $refresh; ?>">
<?
if (!file_exists("users/" . $_COOKIE['cookie_dschat'] . "")){
echo "You've been kicked from the chat by the admin!";
exit;
}
?>
<body  bgcolor="<? echo $col1 ?>">
<IFRAME SRC="cdata.html" TITLE="dataload" frameborder=0 scrolling="auto" width="420" height="320">
<!-- Alternate content for non-supporting browsers -->
<h3>WARNING: <BR>
YOUR BROWSER DOES NOT SUPPORT IFRAMES,
<BR>
AND THEREFOR WILL NOT ALLOW DSCHAT TO FUNCTION CORRECTLY.<br><br>
TURN FRAMES ON TO VIEW DSCHAT CORRECTLY.
</IFRAME>
</body>

