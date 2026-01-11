<?php

  // The Simple PHP Blog is released under the GNU Public License.
  //
  // You are free to use and modify the Simple PHP Blog. All changes 
  // must be uploaded to SourceForge.net under Simple PHP Blog or
  // emailed to apalmo <at> bigevilbrain <dot> com

  // ------------------------
  // "Archive Menu" Functions
  // ------------------------

  /**************************************************************************
  MODIFICACIONES PARA LA GESTION DE LOS BLOQUES FIJOS DEL SPHPBLOG
  ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
  * Se ha modificado la funcion "read_blocks" para que diferencie entre los
    bloques por defecto (#) y los definidos por el usuario, y en el caso de
    los primeros llame a la funcion correspondiente. (Lineas 419 a 427)
  **************************************************************************/
  
   // Sverd1 March 17, 2006
  function dateString() {
    $dateArray = read_dateFormat();
    $dateToday = explode("/", $dateArray[ 'sDate_order' ]);
    foreach($dateToday as $dToday) {
      if ($dToday == 'Day') {
        $dateString[] = '%d';
      } elseif ($dToday == 'Month' || $dToday == 'MMM') {
        $dateString[] = '%m';
      } elseif ($dToday == 'Year') {
        $dateString[] = '%y';
      }
    }
    return ( implode("/", $dateString) );
  }
  
  /*
  function read_menus_calendar ( $m, $y, $d ) {
    global $lang_string, $user_colors, $blog_config;
    
    if ( !isset( $m ) ) {
      $m = date( 'm' );
    }
    if ( !isset( $y ) ) {
      $y = date( 'y' );
    }
    if ( !isset( $d ) ) {
      $d = date( 'd' );
    }
    
    if( $blog_config[ 'blog_calendar_start' ] == 'sunday' ) {
    $date_string = mktime(0, 0, 0, $m, 2, $y ); // Use this for starting the calendar on Sunday
    } else {
    $date_string = mktime(0, 0, 0, $m, 1, $y ); //The date string we need for some info... saves space ^_^
    }
    $day_start = date( 'w', $date_string ); //The number of the 1st day of the week
    if ( strftime( '%w', mktime( 0, 0, 0, 1, 1, 2007 ) )!=0 )
    {
      $day_start = ( $day_start + 6 ) % 7;
    }
    //Calculate the previous/next month/year
    if ( $m < 12 )
    {
      $next_month = $m + 1;
      $next_year = $y + 2000;
    }
    else
    {
      $next_year = $y + 1;
      $next_month = 1;
    }
    if ( $m > 1 )
    {
      $previous_month = $m - 1;
      $previous_year = $y;
    }
    else
    {
      $previous_year = $y - 1;
      $previous_month = 12;
    }
    
    //$entries = sb_folder_listing( CONTENT_DIR . $y . '/' . $m . '/', array( '.txt', '.gz' ) );
    $entries = blog_entry_listing();
    if ( $blog_config[ 'blog_entry_order' ] != 'old_to_new' )
    {
      sort ( $entries );
    }

    //Remove not current month/day entries
    $temp_entries=array();
    for ( $i = 0; $i < count( $entries ); $i++ ) {
      if ( ( substr( $entries[ $i ], 5, 2 ) == $y ) && ( substr( $entries[ $i ], 7, 2 ) == $m ) ) {
        array_push( $temp_entries, $entries[ $i ] );
      }
    }

    //Don't let go before the first article
    if ( substr( $entries[ 0 ], 7, 2 ) + ( substr( $entries[ 0 ], 5, 2 ) * 12 ) >=
      $y*12+$m ) {
      $previous_year = $y+2000;
      $previous_month = $m;
    }
    //Don't let go past now
    if ( date( 'm' ) + ( date( 'y' ) * 12 ) <=
      $y*12+$m ) {
      $next_year = $y+2000;
      $next_month = $m;
    }

    $entries=$temp_entries;
    unset( $temp_entries );

    // Loop Through Days
    $counts = Array();
    for ( $i = 0; $i < count( $entries ); $i++ ) {
      $temp_index = substr( $entries[$i], 9, 2 )-1;
      $temp_entry = substr( $entries[$i], 0, 11 );

      // Count the number of entries on this day
      $counts[$temp_index] = 1;
      for ( $j = $i + 1; $j < count( $entries ); $j++ ) {
        if ( $temp_entry == substr( $entries[$j], 0, 11 ) ) {
          $counts[$temp_index]++;
        } else {
          break;
        }
      }
      $i = $j - 1;
    }

    $str = '
    <table border="0" cellpadding="0" cellspacing="0" align="center" class="calendar">
    <tr>
    <td align="center">';
    if ( ( ( $previous_year%100 )!=$y ) || ( $previous_month!=$m ) ) {
      $str.='<a href="index.php?y=' . sprintf( '%02d', $previous_year % 100 ) . '&amp;m=' . sprintf( '%02d', $previous_month ) .'">&laquo;</a>';
    }
    $str.='</td>
    <td align="center" colspan="5"><b>' . ucwords( strftime( '%B %Y', $date_string) ) . '</b></td>
    <td align="center">';
    if ( ( ( $next_year%100 )!=$y ) || ( $next_month!=$m ) ) {
      $str.='<a href="' . $_SERVER[ 'PHP_SELF' ] . '?y=' . sprintf( '%02d', $next_year % 100 ) . '&amp;m=' . sprintf( '%02d', $next_month ) .'">&raquo;</a>';
    }
    $str.='</td>
    </tr>
    <tr>';
    
    if( $blog_config[ 'blog_calendar_start' ] == 'sunday' ) {   
      // This is for the Sunday starting date
      for ( $i=0; $i<7; $i++ )
      {
        if ( $day_start!=0 )
        {
          $str  .= '<td>' . ucwords( strftime( '%a', mktime(0, 0, 0, 1, ($i+0)%7, 1990 ) ) ) . '</td>';
        }
        else
        {
          $str  .= '<td>' . ucwords( strftime( '%a', mktime(0, 0, 0, 1, ($i+7)%7, 1990 ) ) ) . '</td>';
        }
      }
    } else {    
      for ( $i=0; $i<7; $i++ )
      {
        if ( $day_start!=0 )
        {
          $str  .= '<td>' . ucwords( strftime( '%a', mktime(0, 0, 0, 1, ($i+1)%7, 1990 ) ) ) . '</td>';
        }
        else
        {
          $str  .= '<td>' . ucwords( strftime( '%a', mktime(0, 0, 0, 1, ($i+8)%7, 1990 ) ) ) . '</td>';
        }
      }
    }
    
    $str  .= '</tr><tr>';
    
    //The empty columns before the 1st day of the week
    for ( $i = 0; $i<$day_start; $i++ )
    {
      $str  .= '<td>&nbsp;</td>';
    }
    $current_position = $day_start; //The current (column) position of the current day from the loop
    $total_days_in_month = date( 't', $date_string); //The total days in the month for the end of the loop

    //Loop all the days from the month
    for ( $i = 1; $i<=$total_days_in_month; $i++) {
      if ( mktime( 0, 0, 0, $m, $i, $y ) == mktime( 0, 0, 0 ) ) {
        $str  .= '<td align="center"><u>';
      } else {
        $str  .= '<td align="center">';
      }
      
      if ( isset($counts[$i-1]) && $counts[$i-1] > 0 ) {
        $str  .= '<a href="index.php?d=' . sprintf( '%02d', $i) . '&amp;m=' . sprintf( '%02d', $m ) . '&amp;y=' . sprintf( '%02d', $y % 100 ) . '" title="' . $counts[$i-1] . '">' . $i . '</a>';
      } else {
        $str  .= $i;
      }
      
      if ( mktime( 0, 0, 0, $m, $i, $y ) == mktime( 0, 0, 0 ) ) {
        $str  .= '</u></td>';
      } else {
        $str  .= '</td>';
      }
      
      $current_position++;
      
      if ( $current_position == 7 ) {
        $str  .= '</tr><tr>';
        $current_position = 0;
      }
    }
    $end_day = 7-$current_position; //There are

    //Fill the last columns
    for ( $i = 0; $i<$end_day; $i++ )
    {
      $str  .= '<td></td>';
    }
    $str  .= '</tr><tr>';
    
    // Fixed per Sverd1 March 17, 2006
    $str  .= '<td colspan="7" align="center">' . strftime( '<a href="index.php?y=%y&amp;m=%m&amp;d=%d">' . dateString() ) . '</a></td></tr></table>'; // Close the table
    return( $str );
  }
  */

  function read_menus_tree ( $m, $y, $d, $max_chars=75, $base_url='index.php', $showall=false ) {
    // Create the right-hand navigation menu and Archives page. Return HTML
    //
    global $lang_string;
    
    $entry_array = blog_entry_listing();
    // $entry_array[$i] = implode( '|', array( $entry_filename, $year_dir, $month_dir ) ) );
    
    $str = '';
    if ( count( $entry_array ) > 0 ) {
      $str_year = '';
      $str_month = '';
      $str_day = '';
      
      list( $last_filename, $last_y, $last_m ) = explode( '|', $entry_array[ 0 ] );
      $last_d = substr($last_filename, 9, 2);
      
      $str = '';
      $str.= '<div id="archive_tree_menu"><ul>';
      
      for ( $n = 0; $n <= count( $entry_array ) - 0; $n++ ) {
      
        if ( $n == count( $entry_array ) ) {
          list( $curr_filename, $curr_y, $curr_m ) = explode( '|', $entry_array[ $n-1 ] );
        } else {
          list( $curr_filename, $curr_y, $curr_m ) = explode( '|', $entry_array[ $n ] );
        }
        $curr_d = substr($curr_filename, 9, 2);
        
        // Month
        if ( $last_m != $curr_m || $last_y != $curr_y || $n == count( $entry_array ) ) {
          
          // Build Month List
          $str_month .= '<li>' . "\n";
          $temp_str = ( strftime( '%B', mktime(0, 0, 0, $last_m, $last_d, $last_y ) ) );
          $str_month .= '<a href="' . $base_url . '?m=' . $last_m . '&amp;y=' . $last_y . '">' . $temp_str . '</a>' . "\n";
          
          // Fixed per Sverd1 March 17, 2006
          if (!empty($str_day)) {
            $str_month .= '<ul>' . "\n" . $str_day . "\n" . '</ul>' . "\n";
          }
          
          $str_month .= '</li>' . "\n";
          
          $str_day = '';
          $last_m = $curr_m;
        }
        
        // Year
        if ( $last_y != $curr_y || $n == count( $entry_array ) ) {
        
          // Build Year List
          $temp_str = ( strftime( '%Y', mktime(0, 0, 0, $last_m, $last_d, $last_y ) ) );
          $str_year .= '<li>' . "\n";
          $str_year .= $temp_str . "\n";
          $str_year .= '<ul>' . "\n";
          $str_year .= $str_month . "\n";
          $str_year .= '</ul>' . "\n";
          $str_year .= '</li>' . "\n";
          $str .= $str_year;
          
          $str_year = '';
          $str_month = '';
          $str_day = '';
          
          $last_y = $curr_y;
        }
        
        // Day
        if ( $curr_y == $y && $curr_m == $m || $showall == true ) {
          
          // Build Day List
          $blog_entry_data = blog_entry_to_array( CONTENT_DIR . $curr_y . '/' . $curr_m . '/' . $curr_filename );
          
          $curr_array = Array();
          $curr_array[ 'subject' ] = blog_to_html( $blog_entry_data[ 'SUBJECT' ], false, true );
          // Fixed per Sverd1 March 17, 2006
          $curr_array[ 'date' ] = ( strftime( dateString(), mktime(0, 0, 0, $curr_m, $curr_d, $curr_y ) ) );
          $curr_array[ 'entry' ] = blog_to_html( $blog_entry_data[ 'CONTENT' ], false, true );

          $str_day .= '<li>' . "\n";
          $str_day .= '<a href="index.php?m=' . $curr_m . '&amp;y=' . $curr_y . '&amp;entry=' . sb_strip_extension( $curr_filename ) . '">' . $curr_array[ 'subject' ] . '</a><br />' . "\n";
          $str_day .= '<b>' . $curr_array[ 'date' ] . '</b>';
          if ( $max_chars == 0) {
            // Don't show any of the entry...
          } else if ( strlen( $curr_array[ 'entry' ] ) > $max_chars ) {
            // Truncate...
            $str_day .= "<br />\n";
            $str_day .= substr( $curr_array[ 'entry' ], 0, $max_chars) . "<p />\n";
          } else {
            $str_day .= "<br />\n";
            $str_day .= $curr_array[ 'entry' ] . "<p />\n";
          }
          $str_day .= '</li>' . "\n";
        }
        
      }
      
      $str .= '</ul></div>';
    }       
    return( $str );
  }

  // ----------------------
  // "Links Menu" Functions
  // ----------------------
  
  function read_links ( $logged_in ) {
    // Create the right-hand link menu. Return HTML
    //
  
    global $lang_string;
    
    // Read links file.
    $filename = CONFIG_DIR.'links.txt';
    $result = sb_read_file( $filename );
    
    // Append new links.
    $str = NULL;
    if ( $result ) {
      $array = explode('|', $result);
      for ( $i = 0; $i < count( $array ); $i = $i + 2 ) {
        if ( $array[$i+1] == '' ) {
          $str  .= '<br />' . $array[$i] . '<br />';
        } else {
          if ( strpos($array[$i+1], 'http') === 0 ) {
            $str  .= '<a href="' . $array[$i+1] . '" target="_blank">' . $array[$i] . '</a><br />';
          } else {
            $str  .= '<a href="' . $array[$i+1] . '">' . $array[$i] . '</a><br />';
          }
        }
      }
    }

    // Show invisible links when logged in.
    if ( $logged_in == true ) {
      $dir = CONTENT_DIR.'static/';
      $contents = sb_folder_listing( $dir, array( '.txt','.gz' ) );
      for ( $i = 0; $i < count( $contents ); $i++ ) {
        $staticfile = sb_read_file( $dir . $contents[ $i ] );
        $exploded_array = explode( '|', $staticfile );
        $blog_entry_data = explode_with_keys( $exploded_array );
        if ( $blog_entry_data[ 'MENU_VISIBLE' ] == false ) {
          $str .= '<a href="static.php?page=' . sb_strip_extension( $contents[ $i ] ) . '">*' . $blog_entry_data[ 'SUBJECT' ] . '</a><br />';
        }
      }
    }

    if ( $logged_in == true ) {
      $str  .= '<a href="add_link.php">[ ' . $lang_string[ 'sb_add_link_btn' ]  . ' ]</a><br />';
    }



    return ( $str );
  }

