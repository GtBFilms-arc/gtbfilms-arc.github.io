<?php 

	function trackback_to_array ( $entryFile ) {
		// Reads a trackback entry and returns an key/value pair array.
		//
		// Returns false on fail...
		global $sb_info;
		$trackback_entry_data = array();
				
		$str = sb_read_file( $entryFile );
		$exploded_array = explode( '|', $str );
		
		if ( count( $exploded_array ) > 1 ) {
			$trackback_entry_data = explode_with_keys( $exploded_array );
			return( $trackback_entry_data );
		} else {
			// Exploded array only contained 1 item, so something is wrong...
			return( false );
		}
	}
	
	function read_trackbacks ( $y, $m, $entry, $logged_in, $is_html ) {
		global $blog_content, $blog_config, $lang_string, $user_colors;
		
		$blog_content = '';

		// Trackbacks
		$basedir = CONTENT_DIR;
		$dir = $basedir.$y.'/'.$m.'/'.$entry.'/trackbacks/';
		$file_array = sb_folder_listing( $dir, array( '.txt', '.gz' ) );
		if ( $blog_config[ 'blog_comment_order' ] == 'new_to_old' ) {
			$file_array = array_reverse( $file_array );
		}
		
		$contents = array();
		for ( $i = 0; $i < count( $file_array ); $i++ ) {
			if ( $file_array[$i] !== 'rating.txt' ) {
				array_push( $contents, array( 'path' => ( $dir . $file_array[$i] ), 'entry' => $file_array[$i] ) );
			}
		}
		
		if( !$is_html ) {
		   $results = array();
		}
		
		if ( $contents ) {	
			for ( $i = 0; $i <= count( $contents ) - 1; $i++ ) {
				$trackback_entry_data = trackback_to_array( $contents[$i][ 'path' ] );
				
				$entry_array = array();
				$entry_array[ 'date' ] = blog_to_html( format_date( $trackback_entry_data[ 'DATE' ] ), true, false );
				$entry_array[ 'url' ] = blog_to_html( $trackback_entry_data[ 'URL' ], true, false );

				if ( isset( $trackback_entry_data[ 'TITLE' ] ) ) {
				   $entry_array[ 'title' ] = blog_to_html( $trackback_entry_data[ 'TITLE' ], true, false );
				}
				if ( isset( $trackback_entry_data[ 'EXCERPT' ] ) ) {
				   $entry_array[ 'excerpt' ] = blog_to_html( $trackback_entry_data[ 'EXCERPT' ], true, false );
				}
				if ( isset( $trackback_entry_data[ 'BLOGNAME' ] ) ) {
				   $entry_array[ 'blog_name' ] = blog_to_html( $trackback_entry_data[ 'BLOGNAME' ], true, false );
				}
				if ( isset( $trackback_entry_data[ 'IP' ] ) ) {
				   $entry_array[ 'ip' ] = blog_to_html( $trackback_entry_data[ 'IP' ], true, false );
				}
				if ( isset( $trackback_entry_data[ 'DOMAIN' ] ) ) {
				   $entry_array[ 'domain' ] = blog_to_html( $trackback_entry_data[ 'DOMAIN' ], true, false );
				}
				
				$entry_array[ 'logged_in' ] = $logged_in;
				
				// Author
				if ( $logged_in == true ) {
					$entry_array[ 'delete' ][ 'name' ] = $lang_string[ 'delete_btn' ];
					$entry_array[ 'delete' ][ 'url' ] = 'trackback_delete_cgi.php?y='.$y.'&amp;m='.$m.'&amp;entry='.$entry.'&amp;trackback='.$dir.( $contents[$i][ 'entry' ] );
				}
				
      		if( $is_html ) {
				   $blog_content = $blog_content . theme_trackbackentry( $entry_array );
			   } else {
			      array_push( $results, $entry_array );
				}
         }
		}
		
		if( $is_html ) {
		   return $blog_content;
	   } else {
	      return $results;
	   }
	}

	function write_trackback ( $y, $m, $entry, $tb_url, $title, $excerpt, $blog_name, $user_ip, $user_domain ) {
		// Save new entry or update old entry
		//
		global $blog_config, $sb_info;
		
		//clearstatcache();
		
		// We're going to assume that the y and m directories exist...
		$basedir = CONTENT_DIR;
		$dir = $basedir.$y.'/'.$m.'/'.$entry;
		
		if (!file_exists($dir)) {
			$oldumask = umask(0);
			$ok = mkdir($dir, 0777 );
			umask($oldumask);
			if (!$ok) {
				// There is a bug in some versions of PHP that will
				// cause mkdir to fail if there is a trailing "/".
				//
				// Thanks to Matt - http://agent.chaosnet.org
				return ( $dir );
			}
		}
		$dir  .= '/';
		
		$dir = $basedir.$y.'/'.$m.'/'.$entry.'/trackbacks';
		
		if (!file_exists($dir)) {
			$oldumask = umask(0);
			$ok = mkdir($dir, 0777 );
			umask($oldumask);
			if (!$ok) {
				// There is a bug in some versions of PHP that will
				// cause mkdir to fail if there is a trailing "/".
				//
				// Thanks to Matt - http://agent.chaosnet.org
				return ( $dir );
			}
		}
		$dir  .= '/';
		
		$trackback_date = time();
		
		$stamp = date('ymd-His');
		if ( $blog_config[ 'blog_enable_gzip_txt' ] ) {
			$entryFile = $dir.'trackback'.$stamp.'.txt.gz';
		} else {
			$entryFile = $dir.'trackback'.$stamp.'.txt';
		}
		
		$save_data = array();

		// Is there already an existing trackback entry from this URL?
		$found = false;
		$file_array = sb_folder_listing( $dir, array( '.txt', '.gz' ) );
		for ( $i = 0; $i < count( $file_array ); $i++ ) {
			$trackback_entry_data = trackback_to_array( $dir.$file_array[$i] );
			if ( $trackback_entry_data[ 'URL' ] === clean_post_text( $tb_url ) ) {
				$found = true;
				$entryFile = $dir.$file_array[$i];
			}
		}

		if( $found ) {
		   // This is an update ping, so read current values from file first
		   $save_data = trackback_to_array( $entryFile );

			// Delete the old file
			if ( $blog_config[ 'blog_enable_gzip_txt' ] ) {
				if ( file_exists( $entryFile ) ) {
					sb_delete_file( $entryFile );
				}
			} else {
				if ( file_exists( $entryFile ) ) {
					sb_delete_file( $entryFile );
				}
			}
		} else {
		   $save_data[ 'DATE' ] = $trackback_date;
		}

		// Save the file
		$save_data[ 'VERSION' ] = $sb_info[ 'version' ];
		$save_data[ 'URL' ] = clean_post_text( $tb_url );
		$save_data[ 'TITLE' ] = clean_post_text( $title );
		$save_data[ 'EXCERPT' ] = clean_post_text( $excerpt );
		$save_data[ 'BLOGNAME' ] = clean_post_text( $blog_name );
		$save_data[ 'IP' ] = clean_post_text( $user_ip );
		$save_data[ 'DOMAIN' ] = clean_post_text( $user_domain );

		// Implode the array
		$str = implode_with_keys( $save_data );
		
		// Save the file		
		$result = sb_write_file( $entryFile, $str );
			
		if ( $result ) {
			// Update Most Recent List
			if( $found ) {
			   delete_most_recent_trackback( $entryFile );
		      $tb_file = str_replace( '/', '', sb_strip_extension( strrchr( $entryFile, '/') ) );
	 		   add_most_recent_trackback( $tb_file, $y, $m, $entry );
			} else {
	 		   add_most_recent_trackback( 'trackback'.$stamp, $y, $m, $entry );
	 		}
	 		
	 		if ( $blog_config[ 'blog_email_notification' ] ) {
				// Send Email Notification:
				if( $found ) {
					$subject='New trackback received at ' . $blog_config[ 'blog_title' ];
				} else {
					$subject='Updated trackback received at ' . $blog_config[ 'blog_title' ];
				}
				$body='From: ' . $save_data[ 'BLOGNAME' ] . "<br />\n";
				
				$port = ':' . $_SERVER[ 'SERVER_PORT'];
				if ($port == ':80') {
					$port = '';
				}				
				if ( dirname($_SERVER[ 'PHP_SELF' ]) == '\\' ) {
					// Hosted at root.
					$base_url = 'http://'.$_SERVER[ 'HTTP_HOST' ].$port.'/';
				} else {
					// Hosted in sub-directory.
					$base_url = 'http://'.$_SERVER[ 'HTTP_HOST' ].$port.dirname($_SERVER[ 'PHP_SELF' ]).'/';
				}
				
				$body .= '<a href="' . $base_url . 'trackback.php?y=' . $y . '&amp;m=' . $m . '&amp;entry=' . $entry . '&amp;__mode=html">' . $base_url . 'comments.php?y=' . $y . '&amp;m=' . $m . '&amp;entry=' . $entry . "&amp;__mode=html</a><br /><br />\n\n";
				$body .= '<i>On ' . format_date( $trackback_date ) . ', the following trackback was received from '.$save_data[ 'DOMAIN' ].' ('.$save_data[ 'IP' ].')'.":</i><br /><br />\n\n" . blog_to_html( $save_data[ 'TITLE' ].'<br />'.$save_data[ 'EXCERPT' ], false, false );
				sb_mail( $blog_config[ 'blog_email' ], $blog_config[ 'blog_email' ], $subject, $body, false );
	 		}
	 	}
	 	
 		return ( true );
	}

	function delete_trackback ( $entryFile ) {
		// Delete the old file
		if ( file_exists( $entryFile ) ) {
			$ok = sb_delete_file( $entryFile );
		}
		if ( file_exists( $entryFile.'.gz' ) ) {
			$ok = sb_delete_file( $entryFile.'.gz' );
		}

		// Trim off filename and leave path to last directory.
		$dirpath = $entryFile;
		$pos = strrpos( $dirpath, '/' );
		if ($pos !== false) {
			$dirpath = substr( $dirpath, 0, $pos );
			
			// Get listing of files in folder.
			$file_array = sb_folder_listing( $dirpath . '/', array( '.txt', '.gz' ) );
			if ( count( $file_array ) == 0 ) {
				// Directory is empty, delete it...
				sb_delete_directory( $dirpath );
			}
			
	      $pos = strrpos( $dirpath, '/' );
   		if ($pos !== false) {
   			$dirpath = substr( $dirpath, 0, $pos );
   			
   			// Get listing of files in folder.
   			$file_array = sb_folder_listing( $dirpath . '/', array( '.txt', '.gz' ) );
   			if ( count( $file_array ) == 0 ) {
   				// Directory is empty, delete it...
   				sb_delete_directory( $dirpath );
   			}
			}
		}
		
		if ( $ok ) {
			delete_most_recent_trackback( $entryFile );
		}
		
		return ( $ok );
	}
	
   function get_tb_uri( $link ) {
      $url = parse_url($link);
      if( (!strlen($url[ 'host' ])) && (strpos($link, 'mailto:') === false) ) {
         $link = 'http://'.$_SERVER[ 'HTTP_HOST' ].$link;
      	$url = parse_url($link);
      }
      if( $url[ 'scheme' ] === 'http' ) {
	      // $socket = fsockopen( $url[ 'host' ], 80, $errno, $errstr, 30);
	      $socket = fsockopen( ( $url[ 'host' ] === $_SERVER[ 'HTTP_HOST' ] ? $_SERVER[ 'SERVER_ADDR' ] : $url[ 'host' ] ), 80, $errno, $errstr, 30);
			if ( $socket ) { 
				fwrite( $socket, 'GET ' . $url[ 'scheme' ] . '://' . $url[ 'host' ] . $url[ 'path' ] . '?' . $url[ 'query' ] . ' HTTP/1.0\nHost: ' . $url[ 'host' ] . "\n\n" );
				$data = '';
	         while( !feof( $socket ) ) {
	            $data .= fread($socket, 8192);
	         }
				fclose ( $socket );
			} else {
				return( false );
			}
	      
	      if( preg_match_all( '/(<rdf:RDF.*?<\/rdf:RDF>)/sm', $data, $rdf_all, PREG_SET_ORDER ) ) {
	         for( $k=0; $k<count($rdf_all); $k++ ){
	            if( preg_match( '|dc:identifier="'.preg_quote($link).'"|ms', $rdf_all[$k][1] ) ) {
	               // dc:identifier matches requested $link, so return the trackback ping URI
	               if( preg_match( '/trackback:ping="([^"]+)"/', $rdf_all[$k][1], $matches ) ) {
	                  return $matches[1];
	               }
	            }
	         }
	      }
      }

      return false;
   }
   
	function trackback_autodiscover( $blog_text ) {
		$results = array();
		$links = array();
		
		if( preg_match_all( '/\[url=([^\]]+)\]/', $blog_text, $links, PREG_SET_ORDER ) ) {
   		for( $i=0; $i<count($links); $i++ ) {
   		   if( ($uri = get_tb_uri( $links[$i][1] ) ) !== false ) {
		         $results[] = $uri;
   		   }
   		}
		}
      
		return ( $results );
	}

	function trackback_ping( $tb_ping, $title, $permalink, $blog_text ) {
		global $lang_string, $blog_config, $auto_discovery_confirm;
		
		$excerpt = blog_to_html( $blog_text, true, true );
		$excerpt = ( strlen($excerpt) > 127 ? substr( $excerpt, 0, 124 ) . '...' : $excerpt );

		$auto_discovery_confirm = array();
      
		$url_array = explode( ',', $tb_ping );
		if ( is_array( $url_array ) ) {
			for ( $i = 0; $i < count( $url_array ); $i++ ) {
				if ( $url_array[$i] === $lang_string[ 'label_tb_autodiscovery' ] ) {
					if( $blog_config[ 'blog_trackback_auto_discovery' ] ) {
					   // The actual ping URIs are to be confirmed by the user
					   $auto_discovery_confirm[ 'text' ]      = $blog_text;
					   $auto_discovery_confirm[ 'title' ]     = $title;
					   $auto_discovery_confirm[ 'permalink' ] = $permalink;
					   $auto_discovery_confirm[ 'excerpt' ]   = $excerpt;
					}
				} else {
					sb_tb_ping ( $url_array[$i], $title, $permalink, $excerpt );
				}
			}
		}

		return ( true );
	}

?>
