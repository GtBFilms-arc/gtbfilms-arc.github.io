<?php if (!defined('PmWiki')) exit();
/*  Copyright 2005 Hans Bracker. 
    This file is skin.php; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published
    by the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.
    skin.php  is part of the gemini and fixflow skins for pmwiki 2.

    This script sets the default styles for gemini or fixflow skins.
    It also defines various style options. All options are loaded via seperate css files,
    one each for colors, fonts, rightbar settings and alternative layouts.  
        
    To set different styles than the defaults it uses stylechange.php.
    For switching styles the following are used, which set cookies :
    ?setcolor=..., ?setfont=..., ?setrb=..., setlayout=...
    The following can be used to change a style for a page, without seting  a cookie:
    ?colors=..., ?fonts=..., ?rb=..., ?layout=...
    The possible parameters are defined below in various lists.
      
    By default, the cookies that are created will expire after
    one year.  To have cookies to expire at the end of the browser
    session, set $FontCookieExpires=0; $ColorCookieExpires=0; 
    $RightbarCookieExpires=0; $LayoutCookieExpires=0;
*/
global $DefaultColor, $DefaultFont, $DefaultLayout, $DefaultMenu, 
        $DefaultRightbar, $HTMLStylesFmt, $EnableStyleOptions, $EnableThemes;
        
# No RightBar by default, RightBar can be shown on individual pages 
# or groups (via group header) by including markup (:showright:) on the page.
# SetTmplDisplay('PageRightFmt', 1); enables RightBar as default.
SetTmplDisplay('PageRightFmt', 0);

# Adds small searchbox to top of sidebar. 
# A searchbox can also be inserted through markup (:searchbox:), 
# which could be added anywhere in the SideBar, PageTopMenu or PageFootMenu.
# In this case you may wish to set
# $EnableSidebarSearchbox = 0;  to disable searchbox in the template.
$EnableSidebarSearchbox = 1;

# Enables grouplink in titlebar; set to 0 for no grouplink in titlebar.
# To see a link to the Group's homepage add *[[{$Group}]] to the PageTopMenu.
# Add *[[{$Name}]] to see the current page name, which may be different than the title
$EnableGroupTitle = 1;

# Set default colors, font and layout schemes here,
# the default loads without any cookie being set.
# Different defaults can be set also in config.php
# or a groups local/GroupName.php (for different coloured groups for instance) 
# by adding lines like  $DefaultColor = 'blue';
SDV($DefaultColor,'blue');
SDV($DefaultFont,'sans');
SDV($DefaultLayout,'smallheader');
#SDV($DefaultMenu,'fixed');    # for FixFlow only!
SDV($DefaultRightbar,'narrow');

# By default style options are enabled, 
# SDV($EnableStyleOptions, 0); #disables option setting via cookies.
SDV($EnableStyleOptions, 1);

# By default markup (:theme colorname fontname:)is enabled, 
# SDV($EnableThemes, 0); #disables theme display.
SDV($EnableThemes, 1);

# Quick way to disable background images:
# If you don't want textured backgrounds you can uncomment the next line
# $HTMLStylesFmt[]="body,#content,#titlebar,#sidebar,#footnav,#pagefooter{background-image:none}\n"; 

# option arrays, these can be expanded with more custom files.
# comment out any options not wanted.
# The keyword (left of arrow) is used to set the default. 
# Right of the arrow is the css file which gets loaded.
global $PageColorList, $PageFontList;
SDVA($PageColorList, array (
        'blue' => 'c-blue.css',
        'sky' => 'c-sky.css',
        'sky-blue' => 'c-sky-blue.css',
        'sand' => 'c-sand.css',
        'silver' => 'c-silver.css',
        'lavender' => 'c-lavender.css',
        'lilac' => 'c-lilac.css',       
        'pink' => 'c-pink.css',
        'red-gold' => 'c-red-gold.css',
        'green-gold' => 'c-green-gold.css',
        'parch-blue' => 'c-parch-blue.css',
        'parch-yellow' => 'c-parch-yel.css',
        'night' => 'c-night-blue.css',
        'stars' => 'c-night-stars.css',
        ));
SDVA($PageFontList, array(
        'sans' => 'font-sans.css',
        'verdana' => 'font-verdana.css',
        'georgia' => 'font-georgia.css',
        'times' => 'font-times.css',
        'palatino' => 'font-palatino.css',
        'monospace' => 'font-lucida.css',
        'courier' => 'font-courier.css',
        'comic' => 'font-comic.css'
        ));
$PageLayoutList = array (
        'standard' => 'layout-standard.css',        # for GeminiTwo only!
        'smallheader' => 'layout-smallheader.css',  # for GeminiTwo only!
#       'left' => 'layout-left.css',        # for FixFlow only!
#       'right' => 'layout-right.css',      # for FixFlow only!         
        );
$PageMenuList = array(
        'fixed' => 'fixed',     # for FixFlow       
        'scroll' => 'scroll'    # for FixFlow
        );