/*
  function write_link ( $link_name, $link_url, $link_id ) {
    // Save new link. Update links file
    //
    
    // write_link( clean_post_text( $blog_subject ), 'static.php?page='.$entryFile, $i-1 );
    
    // Clean up link name and make safe for HTML and text database storage.
    global $lang_string;
    $link_name = str_replace( '|', ':', $link_name );
    $link_name = htmlspecialchars( $link_name, ENT_QUOTES, $lang_string[ 'php_charset' ] );
    
    // Clean up link url and make safe text database storage.
    $link_url = str_replace( '|', ':', $link_url );

    // Read old links file.
    $filename = CONFIG_DIR.'links.txt';
    $result = sb_read_file( $filename );
  
    // Append new links.
    if ( $result ) {
      $array = explode('|', $result);
      
      if ( $link_id !== '' ) {
        array_splice( $array, $link_id, 2 );
        array_splice( $array, $link_id, 0, array( $link_name, $link_url ) );
      } else {
        array_push( $array, $link_name );
        array_push( $array, $link_url );
      }
    } else {
      $array = array( $link_name, $link_url );
    }
    
    // Save links to file.
    $str = implode('|', $array);
    $result = sb_write_file( $filename, $str );
    
    if ( $result ) {
      return ( true );
    } else {
      // Error:
      // Probably couldn't create file...
      return ( $filename );
    }
  }
  
  function modify_link ( $action, $link_id ) {
    // Modify links.
    // Move links up or down, edit or delete.
    
    // Read links file.
    $filename = CONFIG_DIR.'links.txt';
    $result = sb_read_file( $filename );
    
    // Append new links.
    if ( $result ) {
      $array = explode('|', $result);
      
      if ( $action === 'up' ) {
        if ( count( $array ) > 2 && $link_id != 0 ) {
          $pop_array = array_splice( $array, $link_id, 2 );
          array_splice( $array, $link_id-2, 0, $pop_array );
        }
      }
      if ( $action === 'down' ) {
        if ( count( $array ) > 2 && $link_id < ( count( $array ) - 3 ) ) {
          $pop_array = array_splice( $array, $link_id, 2 );
          array_splice( $array, $link_id+2, 0, $pop_array );
        }
      }
      if ( $action === 'delete' ) {
        if ( $link_id <= ( count( $array ) - 1 ) ) {
          array_splice( $array, $link_id, 2 );
        }
      }
      if ( $action === 'delete_static' ) {
        for ( $i = 0; $i < count( $array ); $i++ ) {
          if ( $link_id == $array[$i] ) {
            array_splice( $array, $i-1, 2 );
            break;
          }
        }
      }
    }
    
    // Save links to file.
    $str = implode('|', $array);
    $result = sb_write_file( $filename, $str );
    
    if ( $result ) {
      return ( true );
    } else {
      // Error:
      // Probably couldn't create file...
      return ( $filename );
    }
  }
  */
  
  // ----------------------------
  // "Blocks" Functions
  // ----------------------------

  function read_blocks ( $logged_in ) {
    // Create the right-hand block. Return array
    //

    global $blog_content, $blog_subject, $blog_text, $blog_date, $user_colors, $logged_in, $blog_config;
    global $lang_string;

    // Read blocks file.
    $filename = CONFIG_DIR.'blocks.txt';
    $result = sb_read_file( $filename );

    // Append new blocks.
    $block_array = array();
    if ( $result ) {
      $array = explode('|', $result);
      for ( $i = 0; $i < count( $array ); $i+=2 ) {
        // blog_to_html( $str, $comment_mode, $strip_all_tags, $add_no_follow=false, $emoticon_replace=false )
        if ( (($blog_config[ 'blog_enable_static_block' ] == true) and ( $array[$i] != $blog_config[ 'static_block_options' ] ))
           or ($blog_config[ 'blog_enable_static_block' ] == false) ) {
          $block_array[$i] = blog_to_html( $array[$i], false, false, false, true );
          $block_array[$i + 1] = blog_to_html( $array[$i + 1], false, false, false, true );
        }
      }
    }

    return ( $block_array );
  }
  
  function write_block ( $block_name, $block_content, $block_id ) {
    // Save new block. Update blocks file
    //
    
    // Clean up block name and make safe for HTML and text database storage.
    global $lang_string;
    $block_name = str_replace( '|', ':', $block_name );
    $block_name = htmlspecialchars( $block_name, ENT_QUOTES, $lang_string[ 'php_charset' ] );

    // Clean up block url and make safe text database storage.
    $block_content = clean_post_text(str_replace( '|', ':', $block_content ));

    // Read old blocks file.
    $filename = CONFIG_DIR.'blocks.txt';
    $result = sb_read_file( $filename );

    // Append new blocks.
    if ( $result ) {
      $array = explode('|', $result);
      
      if ( $block_id !== '' ) {
        array_splice( $array, $block_id, 2 );
        array_splice( $array, $block_id, 0, array( $block_name, $block_content ) );
      } else {
        array_push( $array, $block_name );
        array_push( $array, $block_content );
      }
    } else {
      $array = array( $block_name, $block_content );
    }
    
    // Save blocks to file.
    $str = implode('|', $array);
    $result = sb_write_file( $filename, $str );
    
    if ( $result ) {
      return ( true );
    } else {
      // Error:
      // Probably couldn't create file...
      return ( $filename );
    }
  }
  
  function modify_block ( $action, $block_id ) {
    // Modify blocks.
    // Move blocks up or down, edit or delete.
    
    // Read blocks file.
    $filename = CONFIG_DIR.'blocks.txt';
    $result = sb_read_file( $filename );
    
    // Append new blocks.
    if ( $result ) {
      $array = explode('|', $result);
      
      if ( $action === 'up' ) {
        if ( count( $array ) > 2 && $block_id != 0 ) {
          $pop_array = array_splice( $array, $block_id, 2 );
          array_splice( $array, $block_id-2, 0, $pop_array );
        }
      }
      if ( $action === 'down' ) {
        if ( count( $array ) > 2 && $block_id < ( count( $array ) - 3 ) ) {
          $pop_array = array_splice( $array, $block_id, 2 );
          array_splice( $array, $block_id+2, 0, $pop_array );
        }
      }
      if ( $action === 'delete' ) {
        if ( $block_id <= ( count( $array ) - 1 ) ) {
          array_splice( $array, $block_id, 2 );
        }
      }
      if ( $action === 'delete_static' ) {
        for ( $i = 0; $i < count( $array ); $i++ ) {
          if ( $block_id == $array[$i] ) {
            array_splice( $array, $i-1, 2 );
            break;
          }
        }
      }
    }
    
    // Save blocks to file.
    $str = implode('|', $array);
    $result = sb_write_file( $filename, $str );
    
    if ( $result ) {
      return ( true );
    } else {
      // Error:
      // Probably couldn't create file...
      return ( $filename );
    }
  }
  // DATOH_END

  // ----------------------------
  // "Most Recent Menu" Functions
  // ----------------------------
  
  function add_most_recent ( $comment_id, $y, $m, $blog_entry_id ) {
    global $blog_config;
    
    // Add an item to the 'Last Updated' List
    //
    
    // Read links file.
    $filename = CONFIG_DIR.'last_updated.txt';
    $result = sb_read_file( $filename );
    
    // Append new links.
    if ( $result ) {
      $array = explode('|', $result);
      array_push( $array, $blog_entry_id, $m, $y, $comment_id );
    } else {
      $array = array( $blog_entry_id, $m, $y, $comment_id );
    }
    
    $max_comments = $blog_config[ 'blog_max_entries' ];
    if ( count( $array ) > ( ( $max_comments * 4 ) - 1 ) ) {
      // $array = array_reverse( $array );
      $array = array_slice( $array, $max_comments * -4, $max_comments * 4);
      // $array = array_reverse( $array );
    }
    
    // Save links to file.
    $str = implode( '|', $array );
    sb_write_file( $filename, $str );
  }
  
  function delete_most_recent ( $item_filename ) {
    // Delete an item to the 'Last Updated' List
    //
    
    // Read links file.
    $filename = CONFIG_DIR.'last_updated.txt';
    $result = sb_read_file( $filename );

    $blog_entry_id = str_replace( '/', '', sb_strip_extension( strrchr( $item_filename, '/') ) );
    
    // Append new links.
    $str = NULL;
    $update_file = false;
    if ( $result ) {
      $array = explode('|', $result);
      $array = array_reverse( $array );
      for ( $i = 0; $i < count( $array ); $i = $i + 4 ) {
        if ( $blog_entry_id == $array[$i] ) {
          array_splice( $array, $i, 4 );
          $update_file = true;
          break;
        }
      }
    }
    
    // Save links to file.
    if ( $update_file ) {
      $array = array_reverse( $array );
      $str = implode('|', $array);
      sb_write_file( $filename, $str );
    }
  }

  function confirm_unmod( $modflag ) {
    global $blog_config;
    $result = true;
    if ($blog_config[ 'blog_comments_moderation' ] == 1) {
      if ( $modflag == 'H' ) { $result = false; }
    }

    return( $result );
  }

