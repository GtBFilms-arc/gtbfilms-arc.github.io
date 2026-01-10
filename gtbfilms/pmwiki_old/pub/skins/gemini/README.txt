
#### Installation instructions for GeminiTwo skin ####

Install in the usual way by unzipping into skin directory.
A directory named gemini will be created with all the files.
Upload the files in this folder and its subfolders.

If you have installed a previous version of GeminiTwo then please rename this skin directory first.
You may later want to delete it. Version 9 has a number of files renamed, so an installation 
on top of the previous one is not recommended

I advise to read the following instructions first and create the foot menu and top menu pages 
first using your current skin or the pmwiki default skin, since these pages are needed
to display the links for edit, history, print view and other page-actions.
See the sample page contents below.

FixFlow and GeminiTwo share the same menu pages and RightBar pages
and can co-exist together. They share the same cookies for setting style options,
so a colour or font change in FixFlow effects also GeminiTwo.


##### Files included #####

README.txt        this readme file.
skin.php          skin script with lots of configuration settings.
skin-gemini.tmpl skin html template file.
stylechange.php   script for changing the style options, based on setting cookies.
layout-main.css   main layout css file (Cascading Style Sheet).
layout-standard.css   layout css file for layout with standard wide header.
layout-smallheader.css  layout css file for layout with small header on top of the sidebar.
layout-print.css   Layout for printing.
c-*.css files for various colour schemes.
font-*.css files for various font schemes.
rb-*.css files for various rightbar styles.
images/*.gif *.jpg images for various colour schemes in image folder.
wikilib.d/* default configuration pages:
   Site.Gemini-Configuration  with links to the other configuration pages and basic configurations
   Site.Gemini-EditForm default edit form
   Site.PageTopMenu     default top menu with action links
   Site.PageFootMenu    default foot menu with action links
   Site.PageHeader      default header with pmwiki logo
   Site.PageFooter      default footer
   Site.StyleOptions    page to switch style options

##### Editing configuration files #####

To set up GeminiTwo as the default skin add to config.php:
$Skin = 'gemini';

If you use skinchange.php add 'gemini' => 'gemini', to the $PageSkinList array.

To set new defaults for font and color scheme you need to edit the skin.php file in skins/gemini.
Near the top you find the lines where you can replace the default file names with the ones
of you preference. you need to enter the exact filename (not the path or folder name).

Underneath are arrays for the various options, which can be expanded to include different custom files for
colour and font schemes, and layouts. 


##### Setting up the pages containing action links ##### 

All the page actions are configured on wiki pages, not in the template, so they are easily customisable.
If you wish your site to look more "normal" without displaying any edit links you can remove these.
This will just hide the links, but is not a replacement for proper site security, in case that is needed.
You can also add your favourite links etc. You are not restricted to page actions!

Site.PageFootMenu and Site.PageTopMenu are installed automatically, but will not overwrite existing versions,
and can subsequently be edited and configured to your needs.

Note: "Print View" is not necessary any longer, since the skin supports a special print layout, 
so one can go straight to the browsers Print or print Preview function. 
 
##### Setting up the Header (header title and logo) ######

Previously the template contained a link to a logo image, and its location was specified
in config.php by setting $PageLogoUrl. This can still be done, but with FixFlow 2 onwards 
the header can also be configured from a wiki page (this has preference to the $PageLogoUrl
setting):

Site.PageHeader is installed automatically with a default pmwiki logo. It will not overwrite
an existing version. Edit it to include a link to your logo image and/or any header title text.
Example of logo image, linking to Main.Homepage, and uploaded to the Site group:

[[Main.HomePage| Attach:Site/PageHeader/logo.gif]]

Best effect is achieved by creating a transparent gif image for the logo. 
To fit the logo into the sidebar it should not be wider than 160px.
A short texttitle can be used instead, or in addition, to a logo.

PageHeader pages can also be created in wiki groups, and any such page will be displayed 
instead of the default page in the Site group. This allows for customising the header 
for various groups.


##### Setting up a Page Footer ######

Site.PageFooter is installed automatically, but will not overwrite an existing version.

Edit the page for any wiki-site wide footer information 
(like copyright notices etc). Groups can also have their own PageFooter pages for showing
group-specific footer information.


###### Setting up different defaults in skin.php #####

Different defaults can be set in skin.php by setting different keywords for the $Default*** 
variables near the top of the file. 

Style options can be limited by commenting out lines from the option arrays ($PageColorList etc)
or expanded by adding new options and adding new css files for colour or font schemes.

Style switching (using the cookie script stylechange.php) can be disabled by setting 
$EnableStyleOptions = 0;

The display of the group name in the titlebar can be disabled by setting
$EnableGroupTitle = 0;

The group name (as link) can be alternatively displayed in the top menu bar by adding
*[[{$Group}]] to the PageTopMenu page. Likewise adding *{$Name} adds the full page name
of the current page to the top menu, which may be different from the page title displayed 
in the titlebar.

The display of the small searchbox at the top of the sidebar can be disabled by setting
$EnableSidebarSearchbox = 0;

As an alternative a small searchbox can be added anywhere into the sidebar (for instance 
the bottom of the sidebar), or the top menu, or foot menu, by adding markup (:searchbox:) 
to the relevant configuration page.
If alternative skins are used for the wiki and a searchbox added through (:searchbox:) markup,
this searchbox can be prevented from showing in the other skins by doing the following:
Add the markup to the sidebar in this way:
(:if enabled smallsearchbox:)(:searchbox:)(:if:)
Define in config.php the 'if enabled' conditional in this way:
# adds 'enabled' conditional markup:
$Conditions['enabled'] = "(boolean)\$GLOBALS[\$condparm]";

A RightBar page can be shown for all pages by setting
SetTmplDisplay('PageRightFmt', 1);
You can still disable the rightbar for selected pages using markup (:noright:).


###### Style options switching  #####

The page Site.StyleOptions can be used to switch to different styles.
It installs automatically but will not overwrite an existing version.
If you upgrade the skin and more styles are available, you should first delete
your existing version of Site.StyleOptions (or rename it).

Style option switching can be disable din skin.php 
by setting $EnableStyleOptions =0;


##### Setting up RightBar pages #####

For the right bar box you can create a page called Site.RightBar
and add content there, or use (:include ..mypage.. :) to add content from another
page there (I sometimes use a page Main.LatestNews to be included in the RightBar)
You can also create group-specific RightBar pages: Group.RightBar. and even 
page-specific RighBar pages by appending"-RighBar" to the page name, so the RightBar 
page is called Group.Pagename-RightBar.

The rightbar is displayed as default only for specific pages or groups which have the 
custom markup (:showright:) included.
To show the rightbar as default on all pages change in skin.php SetTmplDisplay(...)  to
SetTmplDisplay('PageRightFmt', 1);

A small red cross gif image redbt.gif is included in the image folder, which could be used to create 
a visual button for switching the rightbar off. 
Upload the image to the 'Site' group, and in the page RightBar put:
  %align=right%[[{$Name}?rb=0 | Attach:Site/redbt.gif"Close Rightbar"]] %%
The ?rb=0 switches the page to show it with no rightbar displayed. If you click on the page title 
to reload the page, or go to a different page, the rightbar will be shown again.


#### Creating page titles with non-standard (fancy) fonts ######

The normal page titles can be replaced with images of non-standard (fancy) fonts, by creating a page
with the string "-TitleBar" added to the name of the page, like "MyPage-TitleBar". Put the image as 
attachment on this page, without any lines before or after (for instance: Attach:myfancyfonttitle.gif).
Best is to create a gif image of the title in special font, using a transparent background, in  
an image editor. A page named as described above will take the place of the normal page title, 
in the space of the titlebar. 

##### Loading order of configuration pages #####

PmWiki will use any group specific pages for page actions, right bar, page footer and header, 
like Mygroup.RightBar, Mygroup.PageTopMenu, Mygroup.PageFootMenu, Mygroup.PageFooter, MyGroup.PageHeader,
in preference to those pages in the Site group. The new Site group is the preferred
page location for configuration files, as it will receive special protection in future Pmwiki versions.
You can create configuration pages in any group, setting up custom actions for those groups, or custom 
right bars, or custom footers.


##### Special edit form ######

Site.Gemini-EditForm is the page for the special edit form used by the skin.
It can be customised with care.


##### Special directives #####

The directives (:nofooter:), (:noheader:), (:noleft:), (:noright:), (:notitle:) etc work well with this skin.
I added custom markup (:notopmenu:) and (:nofootmenu:), which may disable the top and bottom  page menus, 
defined in Site.PageTopMenu and Site.PageFootMenu. A custom markup (:showright:) can be used on pages or in a 
GroupHeader page to show the rightbar, if the rightbar is by default not shown through 
SetTmplDisplay('PageRightFmt', 0); in skin.php.


##### Theme markup #####

A custom markup (:theme colourscheme fontscheme:) can be used to show individual pages 
(or groups) with different colour and font schemes. For instance 
(:theme sand:) will show the current page in 'sand' colour scheme.
(:theme red-gold comic:) will show the current page in 'red-gold' colour scheme and 
'comic' font scheme.
The first parameter after (:theme needs to be a valid colour scheme name, the second parameter
is optional and need sto be a valid font scheme name (or be omitted).
Theme markup can be disabled by setting $EnableThemes = 0;  
