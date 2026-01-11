<?php

/**
 *  Yapig (Yet Another PHP Image Gallery) - Configuration File -
 *  
 *  This file contains all configurable options of Yapig.
 * 
 *  Please read carefully the instructions and complete the config file. 
 *  Don't worry. It will take less than five minutes. Most of values are ok  
 *  by default.
 * 
 *  Admin must change admin login data ($USER and $PASSWORD vairables)
 * 
 * @package admin
 * @author NaTaSaB 
 * @link http://yapig.sourceforge.net
 * @copyright Distributed under GPL License.
 * 
 */

/**
 * Admin Username
 * 
 * This is the name you have to introduce in the admin.php login form. You
 * must change default value. You can configure several admin accounts, 
 * for example:
 * 
 * $USER= array ('admin1','admin2');
 * 
 * --
 * ATENTION: there should be the same number of admins and passwords, if not
 * Yapig might fail.
 * --
 * 
 * @global string $USER
 * @name $USER
 *
 */

$USER='jane';

/**
 * Admin Password
 * 
 * This is the password you have to introduce in the admin.php login form.
 * You must change default value. You can configure severaladmin accounts
 * 
 * $PASSWORD= array('admin1_pass','admin2_pass');
 *
 * @global string $PASSWORD
 * @name $PASSWORD
 */

$PASSWORD='oldboynonsense';

/**
 * Use cookies or sessions
 * 
 * Set this variable to 'false' if you want to use sessions for 
 * authentification issues. Set it to 'true' if you want to use cookies.
 * 
 * By default false is ok. I recommend set it to true only if you
 * have "rare" problems with authentification as admin or in password
 * protected galleries in your server. 
 * For example, you have to use cookies in lycos servers.
 *
 */

$USE_COOKIES=true;

/**
 * Site Title
 *
 * This title will appear in the gallery Index.
 * 
 * NOTE: To use single apostrof, use the character " as string delimiter.
 * Example:
 *       $I_TITLE= "Yapig's Homepage";   
 *    
 * @global string $I_TITLE
 * @name $I_TITLE
 *
 */

$I_TITLE='Janes Australia and New Zealand Photo Gallery';

/**
 * Website Start Page
 * This variable is to have a link to your home page. It can be a relative or
 * complete URI.
 *
 * @global string $HOME
 * @name $HOME
 */

$HOME='http://www.janebushell.co.uk';


/**
 * Base Directory
 *
 * Here is where you have to store your photos and images. Each gallery will
 * be in a different subdirectory of $BASE_DIR. It must be a relative
 * path from Yapig's root directory. 
 * 
 * ATENTION!!!
 * 
 *       + You must run install.php if you modify this directory.
 *       + Must end with the character '/' 
 * 
 * @global string $BASE_DIR
 * @name $BASE_DIR
 */

$BASE_DIR='photos/';

//////////////////////////////////////////////////////////////////////////////
// INTERFACE CONFIG
//////////////////////////////////////////////////////////////////////////////

/**
 * Yapig Language 
 * 
 * Yapig Interface is prepared to be shown in any language. 
 * 
 * Yapig can automatically detect user language. If you want to 
 * use this feature, set $LANG = 'auto' and if Yapig fails it will output
 * text in DEFAULT_LANGUAGE
 * 
 * Default value 'auto'.
 *
 * There is a complete list of supported languages on doc/en/translations.html
 *  
 * @global string $LANG
 * @name $LANG
 * 
 */

$LANG='auto';

/**
 * Default output language if visitor browser language is not available.
 * 
 * There is a complete list of supported languages on doc/en/translations.html
 * 
 * Only used if $LANG = 'auto'
 */
 
$DEFAULT_LANGUAGE='en';


/**
 * Yapig Version.
 * 
 * It is displayed on the footer of the default template. 
 * You might want to set this var to '' for security reasons. 
 * For hackers is more time spending try to explode any vulnerability 
 * not knowing this data
 * 
 */

$VERSION='0.95.0';


/**
 * Template dir
 *
 * This variable stores th name of the directory where the template
 * that is used by Yapig is stored
 * 
 * If you want to create your own template, please read the documentation.
 * Default template location is 'template/default/'. Note: MUST end with '/' 
 * character.
 *
 * @global string $TEMPLATE_DIR
 * @name $TEMPLATE_DIR
 */