/*
  function get_most_recent () {
    // Read last updated items from disk, return HTML
    //
    global $lang_string, $user_colors;

    // Read links file.
    $filename = CONFIG_DIR.'last_updated.txt';
    $result = sb_read_file( $filename );

    // Append new links.
    $str_comments = NULL;
    if ( $result ) {
      $array = explode('|', $result);
      $array = array_reverse( $array );
      for ( $i = 0; $i < count( $array ); $i = $i + 4 ) {
        $comment_id = $array[$i+0];
        $y = $array[$i+1];
        $m = $array[$i+2];
        $blog_entry_id = $array[$i+3];
        
        $comment_file = CONTENT_DIR.$y.'/'.$m.'/'. sb_strip_extension( $blog_entry_id ).'/comments/'.$comment_id;
        if ( file_exists( $comment_file . '.txt' ) ) {
          $comment_file  .= '.txt';
        } elseif ( file_exists( $comment_file . '.txt.gz' ) ) {
          $comment_file  .= '.txt.gz';
        }
        
        $comment_entry_data = comment_to_array( $comment_file );
        if ( ($comment_entry_data !== false) && ( confirm_unmod($comment_entry_data[ 'MODERATIONFLAG' ]) ) ) {
          global $blog_config;
          
          $comment_name = $comment_entry_data[ 'NAME' ];
          $comment_date = $comment_entry_data[ 'DATE' ];
          $comment_text = $comment_entry_data[ 'CONTENT' ];
          $comment_text = blog_to_html( $comment_text, false, true );
          
          if ( strlen( $comment_name ) > 40 ) {
            $comment_name = substr( $comment_name, 0, 40 );
            $comment_name = substr( $comment_name, 0, strrpos( $comment_name, ' ' ) ) . '...';
          }
          
          if ( strlen( $comment_text ) > 40 ) {
            $comment_text = substr( $comment_text, 0, 40 );
            $comment_text = substr( $comment_text, 0, strrpos( $comment_text, ' ' ) ) . '...';
          }
          
          global $blog_config, $theme_vars;
          if ( $blog_config[ 'blog_comments_popup' ] == 1 ) {
            $str_comments  .= '<a href="javascript:openpopup(\'comments.php?y='.$y.'&amp;m='.$m.'&amp;entry='.$blog_entry_id.'\','.$theme_vars[ 'popup_window' ][ 'width' ].','.$theme_vars[ 'popup_window' ][ 'height' ].',true)">'.$comment_name.'</a><br />';
          } else {
            $str_comments  .= '<a href="comments.php?y='.$y.'&amp;m='.$m.'&amp;entry='.$blog_entry_id.'">'.$comment_name.'</a><br />';
          }
          
          // $str_comments = $str_comments . format_date_menu( $comment_date ) . '<br />';
          $str_comments  .= format_date( $comment_date ) . '<br />';
          $str_comments  .= $comment_text . '<p />';
        }
      }
    }
    
    return ( $str_comments );
  }
  */
  
  function add_most_recent_trackback ( $trackback_id, $y, $m, $blog_entry_id ) {
    global $blog_config;
    
    // Add an item to the 'Last Updated' List
    //
    
    // Read links file.
    $filename = CONFIG_DIR.'last_updated_trackback.txt';
    $result = sb_read_file( $filename );
    
    // Append new links.
    if ( $result ) {
      $array = explode('|', $result);
      array_push( $array, $blog_entry_id, $m, $y, $trackback_id );
    } else {
      $array = array( $blog_entry_id, $m, $y, $trackback_id );
    }
    
    $max_comments = $blog_config[ 'blog_max_entries' ];
    if ( count( $array ) > ( ( $max_comments * 4 ) - 1 ) ) {
      // $array = array_reverse( $array );
      $array = array_slice( $array, $max_comments * -4, $max_comments * 4);
      // $array = array_reverse( $array );
    }
    
    // Save links to file.
    $str = implode( '|', $array );
    sb_write_file( $filename, $str );
  }
  
  function delete_most_recent_trackback ( $item_filename ) {
    // Delete an item to the 'Last Updated' List
    //
    
    // Read links file.
    $filename = CONFIG_DIR.'last_updated_trackback.txt';
    $result = sb_read_file( $filename );

    $blog_entry_id = str_replace( '/', '', sb_strip_extension( strrchr( $item_filename, '/') ) );
    
    // Append new links.
    $str = NULL;
    $update_file = false;
    if ( $result ) {
      $array = explode('|', $result);
      $array = array_reverse( $array );
      for ( $i = 0; $i < count( $array ); $i = $i + 4 ) {
        if ( $blog_entry_id == $array[$i] ) {
          array_splice( $array, $i, 4 );
          $update_file = true;
        }
      }
    }
    
    // Save links to file.
    if ( $update_file ) {
      $array = array_reverse( $array );
      $str = implode('|', $array);
      sb_write_file( $filename, $str );
    }
  }
  
  function get_most_recent_trackback () {
    // Read last updated items from disk, return HTML
    //
    global $lang_string, $user_colors;
    
    // Read links file.
    $filename = CONFIG_DIR.'last_updated_trackback.txt';
    $result = sb_read_file( $filename );
    
    // Append new links.
    $str_trackbacks = NULL;
    if ( $result ) {
      $array = explode('|', $result);
      $array = array_reverse( $array );
      for ( $i = 0; $i < count( $array ); $i = $i + 4 ) {
        $trackback_id = $array[$i+0];
        $y = $array[$i+1];
        $m = $array[$i+2];
        $blog_entry_id = $array[$i+3];
        
        $trackback_file = CONTENT_DIR.$y.'/'.$m.'/'. sb_strip_extension( $blog_entry_id ).'/trackbacks/'.$trackback_id;
        if ( file_exists( $trackback_file . '.txt' ) ) {
          $trackback_file  .= '.txt';
        } elseif ( file_exists( $trackback_file . '.txt.gz' ) ) {
          $trackback_file  .= '.txt.gz';
        }
        
        $trackback_entry_data = comment_to_array( $trackback_file );
        if ( $trackback_entry_data !== false) {
          $trackback_date = $trackback_entry_data[ 'DATE' ];
          $trackback_title = $trackback_entry_data[ 'TITLE' ];
          $trackback_blogname = $trackback_entry_data[ 'BLOGNAME' ];
          
          if ( strlen( $trackback_title ) > 40 ) {
            $trackback_title = substr( $trackback_title, 0, 40 );
            $trackback_title = substr( $trackback_title, 0, strrpos( $trackback_title, ' ' ) ) . '...';
          }
          
          if ( strlen( $trackback_blogname ) > 40 ) {
            $trackback_blogname = substr( $trackback_blogname, 0, 40 );
            $trackback_blogname = substr( $trackback_blogname, 0, strrpos( $trackback_blogname, ' ' ) ) . '...';
          }
          
          global $blog_config, $theme_vars;
          if ( $blog_config[ 'blog_comments_popup' ] == 1 ) {
            $str_trackbacks  .= '<a href="javascript:openpopup(\'trackback.php?y='.$y.'&amp;m='.$m.'&amp;entry='.$blog_entry_id.'&amp;__mode=html\','.$theme_vars[ 'popup_window' ][ 'width' ].','.$theme_vars[ 'popup_window' ][ 'height' ].',true)">'.$trackback_title.'</a><br />';
          } else {
            $str_trackbacks  .= '<a href="trackback.php?y='.$y.'&amp;m='.$m.'&amp;entry='.$blog_entry_id.'&amp;__mode=html">'.$trackback_title.'</a><br />';
          }
          
          // $str_trackbacks = $str_trackbacks . format_date_menu( $trackback_date ) . '<br />';
          $str_trackbacks  .= format_date( $trackback_date ) . '<br />';
          $str_trackbacks  .= $trackback_blogname . '<p />';
        }
      }
    }
    
    return ( $str_trackbacks );
  }
?>