$PageRightbarList = array (
        '0' => 'rb-none.css',
        'off' => 'rb-none.css',
        'on' => 'rb-narrow.css',
        '1' => 'rb-narrow.css',
        'narrow' => 'rb-narrow.css',
        '2' => 'rb-normal.css',
        'normal' => 'rb-normal.css',
        '3' => 'rb-wide.css',
        'wide' => 'rb-wide.css',
        );

# =========== end of configuration section of skin.php ================= #

# define variables
global $Now, $LayoutCss, $MenuCss, $RightbarCss, $FontCss, 
        $ColorCss, $IE6Css, $HTMLStylesFmt, $SkinDir;
$sc = $DefaultColor;
$sf = $DefaultFont;     
$sl = $DefaultLayout;
$sm = $DefaultMenu;
$sr = $DefaultRightbar;
$ColorCss = $PageColorList[$sc];
$FontCss = $PageFontList[$sf];
$LayoutCss = $PageLayoutList[$sl];
$RightbarCss = $PageRightbarList[$sr];

# add stylechange.php for cookie setting code if set.
if ($EnableStyleOptions == 1) {include_once("$SkinDir/stylechange.php");};

# do not show rightbar box if RightBar is empty
global $HTMLStylesFmt; 
$prb = FmtPageName('$FullName-RightBar',$pagename);
$grb = FmtPageName('$Group.RightBar',$pagename);
$srb = FmtPageName('$SiteGroup.RightBar',$pagename);
$rpage = array();
if (PageExists($prb)) $rpage = ReadPage($prb);
if (PageExists($grb)) $rpage .= ReadPage($grb);
if (PageExists($srb)) $rpage .= ReadPage($srb);
if ($rpage['text']=='') {$HTMLStylesFmt[] = " #rightbar { display:none } \n";};

/* ==== for Fixflow only! ====
# switch from default fixed menu to scrolled menu
if ($sm == 'scroll') { 
        $HTMLStylesFmt[] = " #sidebarbox, #header {position: absolute;} \n
                              body, #wrapper { background-attachment: scroll } \n ";
        };
if ($sm == 'fixed') { $HTMLStylesFmt[] = " body, #wrapper { background-attachment: fixed } \n ";
        };

# for fixFlow only!
# IE6 css hack switch:
if ($sl == 'right') {
        $IE6Css = 'ie6fix-right.css';
        if ($sm == 'scroll') $IE6Css = 'ie6scroll-right.css'; 
        };
if ($sl == 'left') {
        $IE6Css = 'ie6fix-left.css';
        if ($sm == 'scroll') $IE6Css = 'ie6scroll-left.css'; 
        };
=========== */

# for GeminiTwo only! smallheader logo-position switching
if ($LayoutCss==$PageLayoutList['smallheader']) { SetTmplDisplay('PageHeaderFmt', 0); };
if ($LayoutCss==$PageLayoutList['standard']) { $HTMLStylesFmt[]="#sideheader {display:none}\n"; };

# logo switchlogic
# displays if exist page PageHeader, otherwise $PageLogoUrl defined in config.php
global $LogoFmt;
$LogoFmt = "
    <a href='\$ScriptUrl'><img src='\$PageLogoUrl' alt='\$WikiTitle' border='0' /></a> 
    ";
$ghdr = FmtPageName('$Group.PageHeader',$pagename);
$shdr = FmtPageName('$SiteGroup.PageHeader',$pagename);
if (PageExists($shdr)) $LogoFmt = "";
if (PageExists($ghdr)) $LogoFmt = "";

## Defines sidebar searchbox for template:
global $SearchBoxFmt, $SearchTagFmt, $smallsearchbox;
$SearchBoxFmt = 
      "<form class='wikisearch' action='\$PageUrl' method='get' >
      <input type='hidden' name='n' value='\$FullName' />
      <input type='hidden' name='action' value='search' />
      <input class='inputbox wikisearchbox' type='text' name='q' size='16' value=' $[Search Site] ' 
      onfocus=\"if(this.value=' $[Search Site] ') {this.value=''}\" onblur=\"if(this.value=='') 
           {this.value=' $[Search Site] '}\" />
      <input name='submit' type='submit' class='inputbutton wikisearchbutton' value=' $[Go] '/>
      </form>";
$SearchTagFmt = $SearchBoxFmt;
if ($EnableSidebarSearchbox == 0) { 
            $SearchTagFmt = "";
            $smallsearchbox = 1;
            };
    
# changes to extended markup recipe for selflink definition:
global $LinkPageSelfFmt;
$LinkPageSelfFmt = "<a class='selflink'>\$LinkText</a>";

# switch to hide group-link in titlebar
global $PageGroup;
$PageGroup = FmtPageName('',$pagename);
if ($EnableGroupTitle == 1) { $PageGroup = FmtPageName('$Groupspaced',$pagename); }
else { $PageGroup = FmtPageName('',$pagename); };

