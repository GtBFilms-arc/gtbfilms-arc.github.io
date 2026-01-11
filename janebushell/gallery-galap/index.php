<?php 
/**
 * Index. It does almost nothing. Maybe in the future will have more
 * importance.
 * 
 * @package user
 */

require_once('config.php');

/***/
if (!file_exists($BASE_DIR . 'global-gen.php')){
    include('install.php');
    exit();
}
/**
 By now, work is done by gallery.php
 */
include("gallery.php");
?>
