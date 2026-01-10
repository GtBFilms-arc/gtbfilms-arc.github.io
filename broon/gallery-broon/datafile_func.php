<?php

require_once('global.php');

/**
 * These functions handle datafiles
 *
 * Definition: _datafile_ is a file with this line format:
 * 
 *             key . $EQUAL . $value . "\n"
 *
 * where key can be for example a gid or a phid.
 *
 * Example:
 *
 *    1=>art_gallery
 *    2=>porn_galler
 *    25=>pepe_pelotas
 *
 * @package common
 */


/**
 * gets data from a data file
 *
 *  The functions searchs for id and returns data.
 *
 * @param string $file Path to file
 * @param string $id Identifier we are looking for.
 * @return string or false if not found.
 * @global string EQUAL attribute value delimiter. As default "=>"
 *
 */

function get_data($file,$id) {
    global $EQUAL;
    
    if (!@file_exists($file)) return(0);
    if(!($all=@file($file))) return(0);
    $i=0;
    while($i<sizeof($all)) {
	$one=explode($EQUAL,trim($all[$i]));
	if (strcmp($id,$one[0])==0) return($one[1]);
	$i++;
    }
    return (0);
}

/**
 * gets all data from a datafile.
 *
 * @param string $file full path to file
 * @returns mixed array with data or false if there was any problem.
 */

function get_all_data($file) {
    global $EQUAL;
    //echo "get_all_data( file: $file)";
    if (!@file_exists($file)) return(false);
    lock_file($file); //read_lock
    if(!($all=@file($file))) {
	unlock_file($file);
	return(false);
    }
    unlock_file($file); 
    $i=0;
    $one=array();
    while($i<sizeof($all)) {
	$tmp=explode($EQUAL,trim($all[$i]));
	$one[$tmp[0]]=$tmp[1];
	$i++;
    }
    //echo "<pre>";print_r($one);echo "</pre>"; //debug Line
    return ($one);
}

/**
 * Deletes an entry of a datafile.
 *
 * @param string $file path of the file.
 * @param string $id identifier (leftside).
 * 
 * @return boolean returns true if found and deleted. False if any problem
 */

function delete_data($file, $id) {
    global $EQUAL;

    if (!@file_exists($file)) return(false);
    lock_file($file);
    if(!($all=@file($file))) {
	unlock_file($file);
	return(false);
    }
    //print_r($all);
    if(!($fd=@fopen($file,"w+"))) return(false);
    $i=0;
    while($i<sizeof($all)) {
	$one=explode($EQUAL,trim($all[$i]));
	if ($id!=$one[0]){
	    fputs($fd,$all[$i]);
	}
	//else {echo "###deleted###";}
	$i++;
    }
    fflush($fd);
    fclose($fd);
    unlock_file($file);
    return (true);
}

/**
 * adds a line to the beginning of a datafile.
 * If the file does not exist, creates it.
 * 
 * @param string file full path of file.
 * @param integer key identifier (gid or phid)
 * @param value data asociated with the key.
 * @global separator, set as default: "=>"  
 *
 * @returns boolean true if ok, false if any problem.
 */

function add_data($file, $key, $value) {
    global $EQUAL;
    lock_file($file);
    if ((!@file_exists($file))||(filesize($file)==0)) {
	if(!($fd=@fopen($file,"w+"))) {
	    unlock_file($file);
	    return(false);
	}
	fputs($fd, $key . $EQUAL . $value . "\n");
	fclose($fd);
	unlock_file($file);
	return(true);
    }
    
    $all = @file($file);
    //print_r($all);
    if(!($fd=@fopen($file,"w+"))) {
	unlock_file($file);
	return(false);
    }
    
    fputs($fd, $key . $EQUAL . $value . "\n");
    if (is_array($all)) foreach ($all as $line) fputs($fd, $line);
    fclose($fd);
    unlock_file($file);
    return(true);  
}

function microtime_float() {
    
    list($usec, $sec) = explode(' ', microtime());
    return ((float)$usec + (float)$sec);
}

/**
 * Lock a file.
 * 
 * This lock system uses mkdir(),it is the only atomic function know. It does not work 
 * with NFS, for example, so this method is BEST EFFORT. Tryes to lock the file, but
 * does not warranty the lock.
 * 
 * Requires two global variables to be defined. 
 * TIMELIMIT: which is the time that will wait until it leaves. 
 */

function lock_file($filename) {
    global $STALEAGE, $TIMELIMIT;
    
    //echo "Locking $filename<br />";//Debug Line
    ignore_user_abort(1);
    $lockDir = $filename . '.lock';
    
    if (is_dir($lockDir)) {
	if ((time() - filemtime($lockDir)) > $STALEAGE) {
	    rmdir($lockDir);
	}
    }
    
    $locked = @mkdir($lockDir);
    
    if ($locked === false) {
	$timeStart = microtime_float();
	do {
	    if ((microtime_float() - $timeStart) > $TIMELIMIT) break;
	    $locked = @mkdir($lockDir);
	} while ($locked === false);
    }
    if ($locked==false) {
	ignore_user_abort(0);
    }
    return $locked;
    
}

function unlock_file($filename) {
    //echo "Unlocking $filename<br />"; //DebugLine
    $lockDir = $filename . '.lock';
    $unlocked= @rmdir($lockDir);   
    ignore_user_abort(0);
    return ($unlocked);
}

