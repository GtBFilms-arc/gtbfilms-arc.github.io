<?php
	// Simple PHP Blog is released under the GNU Public License.
	//
	// You are free to use and modify Simple PHP Blog. Changes 
	// should be uploaded to http://sourceforge.net/projects/sphpblog/
	// or emailed to apalmo <at> bigevilbrain <dot> com
	

	/**
	* Wrappers for legacy functions. See 'classes/fileio.php' (New code should just call the class.)
	*/
	function sb_read_file( $filename ) {
		return fileio::read_file($filename);
	}
	
	function sb_write_file( $filename, $str ) {
		return fileio::write_file($filename, $str);
	}
  
	function sb_create_folder( $dir, $mode=0777 ) {
		return fileio::make_dir($dir, $mode);
	}
  
	function sb_copy( $source, $dest ) {
		return fileio::copy_dir($source, $dest);
	}
	
	function sb_folder_listing( $dir, $ext_array ) {
		return fileio::file_listing($dir, $ext_array=array());
	}
  
	function sb_delete_file( $filename ) {
		return fileio::delete_file($filename);
	}
  
	function sb_delete_directory( $dir ) {
		return fileio::remove_dir($dir);
	}
  
	function sb_strip_extension( $filename ) {
		return fileio::strip_extension($filename);
	}
?>