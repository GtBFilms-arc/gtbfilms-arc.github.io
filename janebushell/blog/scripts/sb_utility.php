<?php

	// The Simple PHP Blog is released under the GNU Public License.
	//
	// You are free to use and modify the Simple PHP Blog. All changes
	// must be uploaded to SourceForge.net under Simple PHP Blog or
	// emailed to apalmo <at> bigevilbrain <dot> com

	// -----------------
	// Utility Functions
	// -----------------

	// Activate PHP's GZ compression output, if not currently activated.
	// Must be called before any header output.
	function sb_gzoutput ()
	{
		// Contributed by: Javier Gutierrez, guti <at> ya <dot> com
		//
	  	if ( ( ini_get( 'zlib.output_compression' ) != '0' ) && ( ini_get('zlib.output_compression' ) != 'On' ) && ( extension_loaded('zlib') ) )
		{
			ini_set( 'zlib.output_compression_level', 9);
			ob_start( 'ob_gzhandler' );
			ini_restore( 'zlib.output_compression_level' );
		}
	}

	function safe_version_compare( $versionA, $versionB ) {
		// This is a PHP < 4.1 safe version compare function.
		// The version_compare function was introduced in 4.1
		// so there's no way to compare older versions.
		//
		// returns -1 if the first version is lower than the second,
		// 0 if they are equal, and +1 if the second is lower.
		//
		// return NULL on error.

		$arrayA = explode( '.', $versionA );
		$arrayB = explode( '.', $versionB );

		$count = min( count( $arrayA ), count( $arrayB ) );

		$result = NULL;
		if ( $count > 0) {
			for ( $i = 0; $i < $count; $i++ ) {
				$intA = intval( $arrayA[$i] );
				$intB = intval( $arrayB[$i] );
				if ( $intA == $intB ) {
					$result = 0;
				} else if ( $intA > $intB ) {
					$result = 1;
				} else if ( $intA < $intB ) {
					$result = -1;
				}
				if ( $result != 0 ) {
					break;
				}
			}
		}

		return ( $result );
	}

	function compress_all_files () {
		// This function compresses or decompressed all
		// data files. I would recommend backing all your
		// blog entries up before you run this. :)
		//
		// 1.2MB - 135,457 bytes
		global $blog_config;

		//clearstatcache();

		$basedir = CONTENT_DIR;

		// YEAR directories
		$counter = 0;
		$dir = $basedir;
		if ( is_dir( $dir ) ) {
			if ( $year_dir_handle = @opendir( $dir ) ) {
				while ( ( $year_dir = readdir( $year_dir_handle ) ) !== false ) {
					if ( is_dir( $dir . $year_dir ) ) {
						if ( $year_dir != '.' && $year_dir != '..' ) {
							if ( $year_dir != 'static' ) {

								// MONTH directories

								if ( $month_dir_handle = @opendir( $dir.$year_dir . '/' ) ) {
									while ( ( $month_dir = readdir( $month_dir_handle ) ) !== false ) {
										if ( is_dir( $dir.$year_dir.'/'.$month_dir ) ) {
											if ( $month_dir != '.' && $month_dir != '..' ) {

												// ENTRIES

												if ( $entry_dir_handle = @opendir( $dir.$year_dir.'/'.$month_dir . '/' ) ) {
													while ( ( $entry_filename = readdir( $entry_dir_handle ) ) !== false ) {
														if ( is_file( $dir.$year_dir.'/'.$month_dir.'/'.$entry_filename ) ) {

															// Store Filename
															$ext = strtolower( strrchr( $entry_filename, '.' ) );
															if ( $blog_config[ 'blog_enable_gzip_txt' ] ) {
																if ( $ext == '.txt' ) {
																	$str = sb_read_file( $dir.$year_dir.'/'.$month_dir.'/'.$entry_filename );
																	if ( $str ) {
																		$ok = sb_write_file( $dir.$year_dir.'/'.$month_dir.'/'.sb_strip_extension( $entry_filename ) . '.txt.gz' , $str );
																		if ( $ok ) {
																			sb_delete_file( $dir.$year_dir.'/'.$month_dir.'/'.$entry_filename );
																			$counter++;
																		}
																	}
																}
															} else {
																if ( $ext == '.gz' ) {
																	$str = sb_read_file( $dir.$year_dir.'/'.$month_dir.'/'.$entry_filename );
																	if ( $str ) {
																		$ok = sb_write_file( $dir.$year_dir.'/'.$month_dir.'/'.sb_strip_extension( $entry_filename ) . '.txt' , $str );
																		if ( $ok ) {
																			sb_delete_file( $dir.$year_dir.'/'.$month_dir.'/'.$entry_filename );
																			$counter++;
																		}
																	}
																}
															}
														} else {

															// COMMENTS

															$comments_dir = $entry_filename.'/comments';
															if ( is_dir( $dir.$year_dir.'/'.$month_dir.'/'.$comments_dir ) ) {
																if ( $comments_dir != '.' && $comments_dir != '..' ) {
																	if ( $comments_dir_handle = @opendir( $dir.$year_dir.'/'.$month_dir.'/'.$comments_dir.'/' ) ) {

																		while ( ( $comment_filename = readdir( $comments_dir_handle ) ) !== false ) {
																			if ( is_file( $dir.$year_dir.'/'.$month_dir.'/'.$comments_dir.'/'.$comment_filename ) ) {

																				// Store Filename
																				$ext = strtolower( strrchr( $comment_filename, '.' ) );
																				if ( $blog_config[ 'blog_enable_gzip_txt' ] ) {
																					if ( $ext == '.txt' ) {
																						$str = sb_read_file( $dir.$year_dir.'/'.$month_dir.'/'.$comments_dir.'/'.$comment_filename );
																						if ( $str ) {
																							$ok = sb_write_file( $dir.$year_dir.'/'.$month_dir.'/'.$comments_dir.'/'.sb_strip_extension( $comment_filename ) . '.txt.gz' , $str );
																							if ( $ok ) {
																								sb_delete_file( $dir.$year_dir.'/'.$month_dir.'/'.$comments_dir.'/'.$comment_filename );
																								$counter++;
																							}
																						}
																					}
																				} else {
																					if ( $ext == '.gz' ) {
																						$str = sb_read_file( $dir.$year_dir.'/'.$month_dir.'/'.$comments_dir.'/'.$comment_filename );
																						if ( $str ) {
																							$ok = sb_write_file( $dir.$year_dir.'/'.$month_dir.'/'.$comments_dir.'/'.sb_strip_extension( $comment_filename ) . '.txt' , $str );
																							if ( $ok ) {
																								sb_delete_file( $dir.$year_dir.'/'.$month_dir.'/'.$comments_dir.'/'.$comment_filename );
																								$counter++;
																							}
																						}
																					}
																				}
																			}
																		}

																	}
																}
															}

															// END of COMMENTS

															// TRACKBACKS

															$trackbacks_dir = $entry_filename.'/trackbacks';
															if ( is_dir( $dir.$year_dir.'/'.$month_dir.'/'.$trackbacks_dir ) ) {
																if ( $trackbacks_dir != '.' && $trackbacks_dir != '..' ) {
																	if ( $trackbacks_dir_handle = @opendir( $dir.$year_dir.'/'.$month_dir.'/'.$trackbacks_dir.'/' ) ) {

																		while ( ( $trackback_filename = readdir( $trackbacks_dir_handle ) ) !== false ) {
																			if ( is_file( $dir.$year_dir.'/'.$month_dir.'/'.$trackbacks_dir.'/'.$trackback_filename ) ) {

																				// Store Filename
																				$ext = strtolower( strrchr( $trackback_filename, '.' ) );
																				if ( $blog_config[ 'blog_enable_gzip_txt' ] ) {
																					if ( $ext == '.txt' ) {
																						$str = sb_read_file( $dir.$year_dir.'/'.$month_dir.'/'.$trackbacks_dir.'/'.$trackback_filename );
																						if ( $str ) {
																							$ok = sb_write_file( $dir.$year_dir.'/'.$month_dir.'/'.$trackbacks_dir.'/'.sb_strip_extension( $trackback_filename ) . '.txt.gz' , $str );
																							if ( $ok ) {
																								sb_delete_file( $dir.$year_dir.'/'.$month_dir.'/'.$trackbacks_dir.'/'.$trackback_filename );
																								$counter++;
																							}
																						}
																					}
																				} else {
																					if ( $ext == '.gz' ) {
																						$str = sb_read_file( $dir.$year_dir.'/'.$month_dir.'/'.$trackbacks_dir.'/'.$trackback_filename );
																						if ( $str ) {
																							$ok = sb_write_file( $dir.$year_dir.'/'.$month_dir.'/'.$trackbacks_dir.'/'.sb_strip_extension( $trackback_filename ) . '.txt' , $str );
																							if ( $ok ) {
																								sb_delete_file( $dir.$year_dir.'/'.$month_dir.'/'.$trackbacks_dir.'/'.$trackback_filename );
																								$counter++;
																							}
																						}
																					}
																				}
																			}
																		}

																	}
																}
															}

															// END of TRACKBACKS

														}
													}
												}

												// END of ENTRIES

											}
										}
									}
								}

								// END of MONTH directories
							} else {

								// STATIC ENTRIES ( $year_dir == 'static' )

								if ( $static_dir_handle = @opendir( $dir.$year_dir . '/' ) ) {
									while ( ( $entry_filename = readdir( $static_dir_handle ) ) !== false ) {
										if ( is_file( $dir.$year_dir . '/' .$entry_filename ) ) {

											// Store Filename
											$ext = strtolower( strrchr( $entry_filename, '.' ) );
											if ( $blog_config[ 'blog_enable_gzip_txt' ] ) {
												if ( $ext == '.txt' ) {
													$str = sb_read_file( $dir.$year_dir.'/'.$entry_filename );
													if ( $str ) {
														$ok = sb_write_file( $dir.$year_dir.'/'.sb_strip_extension( $entry_filename ) . '.txt.gz' , $str );
														if ( $ok ) {
															sb_delete_file( $dir.$year_dir.'/'.$entry_filename );
															$counter++;
														}
													}
												}
											} else {
												if ( $ext == '.gz' ) {
													$str = sb_read_file( $dir.$year_dir.'/'.$entry_filename );
													if ( $str ) {
														$ok = sb_write_file( $dir.$year_dir.'/'.sb_strip_extension( $entry_filename ) . '.txt' , $str );
														if ( $ok ) {
															sb_delete_file( $dir.$year_dir.'/'.$entry_filename );
															$counter++;
														}
													}
												}
											}

										}
									}
								}

								// END of STATIC ENTRIES

							}
						}
					}
				}
			}
		}
		return( $counter );
	}

	// Support function for upgrading to / downgrading from trackback enabled version
	//
	// (All versions are now trackback enabled. So, we need to move all the comments... -- Alex)
	function move_all_comment_files( $is_upgrade = true, $dont_move_files = false ) {
		// Use the "$dont_move_files" flag to check if any files need to be moved but
		// don't actually move them. This is used on the "login_cgi.php" page.
		$basedir = CONTENT_DIR;
		$count = 0;

		$dir = $basedir;
		if ( is_dir( $dir ) ) {
			if ( $year_dir_handle = @opendir( $dir ) ) {
				while ( ( $year_dir = readdir( $year_dir_handle ) ) !== false ) {
					if ( is_dir( $dir.$year_dir ) ) {
						if ( $year_dir != '.' && $year_dir != '..' ) {
							if ( $year_dir != 'static' ) {

								// MONTH directories

								if ( $month_dir_handle = @opendir( $dir.$year_dir . '/' ) ) {
									while ( ( $month_dir = readdir( $month_dir_handle ) ) !== false ) {
										if ( is_dir( $dir.$year_dir.'/'.$month_dir ) ) {
											if ( $month_dir != '.' && $month_dir != '..' ) {

												// ENTRIES

												if ( $entry_dir_handle = @opendir( $dir.$year_dir.'/'.$month_dir . '/' ) ) {
													while ( ( $entry_filename = readdir( $entry_dir_handle ) ) !== false ) {
														if ( ! is_file( $dir.$year_dir.'/'.$month_dir.'/'.$entry_filename ) ) {

															if( $is_upgrade ) {
																// move comment* to  comment/ subdir
																$comments_dir = $entry_filename;
															} else {
																// move comment/* to .
																$comments_dir = $entry_filename.'/comments';
															}

															if ( is_dir( $dir.$year_dir.'/'.$month_dir.'/'.$comments_dir ) ) {
																if ( $comments_dir != '.' && $comments_dir != '..' ) {
																	if ( $comments_dir_handle = @opendir( $dir.$year_dir.'/'.$month_dir.'/'.$comments_dir.'/' ) ) {
																		while ( ( $comment_filename = readdir( $comments_dir_handle ) ) !== false ) {
																			if ( ( is_file( $dir.$year_dir.'/'.$month_dir.'/'.$comments_dir.'/'.$comment_filename ) ) && ( strpos($comment_filename, 'comment') !== false ) ) {
																				if( $is_upgrade ) {

																					// Check that comments/ dir exists
																					if (!file_exists( $dir.$year_dir.'/'.$month_dir.'/'.$comments_dir.'/comments' )) {
																						$oldumask = umask(0);
																						$ok = mkdir( $dir.$year_dir.'/'.$month_dir.'/'.$comments_dir.'/comments', 0777 );
																						umask($oldumask);
																					}

																					if ( $dont_move_files == false ) {
																						echo $dir.$year_dir.'/'.$month_dir.'/'.$comments_dir.'/'.$comment_filename."<br />";
																						rename( $dir.$year_dir.'/'.$month_dir.'/'.$comments_dir.'/'.$comment_filename, $dir.$year_dir.'/'.$month_dir.'/'.$comments_dir.'/comments/'.$comment_filename);
																					}
																					$count++;
																				} else {
																					if ( $dont_move_files == false ) {
																						echo $dir.$year_dir.'/'.$month_dir.'/'.$comments_dir.'/'.$comment_filename."<br />";
																						rename( $dir.$year_dir.'/'.$month_dir.'/'.$comments_dir.'/'.$comment_filename, $dir.$year_dir.'/'.$month_dir.'/'.$entry_filename.'/'.$comment_filename);

																						// Can we clean up the comments/ subdir?
																						$file_array = sb_folder_listing( $dir.$year_dir.'/'.$month_dir.'/'.$comments_dir.'/', array( '.txt', '.gz' ) );
																						if ( count( $file_array ) == 0 ) {
																							sb_delete_directory( $dir.$year_dir.'/'.$month_dir.'/'.$comments_dir );
																						}
																					}
																					$count++;
																				}
																			}
																		}
																	}
																}
															}
														}
													}
												}
											}
										}
									}
								}
							}
						}
					}
				}
			}
		}

		return( $count );
	}

	// Support function for downgrading from trackback enabled version
	function delete_all_trackbacks() {
		$basedir = CONTENT_DIR;
		$count = 0;

		$dir = $basedir;
		if ( is_dir( $dir ) ) {
			if ( $year_dir_handle = @opendir( $dir ) ) {
				while ( ( $year_dir = readdir( $year_dir_handle ) ) !== false ) {
					if ( is_dir( $dir . $year_dir ) ) {
						if ( $year_dir != '.' && $year_dir != '..' ) {
							if ( $year_dir != 'static' ) {

								// MONTH directories

								if ( $month_dir_handle = @opendir( $dir.$year_dir . '/' ) ) {
									while ( ( $month_dir = readdir( $month_dir_handle ) ) !== false ) {
										if ( is_dir( $dir.$year_dir.'/'.$month_dir ) ) {
											if ( $month_dir != '.' && $month_dir != '..' ) {

												// ENTRIES

												if ( $entry_dir_handle = @opendir( $dir.$year_dir.'/'.$month_dir . '/' ) ) {
													while ( ( $entry_filename = readdir( $entry_dir_handle ) ) !== false ) {
														if ( ! is_file( $dir.$year_dir.'/'.$month_dir.'/'.$entry_filename ) ) {

															$trackbacks_dir = $entry_filename.'/trackbacks';

															if ( is_dir( $dir.$year_dir.'/'.$month_dir.'/'.$trackbacks_dir ) ) {
																if ( $trackbacks_dir != '.' && $trackbacks_dir != '..' ) {
																	if ( $trackbacks_dir_handle = @opendir( $dir.$year_dir.'/'.$month_dir.'/'.$trackbacks_dir.'/' ) ) {
																		while ( ( $trackback_filename = readdir( $trackbacks_dir_handle ) ) !== false ) {
																			if ( ( is_file( $dir.$year_dir.'/'.$month_dir.'/'.$trackbacks_dir.'/'.$trackback_filename ) ) && ( strpos($trackback_filename, 'trackback') !== false ) ) {
																				echo $dir.$year_dir.'/'.$month_dir.'/'.$trackbacks_dir.'/'.$trackback_filename.'<br />';
																				sb_delete_file( $dir.$year_dir.'/'.$month_dir.'/'.$trackbacks_dir.'/'.$trackback_filename );
																				$count++;

																				// Can we clean up the trackbacks/ subdir?
																				$file_array = sb_folder_listing( $dir.$year_dir.'/'.$month_dir.'/'.$trackbacks_dir.'/', array( '.txt', '.gz' ) );
																				if ( count( $file_array ) == 0 ) {
																					sb_delete_directory( $dir.$year_dir.'/'.$month_dir.'/'.$trackbacks_dir );
																				}
																			}
																		}
																	}
																}
															}
														}
													}
												}
											}
										}
									}
								}
							}
						}
					}
				}
			}
		}

		return( $count );
	}

	function sb_get_capcha () {
		$capcha=rand(100000, 999999);
		return( $capcha );
	}

	function assign_rand_value($num)
	{
	// accepts 1 - 36
  switch($num)
  {
    case "1":
     $rand_value = "a";
    break;
    case "2":
     $rand_value = "b";
    break;
    case "3":
     $rand_value = "c";
    break;
    case "4":
     $rand_value = "d";
    break;
    case "5":
     $rand_value = "e";
    break;
    case "6":
     $rand_value = "f";
    break;
    case "7":
     $rand_value = "g";
    break;
    case "8":
     $rand_value = "h";
    break;
    case "9":
     $rand_value = "i";
    break;
    case "10":
     $rand_value = "j";
    break;
    case "11":
     $rand_value = "k";
    break;
    case "12":
     $rand_value = "l";
    break;
    case "13":
     $rand_value = "m";
    break;
    case "14":
     $rand_value = "n";
    break;
    case "15":
     $rand_value = "o";
    break;
    case "16":
     $rand_value = "p";
    break;
    case "17":
     $rand_value = "q";
    break;
    case "18":
     $rand_value = "r";
    break;
    case "19":
     $rand_value = "s";
    break;
    case "20":
     $rand_value = "t";
    break;
    case "21":
     $rand_value = "u";
    break;
    case "22":
     $rand_value = "v";
    break;
    case "23":
     $rand_value = "w";
    break;
    case "24":
     $rand_value = "x";
    break;
    case "25":
     $rand_value = "y";
    break;
    case "26":
     $rand_value = "z";
    break;
    case "27":
     $rand_value = "0";
    break;
    case "28":
     $rand_value = "1";
    break;
    case "29":
     $rand_value = "2";
    break;
    case "30":
     $rand_value = "3";
    break;
    case "31":
     $rand_value = "4";
    break;
    case "32":
     $rand_value = "5";
    break;
    case "33":
     $rand_value = "6";
    break;
    case "34":
     $rand_value = "7";
    break;
    case "35":
     $rand_value = "8";
    break;
    case "36":
     $rand_value = "9";
    break;
  	}
	return $rand_value;
	}

	function get_rand_id($length)
	{
  	if($length>0)
  	{
  	$rand_id="";
   	for($i=1; $i<=$length; $i++)
   	{
   	mt_srand((double)microtime() * 1000000);
   	$num = mt_rand(1,36);
   	$rand_id .= assign_rand_value($num);
   	}
  	}
	return $rand_id;
	}

?>
