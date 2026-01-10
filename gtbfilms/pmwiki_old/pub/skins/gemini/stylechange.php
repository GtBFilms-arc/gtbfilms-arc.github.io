<?php if (!defined('PmWiki')) exit();
/*  
    stylechange.php   - A part of GeminiTwo and FixFlow skins -
    
    This script enables various skin configurations using alternative css files.
    It uses five sets of css file options, to set fonts, colors, the rightbar, 
    the menu behaviour and different layouts. It uses five sets of cookies. 
    The various options are defined in skin.php 
*/

global $Now, $LayoutCss, $MenuCss, $FontCss, $RightbarCss, $ColorCss;

# set cookie expire time (default 1 year)
SDV($LayoutCookieExpires,$Now+60*60*24*365);
SDV($MenuCookieExpires,$Now+60*60*24*365);
SDV($RightbarCookieExpires,$Now+60*60*24*365);         
SDV($FontCookieExpires,$Now+60*60*24*365);
SDV($ColorCookieExpires,$Now+60*60*24*365);

# layout cookie routine
if (isset($_COOKIE['setlayout'])) $sl = $_COOKIE['setlayout'];
if (isset($_GET['setlayout'])) {
  $sl = $_GET['setlayout'];
  setcookie('setlayout',$sl,$LayoutCookieExpires,'/');}
if (isset($_GET['layout'])) $sl = $_GET['layout'];
if (@$PageLayoutList[$sl]) $LayoutCss = $PageLayoutList[$sl];
else $sl = $DefaultLayout;

# menu cookie routine
if (isset($_COOKIE['setmenu'])) $sm = $_COOKIE['setmenu'];
if (isset($_GET['setmenu'])) {
  $sm = $_GET['setmenu'];
  setcookie('setmenu',$sm,$MenuCookieExpires,'/');}
if (isset($_GET['menu'])) $sm = $_GET['menu'];
if (@$PageMenuList[$sm]) $MenuCss = $PageMenuList[$sm];
else $sm = $DefaultMenu;

# rightbar cookie routine
if (isset($_COOKIE['setrb'])) $sr = $_COOKIE['setrb'];
if (isset($_GET['setrb'])) {
  $sr = $_GET['setrb'];
  setcookie('setrb',$sr,$RightbarCookieExpires,'/');}
if (isset($_GET['rb'])) $sr = $_GET['rb'];
if (@$PageRightbarList[$sr]) $RightbarCss = $PageRightbarList[$sr];
else $sr = $DefaultRightbar;

# font cookie routine
if (isset($_COOKIE['setfont'])) $sf = $_COOKIE['setfont'];
if (isset($_GET['setfont'])) {
  $sf = $_GET['setfont'];
  setcookie('setfont',$sf,$FontCookieExpires,'/');}
if (isset($_GET['fonts'])) $sf = $_GET['fonts'];
if (@$PageFontList[$sf]) $FontCss = $PageFontList[$sf];
else $sf = $DefaultFont;

# color cookie routine 
if (isset($_COOKIE['setcolor'])) $sc = $_COOKIE['setcolor'];
if (isset($_GET['setcolor'])) {
  $sc = $_GET['setcolor'];
  setcookie('setcolor',$sc,$ColorCookieExpires,'/');}
  if (isset($_GET['colors'])) $sc = $_GET['colors'];
if (@$PageColorList[$sc]) $ColorCss = $PageColorList[$sc];
else $sc = $DefaultColor;

#####end cookies

?>