# Markup nopagegroup to hide group in titlebar 
function NoPageGroup() {
        global $PageGroup;
        $PageGroup = FmtPageName('',$pagename);
        return ''; } 
Markup('nogroup','directives','/\\(:nogroup:\\)/e',
  "NoPageGroup()");
  
#adding switch for 'Pagename-Titlebar' subpage for fancy font titlebars
$ftb = FmtPageName('$FullName-TitleBar',$pagename);
if(PageExists($ftb))  $HTMLStylesFmt[] = " .titlelink { display:none } \n ";

/* for fixFlow only!
## set scrollswitch to enable showing Menu Scrolled/Fixed links in sidebar 
global $scrollswitch;
$scrollswitch = 1;
*/
/* needed for FixFlow!
## Redefinition of markup noleft
function NoLeftBar() {
     global $HTMLStylesFmt, $PageLeftFmt;
     SetTmplDisplay('PageLeftFmt',0);
     $HTMLStylesFmt[] = " #main { margin-left:0; width:100%; padding-right:0;} #sidebar {margin-left:-500px} \n ";
     return '';
   }
Markup('noleft','directives','/\\(:noleft:\\)/e', "NoLeftBar()"); 
*/

## Markup showright, (never shows RB in edit mode)
global $action;
if ($action != 'edit') {
Markup('showright','directives','/\\(:showright:\\)/e',
  "SetTmplDisplay('PageRightFmt', 1)"); };

## Markup notopmenu
function NoTopMenu() {
    global $HTMLStylesFmt, $PageTopMenuFmt;
    SetTmplDisplay('PageTopMenuFmt',0);
    $HTMLStylesFmt[] = "  #titlebar { margin-top:5px } \n ";
    return '';
    }
Markup('notopmenu','directives','/\\(:notopmenu:\\)/e', "NoTopMenu()"); 
  
## Markup nofootmenu
Markup('nofootmenu','directives','/\\(:nofootmenu:\\)/e',
  "SetTmplDisplay('PageFootMenuFmt', 0)"); 

## removing rightbar, header, title for history and uploads windows
global $action;
if ($action=='diff' || $action=='upload') { 
            SetTmplDisplay('PageRightFmt', 0);
            SetTmplDisplay('PageHeaderFmt', 0);
            SetTmplDisplay('PageTitleFmt', 0);
    };

## alternative Diff (History) form with link in title
global $PageDiffFmt, $PageUploadFmt;
$PageDiffFmt = "<h2 class='wikiaction'>
  <a href='\$PageUrl'> \$FullName</a> $[History]</h2>
  <p>\$DiffMinorFmt - \$DiffSourceFmt - <a href='\$PageUrl'> $[Cancel]</a></p>";

## alternative Uploads form with link in title 
$PageUploadFmt = array(
  "<div id='wikiupload'>
  <h2 class='wikiaction'>$[Attachments for] 
  <a href='\$PageUrl'> \$FullName</a></h2>
  <h3>\$UploadResult</h3>
  <form enctype='multipart/form-data' action='\$PageUrl' method='post'>
  <input type='hidden' name='n' value='\$FullName' />
  <input type='hidden' name='action' value='postupload' />
    <p align='right' style='float:left'>$[File to upload:]<input
      name='uploadfile' type='file' size=60 /><br />
     $[Name attachment as:]
      <input type='text' name='upname' value='\$UploadName' size=25 />
        <input type='submit' value=' $[Upload] ' /><br />
        </form></div><br clear=all/><br>",
  'wiki:$[PmWiki.UploadQuickReference]');
######

## Add a (:theme colorname fontname:) markup
function SetTheme($sc,$sf) {
    global $ColorCss, $PageColorList, $FontCss, $PageFontList, $HTMLStylesFmt;
    if (@$PageColorList[$sc]) $ColorCss = $PageColorList[$sc];
    if($sf) {
     if (@$PageFontList[$sf]) $FontCss = $PageFontList[$sf];};
};
if($EnableThemes == 1) {
Markup('theme', 'fulltext',
  '/\\(:theme\\s+([-\\w]+)\\s*([-\\w]*)\\s*:\\)/e',
  "PZZ(SetTheme('$1', '$2'))"); }
else {
Markup('theme', 'fulltext',
  '/\\(:theme\\s+([-\\w]+)\\s*([-\\w]*)\\s*:\\)/e',
  "");
};

## automatic loading of skin default config pages
global $WikiLibDirs, $SkinDir;
    $where = count($WikiLibDirs);
    if ($where>1) $where--;
    array_splice($WikiLibDirs, $where, 0, 
        array(new PageStore("$SkinDir/wikilib.d/\$FullName")));
      
global $XLLangs, $PageEditForm; 
SDV($PageEditForm,'Site.Gemini-EditForm');
XLPage('gemini', 'Site.Gemini-Configuration' );
   array_splice($XLLangs, -1, 0, array_shift($XLLangs));

?>