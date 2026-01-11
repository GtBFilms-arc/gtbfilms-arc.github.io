<?php

/**
 * @package install
 *
 * Group of functions used by the installer. Right now, Yapig installer is
 * quite simple, so these functions only check GD version.
 *
 *
 */


/**
 * Reimplements the original PHP {@link gd_info()} function for 
 * older PHP versions
 *
 *
 * @return array associative array with info about the GD library of the server
 */
function _gd_info() {
    $array = array(
		   "GD Version" => "",
		   "FreeType Support" => false,
		   "FreeType Linkage" => "",
		   "T1Lib Support" => false,
		   "GIF Read Support" => false,
		   "GIF Create Support" => false,
		   "JPG Support" => false,
		   "PNG Support" => false,
		   "WBMP Support" => false,
		   "XBM Support" => false
		   );

    $gif_support = 0;
    ob_start();
    eval("phpinfo();");
    $info = ob_get_contents();
    ob_end_clean();

    foreach(explode("\n", $info) as $line) {
	if(strpos($line, "GD Version") !== false)
	  $array["GD Version"] = trim(str_replace("GD Version", "", strip_tags($line)));
	if(strpos($line, "FreeType Support") !== false)
	  $array["FreeType Support"] = trim(str_replace("FreeType Support", "", strip_tags($line)));
	if(strpos($line, "FreeType Linkage") !== false)
	  $array["FreeType Linkage"] = trim(str_replace("FreeType Linkage", "", strip_tags($line)));
	if(strpos($line, "T1Lib Support") !== false)
	  $array["T1Lib Support"] = trim(str_replace("T1Lib Support", "", strip_tags($line)));
	if(strpos($line, "GIF Read Support") !== false)
	  $array["GIF Read Support"] = trim(str_replace("GIF Read Support", "", strip_tags($line)));
	if(strpos($line, "GIF Create Support") !== false)
	  $array["GIF Create Support"] = trim(str_replace("GIF Create Support", "", strip_tags($line)));
	if(strpos($line, "GIF Support") !== false)
	  $gif_support = trim(str_replace("GIF Support", "", strip_tags($line)));
	if(strpos($line, "JPG Support") !== false)
	  $array["JPG Support"] = trim(str_replace("JPG Support", "", strip_tags($line)));
	if(strpos($line, "PNG Support") !== false)
	  $array["PNG Support"] = trim(str_replace("PNG Support", "", strip_tags($line)));
	if(strpos($line, "WBMP Support") !== false)
	  $array["WBMP Support"] = trim(str_replace("WBMP Support", "", strip_tags($line)));
	if(strpos($line, "XBM Support") !== false)
	  $array["XBM Support"] = trim(str_replace("XBM Support", "", strip_tags($line)));
    }

    if($gif_support === "enabled") {
	$array["GIF Read Support"] = true;
	$array["GIF Create Support"] = true;
    }

    if($array["FreeType Support"] === "enabled") {
	$array["FreeType Support"] = true;
    }

            if($array["T1Lib Support"] === "enabled") {
		            $array["T1Lib Support"] = true;
	    }

    if($array["GIF Read Support"] === "enabled") {
	$array["GIF Read Support"] = true;
    }

    if($array["GIF Create Support"] === "enabled") {
	$array["GIF Create Support"] = true;
    }

    if($array["JPG Support"] === "enabled") {
	$array["JPG Support"] = true;
    }

    if($array["PNG Support"] === "enabled") {
	$array["PNG Support"] = true;
    }

    if($array["WBMP Support"] === "enabled") {
	$array["WBMP Support"] = true;
    }

    if($array["XBM Support"] === "enabled") {
	$array["XBM Support"] = true;
    }

    return $array;
}


/**
 * Get which version of GD is installed, if any.
 *
 * Returns the version (1 or 2) of the GD extension.
 */
function gdversion() {
   if (! extension_loaded('gd')) { return; }
   ob_start();
   phpinfo(8);
   $info=ob_get_contents();
   ob_end_clean();
   $info=stristr($info, 'gd version');
   preg_match('/\d/', $info, $gd);
   return $gd[0];

}




?>