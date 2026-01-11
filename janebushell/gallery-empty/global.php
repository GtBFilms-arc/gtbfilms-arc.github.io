<?php
/**
 * Global Variables.
 * 
 * These variables are global variables that are not part of the
 * config.php, so a YaPiG admin should not modify them unless if
 * he knows what is doing.
 * 
 *  DIRECTORIES
 *  All directory variables end with "/".
 *  Path where the different gallerys will be stored.
 *  Each gallery will be stored in a different subfolder of $BASE_DIR
 *  Sample: BASE_DIR="fotos/"
 *  gallery 1 will be stored:   fotos/gallery1/
 *  gallery 2 will be stored:   fotos/porn_photos/
 *
 * @package common
 * 
 */


// Set default umask for file creations = 777
umask(0);

//Change max execution time so big galleries can be built.
// Default execution time is 30 seconds
//if this produces any problem, delete the following line. Time is
//specified in seconds.

if (ini_get("safe_mode") != "1") {
    @set_time_limit(300);
}

// Temporal solution for install.

if (isset($_GET['BASE_DIR']) || isset($_POST['BASE_DIR'])) {
    die;
}

if(@file_exists( $BASE_DIR . 'global-gen.php')) {	
    include($BASE_DIR . 'global-gen.php');
}	



/**
 * gid visitors file.
 *
 * This file will store the visits for each gallery in the common
 * correspondece file format. Sample line:
 *   gid $EQUAL visits
 *
 */

$GID_STATS= $SECURE_DIR . 'gid-stats.dat';


/**
 * counter file of created galleries
 * 
 * This file contains a counter of created files. This is for give the
 * galleries an unique and easy to remember gallery identificator
 *
 */ 

$GID_COUNTER_FILE=$SECURE_DIR . 'gid_counter.dat';




/**
 * gid information file
 *
 * Contains several information of a particular gid such as: title,
 * author, description, date, full thumbnail path...
 *
 * Stored in a correct .php file.
 *
 * Stored in $gid_dir (one different file per gallery)
 */

$GID_INFO_FILE='guid_info.php';


/**
 * phid Visitors Counter file
 *
 * For each phid in a particular gallery there will be a counter of visits.
 * This file has the common correspondence file format.
 * phid $EQUAL value
 *
 */

$PHID_STATS='phid-stats.dat';

/**
 *
 * phid comments counter
 *
 * For each phid in a a particular gallery ther will be a counter of comments
 * This file has the common correspondence file format
 *
 */

$PHID_COMMENTS='phid-comments.dat';


/**
 * Phid Captions
 * Each phid can have a caption.
 *
 */

$PHID_CAPTIONS='phid-captions.dat';


/**
 * Common correspondence file format separator.
 *
 * Separes the attribute name of the attribute value in the common
 * correspondence file format: Sample
 * attribute_name $EQUAL value
 * if $EQUAL="=>"
 *
 */

$EQUAL='=>';

/**
 * Comments File separator
 *
 * When a photo receives a comment the differen fields are separated
 * whith this variable in the the comment file.
 *
 */

$SEPARATOR='<|>';

/**
 *
 * Total Counter.
 *
 * All Visit and comments Counter file have a Total Counter which stores
 * the sum of all visits for that file.
 *
 */

$TOTAL_LIST='Total';



/**
 * Supported Image Extensions
 * 
 * This array is for checking file extensions. Yapig will create thumbnails
 * of files with these extensions
 * 
 */

$IMAGE_EXT= array ('jpg','jpeg','jpe', 'png', 'gif');


/**
 * Maximun Uploads at a time
 *
 * In admin file, maximun number of file uploads at a time.
 *
 */

$MAX_UPLOADS=10;



/**
 * Default config.php mail.
 *
 * if $ADMIN_EMAIL == $DEFAULT_EMAIL, when a comment is 
 * post, no email is sent. 
 */

$DEFAULT_EMAIL='yapig@example.com';


/**
 * Default language
 */
$DEFAULT_LANG = 'en';


/**
 * Locking files time in microseconds
 * For avoiding lose of stats we need to be careful with concurrent access to files.
 * We use a method got from php.net/flock. View datafile_func.php lock_file()
 */

$TIMELIMIT = 1000000;

/**
 * For locking files.
 * Time in secconds. If the age of the lock is greater than this => ignore lock.
 * 
 */

$STALEAGE= 5;



?>