/*
 * Gets previous and next keys of an array
 * 
 * This is used when you want to search previous key on a file
 * with line format: key $EQUAL value
 * 
 * For example: if file contents are: 
 *      1=>p
 *      2=>q
 *      3=>r
 *      5=>s
 * and key param is '3' the function will return: array(2,3,5).
 * if key param is 2 the function will return array(-1,1,2);
 * @param array $file text file with line format: key $EQUAL value\n 
 * @param integer $key searched key
 * @returns mixed false if any problem. Array with keys: previous,key,next  
 * 
 */
function get_prev_and_next($file,$key) {
    
    if (!($the_array=get_all_data($file))) return(false);
    $keys=array_keys($the_array); //we only need keys
    $ret_array=array();//Initialize returned array.
    $keys_size=count($keys);
    $i=0; //init counter
    while($i<$keys_size){ 
	if ($keys[$i]==$key){ //Search for the key.
	    if($i>0) $ret_array[0]=$keys[$i-1]; //Previous 
	    else $ret_array[0]=-1; 
	    $ret_array[1]=$key;
	    if ($i+1<$keys_size) $ret_array[2]=$keys[$i+1]; //Next
	    else $ret_array[2]=-1; 
	 //echo "<pre>key_size=$keys_size\n";
	    //print_r($keys);
	    //print_r($ret_array);echo "</pre>"; //debug Line
	    return($ret_array);
	}
	$i++;
    }
}

/**
 * Gets First key of the $file 
 * 
 * Example:
 * key0 => value0
 * key1 => value1
 * 
 * returns key0 or false if there is any problem
 * 
 * 
 */

function get_first_key($file) {

    if (!($all=get_all_data($file))) {
	return false;
    }
    
    foreach($all as $key => $value) {
	return $key;
    }
    
}


/**
 * Returns the position (i.e. the line) of the id
 * 
 * id1 => line1
 * id3 => line2
 * id2 => line3
 * 
 * get_position(file, 2); returns 3.
 * false if there is any problem or not found.
 * 
 */

function get_position($file,$id) {

    if (!($all=get_all_data($file))) {
	return(false);
    }
    //counter;
    $i=1;
    foreach ($all as $key => $value) {	
	if ($key == $id){ 
	    return($i);
	}
	$i++;
    }
    return false;
    
}

/**
 * creates a datafile with the data of the array
 *
 * Example: array([1]=> pepe, [2]=>jose)
 * output: 1=>pepe
 *         2=>jose
 * 
 * @param string filepath destination file.
 * @param array data_array array with data
 * @return bool true if ok, false if not :D
 */

function create_datafile($file,$data_array) {
    global $EQUAL;
    
    //If not is array => create file with nothing
    lock_file($file);
    if (!($fd=@fopen($file,"w+"))) {
	unlock_file($file);
	return(false);
    }
    if (!is_array($data_array)) {
	fclose($fd);
	unlock_file($file);
	return(true);
    }
    $i=0;
    foreach($data_array as $key => $value) {
	fputs($fd,$key . $EQUAL . $value . "\n");
    }
    fclose($fd);
    unlock_file($file);
    return(true);
}

/**
 * Does this operation:
 * $file contents: 
 *   
 *   1=>value1
 *   2=>value2
 *   3=>value3
 * 
 * key2move=1 => does nothing. returns -3
 * key2move=2 returns:
 *
 *   2=>value2
 *   1=>value1
 *   3=>value3
 * 
 * @param string file path to datafile
 * @param string key2move key we want to move
 * @return int negative if problems. Positive if ok.
 */

function move_up_data($file,$key2move) {
    if (!($nav_info=get_prev_and_next($file,$key2move))) return(-1);
    
    //echo "<pre>";print_r(get_all_data($file));echo "</pre>";
    
    if ($nav_info[0]<0) return(-2);
    
    if (!($all_old=get_all_data($file))) return(-3);
    //Now a reordered copy of all_old array will be created 
    foreach ($all_old as $key => $value) {	
	if ($key == $nav_info[0]){ 
	    $tmp_value=$value; //Save value of previous gallery  
	    continue;
	}
	$all_new[$key] = $value;
	if ($key == $key2move) 
      $all_new[$nav_info[0]]=$tmp_value;
    }
    //Now Save new data  
    //echo "<pre>";print_r($all_new);echo "</pre>";  
    if (!create_datafile($file,$all_new)){
	return(-4);
    }
    return(1);
}


/**
 * Does this operation:
 * $file contents:  *   
 *   1=>value1
 *   2=>value2
 *   3=>value3
 * 
 * key2move=3 => does nothing. returns -3
 * key2move=2 returns:
 *
 *   1=>value2
 *   3=>value3
 *   2=>value2
 * 
 * @param string file path to datafile
 * @param string key2move key we want to move
 * @return int negative if problems. Positive if ok.
 */


function move_down_data($file,$key2move) {

  //Get Previous and next
   if (!($nav_info=get_prev_and_next($file,$key2move))){
     return(-1);
   }
   //echo "<pre>";print_r(get_all_data($file));echo "</pre>";
   
   if ($nav_info[2]<0) 
     return(-2);
   
   if (!($all_old=get_all_data($file))) return(-3);
   //Now a reordered copy of all gids will be created 
   foreach ($all_old as $key => $value) {	
     if ($key == $key2move){ 
       $tmp_value=$value; //Save value of previous gallery  
       continue;
     }
     $all_new[$key] = $value;
     if ($key == $nav_info[2]) 
       $all_new[$key2move]=$tmp_value;
   }
   //Now Save new data  
   //echo "<pre>";print_r($all_new);echo "</pre>";  
   if (!create_datafile($file,$all_new)){
     return(-4);     
   }
   return(1);
}



?>