$TEMPLATE_DIR='template/default/';


/**
 * Number of columns on gallery index.
 *  
 * You might want to have several columns on the gallery index. 
 * 
 * It must be an integer value greater or equal to 1.
 * 
 * --
 * Note: to set a value greater than 2 you should modify:
 *    - gallery.css:  .gidindextd class
 *    - Thumbnail size in config.php
 *            
 * @global int $INDEX_COLUMNS
 * @name $INDEX_COLUMNS
 */

$INDEX_COLUMNS=1;

/**
 * Number of Colummns
 *
 * This Variable tells Yapig how many columns of thumbnails per row must
 * display on the index of a particular gallery.
 *
 * @global integer $NUM_COLUMNS
 * @name $NUM_COLUMNS
 */

$NUM_COLUMNS=4;

/**
 * Number of files
 * 
 * This tells how many files has each page. 
 * Total images/page is NUM_COLUMS x NUM_ROWS
 * 
 * If NUM_ROWS=0 then only one page is shown.
 * 
 * @global integer $NUM_ROWS
 * @name $NUM_ROWS
 */

$NUM_ROWS=5;

/*
 * Max Thumbnail Size
 *
 * Size of the thumbnails created by the script. In pixels. The bigger they are,
 * the slower will be loaded the gallery.
 *
 * Thumbnails are proportional to original image size, the biggest side of the picture
 * will be set by this variable.
 *
 */

$THUMB_SIZE=160;


/**
 * Maximun image size
 * 
 * if width or height sizes are bigger than this value, when showing image is
 * automatically resized, it will not be bigger than this size. 
 * 
 * Set this value to 0 to avoid automatic resizing. Pixel Units.
 */

$MAX_IMG_SIZE=0;

/**
 * prefix of the thumbnail name
 *
 * The thumbnail name will be with this format:  $THUMB_PREFIX + image.jpg
 *
 * example:
 *   -> $THUMB_PREFIX="t_"
 *   -> image name image.jpg
 * then the thumbnail name:   t_image.jpg
 *
 */
$THUMB_PREFIX='t_';

/*
 * Thumbnail JPEG Quality
 *
 * Range must be an ingeger between 1 and 100, where 100 is the best quality
 * and biggest filesize.
 */

$THUMB_QUALITY=75;


/**
 * Required fields in comments
 * 
 * When an user sends a comment about one image there are five fields.
 * This way you can choose which are required.
 * 
 * For a complete customization you can modify your template files:
 * add_comment_form.php thanks_comment.php and print_comment.php
 * 
 * @name REQ_IN_COMMENTS
 * @global array REQ_IN_COMMENTS comment field required
 */

$REQ_IN_COMMENTS['title']=true;
$REQ_IN_COMMENTS['author']=true;
$REQ_IN_COMMENTS['email']=false;
$REQ_IN_COMMENTS['web']=false;
$REQ_IN_COMMENTS['comment']=true;

/**
 * Admin Email Address
 *  If you want a mail notification on comments posts
 *  change this value.
 *
 * If you want to set a list of mails then use this format:
 *   
 *    $ADMIN_EMAIL= array ('yapig@example.com', 'natasab@example.com');
 * 
 * Note: If MAIL_ON_COMMENT is set true, but you don't 
 * modify this default value Yapig won't send any email.
 */

$ADMIN_EMAIL='alan@broon.co.uk';

/**
 * Mail Notification on comments
 * Send admin email when an user posts a comment?
 * @name MAIL_ON_COMMENT
 *
 */
$MAIL_ON_COMMENT=false;



/**
 * Default slideshow interval time in seconds 
 * @name SS_INTERVAL
 */

$SS_INTERVAL = 5;

/**
 * Max slideshow image size. It has the same function as MAX_IMG_SIZE but
 * on the slideshow screen.
 *
 * As well you can change the options of the popup window editing the javascript
 * slideshow() function on template/default/javascript.js
 * 
 */

$MAX_SS_IMG_SIZE = 420;

//##########################################################################

//Delete or comment with "//"  the line bellow once you have 
// configured this file.
// If not, Yapig won't let you login as admin.
//$STOP=true;

?>
