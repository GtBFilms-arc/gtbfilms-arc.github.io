<?php
  // The Simple PHP Blog is released under the GNU Public License.
  //
  // You are free to use and modify the Simple PHP Blog. All changes 
  // must be uploaded to SourceForge.net under Simple PHP Blog or
  // emailed to apalmo <at> bigevilbrain <dot> com

  // ------------------
  // Text Editor
  // ------------------
  function sb_editor( $mode='entry' ) {
    // The "Text Editor Interface" for Simple PHP Blog
    //
    // This is used on the "add.php", "preview_cgi.php", "add_static.php", "preview_static_cgi.php" pages. I'm planning on using it for static and comments pages also...
    global $lang_string, $user_colors, $blog_config, $theme_vars;
    
    // Include Supporting Java Script
    require_once('scripts/sb_editor.php');
    
    // Default Form Values
    $default_subject = null; // Value for Subject input field
    $default_content = null; // Value for Entry input field
    $default_tb_ping = null; // Only when blog_trackback_enabled = true
    $default_categories = null; // Field not required...
    $default_relatedlink = null; // Field not required...
    $default_time = time(); // Defaults to "now"
    $default_y = null; // Only in "Edit" mode...
    $default_m = null; // Only in "Edit" mode...
    $default_entry = null; // Only in "Edit" mode...
    $default_filename = null; // Only for Static Entries
    
    if ( $mode == 'static' ) {
      // ------------
      // Add Static Mode
      // ------------
    
      if ( array_key_exists( 'entry', $_GET ) ) {
        // -------------------
        // Edit Existing Static Entry
        // -------------------
        $default_entry = $_GET[ 'entry' ];
    
        // Read Saved Static Entry
        if ( file_exists( CONTENT_DIR.'static/' . $default_entry . '.txt' ) ) {
          $filename = CONTENT_DIR.'static/' . $default_entry . '.txt';
        } elseif ( file_exists( CONTENT_DIR.'static/' . $default_entry . '.txt.gz' ) ) {
          $filename = CONTENT_DIR.'static/' . $default_entry . '.txt.gz';
        }
        $blog_entry_data = static_entry_to_array( $filename );
        
        // Store Data for Form Use
        $default_subject = htmlDecode( $blog_entry_data[ 'SUBJECT' ] );
        $default_content = $blog_entry_data[ 'CONTENT' ];
        $default_filename = $default_entry;
        $default_menu_visible = $blog_entry_data[ 'MENU_VISIBLE' ];
        
        // Display Current Entry
        $entry_content = get_static_entry_by_file( $default_entry );
        echo( $entry_content );

      } else if ( array_key_exists( 'blog_subject', $_POST ) == true ) {
        // ----------------
        // Preview Static Entry
        // ----------------
        
        // (These will only be set if previewing an existing entry that your are editing...)
        $default_entry = array_key_exists( 'entry', $_POST ) ? $_POST[ 'entry' ] : $default_entry;

        // Store Data for Form Use
        $default_subject = sb_stripslashes( $_POST[ 'blog_subject' ] );
        $default_content = sb_stripslashes( $_POST[ 'blog_text' ] );
        $default_filename = sb_stripslashes( $_POST[ 'file_name' ] );
        $default_menu_visible = true;
        // Display Preview Entry  
        $entry_content = preview_static_entry( $default_subject, $default_content );
        echo( $entry_content );
        
      } else {
        // -------------
        // New Static Entry
        // -------------
        $default_filename = 'static' . date('ymd-His');
        $default_menu_visible = true;
      }
      
    }
    
    if ( $mode == 'entry' ) {
      // ------------
      // Add Entry Mode
      // ------------
    
      if ( array_key_exists( 'entry', $_GET ) == true && array_key_exists( 'y', $_GET ) == true && array_key_exists( 'm', $_GET ) == true ) {
        // --------------
        // Edit Existing Entry
        // --------------
        $default_y = $_GET[ 'y' ];
        $default_m = $_GET[ 'm' ];
        $default_entry = $_GET[ 'entry' ];
      
        $entry = CONTENT_DIR.$_GET[ 'y' ].'/'.$_GET[ 'm' ].'/'.$_GET[ 'entry' ];
        
        // Read Saved Entry
        if ( file_exists( $entry . ".txt" ) ) {
          $filename = $entry . ".txt";
        } else if ( file_exists( $entry . ".txt.gz" ) ) {
          $filename = $entry . ".txt.gz";
        }
        $blog_entry_data = blog_entry_to_array( $filename );
        
        // Store Data for Form Use
        $default_subject = htmlDecode( $blog_entry_data[ 'SUBJECT' ] );
        $default_content = $blog_entry_data[ 'CONTENT' ];
        $default_tb_ping = htmlDecode( $blog_entry_data[ 'TB_PING' ] );
        if ( array_key_exists( "CATEGORIES", $blog_entry_data ) ) {
          $default_categories = explode( ',', $blog_entry_data[ 'CATEGORIES' ] );
        }
        $default_relatedlink = htmlDecode( $blog_entry_data[ 'relatedlink'] );
        
        // Split up Date Information
        $temp_date = substr($_GET['entry'],-13,6);
        $temp_time = substr($_GET['entry'],-6,6);
        $dd = substr($temp_date,-2,2);
        $mt = substr($temp_date,-4,2);
        $yy = substr($temp_date,-6,2);
        if ($yy >= 95) {
          $yy = '19' . $yy;
        } else {
          $yy = '20' . $yy;
        }
        $hh = substr($temp_time,-6,2);
        $mm = substr($temp_time,-4,2);
        $ss = substr($temp_time,-2,2);
        
        // Create Time
        $default_time = mktime($hh, $mm, $ss, $mt, $dd, $yy );
        
        // Display Current Entry
        $entry_content = read_entry_from_file( $entry );
        echo( $entry_content );
        
      } else if ( array_key_exists( 'blog_subject', $_POST ) == true ) {
        // -----------
        // Preview Entry
        // -----------
        
        // (These will only be set if previewing an existing entry that your are editing...)
        $default_y = array_key_exists( 'y', $_POST ) ? $_POST[ 'y' ] : $default_y;
        $default_m = array_key_exists( 'm', $_POST ) ? $_POST[ 'm' ] : $default_m;
        $default_entry = array_key_exists( 'entry', $_POST ) ? $_POST[ 'entry' ] : $default_entry;
        
        // Store Data for Form Use
        $default_subject = sb_stripslashes( $_POST[ 'blog_subject' ] );
        $default_content = sb_stripslashes( $_POST[ 'blog_text' ] );
        $default_tb_ping = array_key_exists( 'tb_ping', $_POST ) ? sb_stripslashes( $_POST[ 'tb_ping' ] ): $default_tb_ping;
        $default_categories = array_key_exists( 'catlist', $_POST ) ? $_POST[ 'catlist' ]: $default_categories;
        $default_relatedlink = array_key_exists( 'blog_relatedlink', $_POST ) ? sb_stripslashes( $_POST[ 'blog_relatedlink' ] ): $default_relatedlink;
        
        // Create Time
        $default_time = mktime($_POST['hour'], $_POST['minute'], $_POST['second'], $_POST['month'], $_POST['day'], $_POST['year'] ); // Required
        
        // Display Preview Entry      
        $entry_content = preview_entry( $default_subject, $default_content, $default_tb_ping, $default_relatedlink, $default_time );
        echo( $entry_content );
        
      } else {
        // --------
        // New Entry
        // --------
      }
      
    }
    
    // --------------
    // Misc. Form Values
    // --------------
    switch ( $mode ) {
      case 'entry' :
        $submit_page = 'add_cgi.php';
        $preview_page = 'preview_cgi.php';
        $validate_script = 'validate';
        break;
      case 'static' :
        $submit_page = 'add_static_cgi.php';
        $preview_page = 'preview_static_cgi.php';
        $validate_script = 'validate_static';
        break;
    }
    
    
    // --------
    // Begin Form
    // --------
    ?>
    <form action='<?php echo( $submit_page ); ?>' method="post" name="editor" id="editor" onsubmit="return <?php echo( $validate_script ); ?>(this)">
      <?php
      
        // Hidden Fields
        if ( $mode == 'entry' ) {
          if ( isset($default_y) && isset($default_m) && isset($default_entry) ) {
            echo( HTML_input( false, "y", $default_y, false, 'hidden' ) );
            echo( HTML_input( false, "m", $default_m, false, 'hidden' ) );
            echo( HTML_input( false, "entry", $default_entry, false, 'hidden' ) );
          }
        }
        if ( $mode == 'static' ) {
          if ( isset($default_entry) ) {
            echo( HTML_input( false, "entry", $default_entry, false, 'hidden' ) );
          }
        }
        
        // Subject Input
        // HTML_input( $label=false, $id, $value=null, $add_returns=true, $type='text', $size=null, $maxlength=null, $onchange=null, $width=0, $disabled=false, $autocomplete=false );
        echo( HTML_input( $lang_string[ 'label_subject' ], 'blog_subject', $default_subject, true, 'text', 0, null, null, $theme_vars[ 'max_image_width' ] ) );
        
        // Date Selection Block
        if ( $mode == 'entry' ) {
          editor_date_select( $default_time );
        }
        
        // Style Tag Buttons
        editor_style_buttons( $mode );
        
        // Style Tag Dropdown
        editor_style_dropdown();
        
        // Emoticon Selection
        emoticons_show();
        
        // Image Selection Dropdown
        editor_image_dropdown();
        
        // Content Text Area


        // Static File Name
        if ( $mode == 'static' ) {
           echo( '<input type="checkbox" id="check_visiblemenu" name="check_visiblemenu"' );
           if ( $default_menu_visible == true ) { echo ( ' checked' ); }
           echo( '> ' . $lang_string[ 'chk_visiblemenu' ] . '<p />' );
        }
      ?>

      <label for="blog_text"><?php echo( $lang_string[ 'label_entry' ] ); ?></label><br />
      <textarea style="width: <?php echo( $theme_vars[ 'max_image_width' ] ); ?>px;" id="blog_text" name="blog_text" rows="20" cols="50" autocomplete="OFF" onselect="storeCaret(this);" onclick="storeCaret(this);" onkeyup="storeCaret(this);"><?php echo( $default_content ); ?></textarea><p />
      <?php
      
        // Related Link Input
        if ( $mode == 'entry' ) {
          echo( HTML_input( $lang_string[ 'label_relatedlink' ], 'blog_relatedlink', $default_relatedlink, true, 'text', null, null, null, $theme_vars[ 'max_image_width' ] ) );
        }
          
        // Trackback Input
        if ( $mode == 'entry' ) {
          if ( $blog_config[ 'blog_trackback_enabled' ] ) {
            $value = $default_tb_ping;
            if ( $blog_config[ 'blog_trackback_auto_discovery' ] ) {
              if ( $default_tb_ping ) {
                $value = $default_tb_ping;
              } else {
                $value = $lang_string[ 'label_tb_autodiscovery' ];
              }
            }
            echo( HTML_input( $lang_string[ 'label_tb_ping' ], 'tb_ping', $value, true, 'text', null, null, null, $theme_vars[ 'max_image_width' ] ) );
          }
        }
          
        // Category Selection Box
        if ( $mode == 'entry' ) {
          category_selection_box( $default_categories );
        }

        // Static File Name
        if ( $mode == 'static' ) {
          editor_static_file( $default_filename );
        }
      ?>
      
      <input type="submit" name="preview" value="<?php echo( $lang_string[ 'btn_preview' ] ); ?>" onclick="this.form.action='<?php echo( $preview_page ); ?>';" />
      <input type="submit" name="submit" value="<?php echo( $lang_string[ 'btn_post' ] ); ?>" onclick="this.form.action='<?php echo( $submit_page ); ?>';" /><p />
    </form>
    <?php
  }
  
  function editor_static_file( $filename ) {
    // Static File Name
    global $lang_string;
  
    ?>
      <label for="file_name"><?php echo( $lang_string[ 'file_name' ] ); ?></label><br/>
      <input type="text" name="file_name" autocomplete="OFF" size="40" value="<?php echo $filename; ?>"><br /><br />
    <?php
  }
  
  function editor_style_buttons( $mode ) {
    // Style Tag Buttons
    global $lang_string;
    
    echo( $lang_string[ 'label_insert' ] . '<br />' );
    ?>
      <input type="button" class="bginput" value="<?php echo( $lang_string[ 'btn_bold' ] ); ?>" onclick="ins_styles(this.form.blog_text,'b','');" />
      <input type="button" class="bginput" value="<?php echo( $lang_string[ 'btn_italic' ] ); ?>" onclick="ins_styles(this.form.blog_text,'i','');" />
			<input type="button" class="bginput" value="Center" onclick="ins_styles(this.form.blog_text,'center','');" />
      <input type="button" class="bginput" value="<?php echo( $lang_string[ 'btn_url' ] ); ?>" onclick="ins_url(this.form.blog_text);" />
      <input type="button" class="bginput" value="<?php echo( $lang_string[ 'btn_image' ] ); ?>" onclick="ins_image_v2(this.form.blog_text);"/>			
    <?php
    if ( $mode == 'entry' ) {
    ?>
      <input type="button" class="bginput" value="<?php echo( $lang_string[ 'btn_readmore' ] ); ?>" onclick="insertAtCaret(this.form.blog_text,'[more]');"/>
    <?php
    }
  }
  
  function editor_style_dropdown() {
    // Style Tag Dropdown
    global $lang_string;

    ?><br />
      <select name="style_dropdown" onchange="ins_style_dropdown(this.form.blog_text,this.form.style_dropdown.value);">
        <option label="--" value="--">--</option>
        <option label="[blockquote]xxx[/blockquote]" value="blockquote">[blockquote]xxx[/blockquote]</option>
        <option label="[pre]xxx[/pre]" value="pre">[pre]xxx[/pre]</option>
        <option label="[code]xxx[/code]" value="code">[code]xxx[/code]</option>
        <option label="[strong]xxx[/strong]" value="strong">[strong]xxx[/strong]</option>
        <option label="[b]xxx[/b]" value="b">[b]xxx[/b]</option>
        <option label="[em]xxx[/em]" value="em">[em]xxx[/em]</option>
        <option label="[i]xxx[/i]" value="i">[i]xxx[/i]</option>
        <option label="[h?]xxx[/h?] (?=1-6)" value="h?">[h?]xxx[/h?] (?=1-6)</option>
        <option label="[html]xxx[/html]" value="html">[html]xxx[/html]</option>
        <option label="[del]xxx[/del]" value="del">[del]xxx[/del]</option>
        <option label="[ins]xxx[/ins]" value="ins">[ins]xxx[/ins]</option>
        <option label="[strike]xxx[/strike]" value="strike">[strike]xxx[/strike]</option>
      </select>
      <input type="button" class="bginput" value="ok" onclick="ins_style_dropdown(this.form.blog_text,this.form.style_dropdown.value);"/><p />
    <?php
  }

  function editor_image_dropdown () {
    // Image Selection Dropdown
    global $lang_string, $theme_vars;
    
    $str = image_dropdown();
    if ( $str !== NULL ) {
      echo( '<a href="javascript:openpopup(\'image_list.php\',' . $theme_vars[ 'popup_window' ][ 'width' ] . ',' . $theme_vars[ 'popup_window' ][ 'height' ] . ',true);">' . $lang_string[ 'view_images' ] . '</a><br />' . $str . '<p />' );
    }
  }
  
  function image_dropdown () {
    // Get a list of images in the image folder. Return HTML.
    //
    global $theme_vars;
    
    // Changed this to only display Image files. This function
    // is used in comment.php if the blog owner has img tag
    // enabled for comments. (09/08/05 - alex)
    $dir = IMAGES_DIR;
    $contents = sb_folder_listing( $dir, array( '.jpg', '.jpeg', '.gif', '.png' ) );
    
    $str = NULL;
    if ($contents) {
      if ( count( $contents ) > 0 ) {
      
        $itemArray = array();
        $item = array( 'label' => '--', 'value' => '--' );
        array_push( $itemArray, $item );
        
        for ( $i = 0; $i < count( $contents ); $i++ ) {
          $str_url = $dir.$contents[$i];
          $img_size = @getimagesize( $str_url );
          if ( $img_size == false ) {
            // Image is invalid...
          } else {
            $width = $img_size[0];
            $height = $img_size[1];
            $max_image_width = $theme_vars[ 'max_image_width' ];
            /*
            if ( $width > $max_image_width ) {
              $height_resized = round( $height * ( $max_image_width / $width ) );
              $width_resized = $max_image_width;
            }
            */
            $temp_label = $contents[$i].' - '.$width.' x '.$height;
            $temp_value = '[img='.$dir.$contents[$i].' popup=false]';
            $temp_string = $contents[$i];
            
            $item = array( 'label' => $temp_label, 'value' => $temp_value, 'string' => $temp_string );
            array_push( $itemArray, $item );
          }
        }
        $str = HTML_dropdown( false, 'image_list', $itemArray, false, 'ins_image_dropdown(this.form.blog_text,this.form.image_list);' );
      }
    }
    
    return ( $str );
  }
  
  function editor_date_select( $default_time ) {
    // Date Selection Block
    global $lang_string;
    
    // Edit / Select Date
    $default_time = isset( $default_time ) ? $default_time : time();
    
    $e_day = date('d', $default_time);
    $e_month = date('n', $default_time);
    $e_year = date('Y', $default_time);
    $e_hour = date('G', $default_time);
    $e_minute = date('i', $default_time);
    $e_second = date('s', $default_time);
    
    // Day Drop Down
    $itemArray = array();
    for ( $i = 31; $i >= 1; $i-- ) {
      $item = array( 'label' => $i, 'value' => $i );
      if ( $i == $e_day ) {
        $item['selected'] = true;
      }
      array_push( $itemArray, $item );
    }
    $dd_day = HTML_dropdown( $lang_string[ 'day' ], 'day', $itemArray, false );
    
    // Month Drop Down
    $itemArray = array();
    for ( $i = 12; $i >= 1; $i-- ) {
      $timestamp = mktime(0, 0, 0, $i, date('d'), date('Y'));
      
      $item = array( 'label' => strftime('%B', $timestamp), 'value' => date('m', $timestamp) );
      if ( $i == $e_month ) {
        $item['selected'] = true;
      }
      array_push( $itemArray, $item );
    }
    $dd_month = HTML_dropdown( $lang_string[ 'month' ], 'month', $itemArray, false );
    
    // Year Drop Down
    $itemArray = array();
    for ( $i = 1  ; $i >= -10; $i-- ) {
      $timestamp = mktime(0, 0, 0, date('m'), date('d'), date('Y')+$i);
      
      $item = array( 'label' => strftime('%Y', $timestamp), 'value' => date('Y', $timestamp) );
      if ( date('Y', $timestamp) == $e_year ) {
        $item['selected'] = true;
      }
      array_push( $itemArray, $item );
    }
    $dd_year = HTML_dropdown( $lang_string[ 'year' ], 'year', $itemArray, false );
    
    echo($dd_day . ' ' . $dd_month . ' ' . $dd_year . '<p />');
    
    // $timestamp = mktime(date('H'), date('i'), date('s'), date('m'), date('d'), date('Y'));
    
    // Hour
    $itemArray = array();
    for ( $i = 24; $i >= 1; $i-- ) {
      $timestamp = mktime($i, date('i'), date('s'), date('m'), date('d'), date('Y'));
      if ( $i < 12) {
        $item = array( 'label' => date('h \a\m / H', $timestamp), 'value' => date('H', $timestamp) );
      } else {
        $item = array( 'label' => date('h \p\m / H', $timestamp), 'value' => date('H', $timestamp) );
      }
        
      if ( $i == $e_hour ) {
        $item['selected'] = true;
      }
      array_push( $itemArray, $item );
    }
    $dd_hour = HTML_dropdown( $lang_string[ 'hour' ], 'hour', $itemArray, false );
    $dd_minute = '<label for="mm">'.$lang_string[ 'minute' ].'</label> <input name="minute" id="minute" type="text" value="'.$e_minute.'" size="2" maxlength="2" />';
    $dd_second = '<label for="mm">'.$lang_string[ 'second' ].'</label> <input name="second" id="second" type="text" value="'.$e_second.'" size="2" maxlength="2" />';
      
    echo($dd_hour . ' ' . $dd_minute . ' ' . $dd_second . '<p />');
  }
  
  function category_selection_box ( $selected_cat_array=array() ) {
    // Returns categories HTML.
    global $lang_string;
    
    $str = NULL;
    $catArray = get_category_array();
    if ( count($catArray) > 0) {
      $itemArray = array();
      for ( $i = 0; $i < count( $catArray ); $i++ ) {
        $id_number = $catArray[$i][0];
        $name_str = $catArray[$i][1];
        $space_count = $catArray[$i][2];
        
        $label = '';
        for ( $j = 0; $j < $space_count; $j++ ) {
          $label  .= '&nbsp;';
        }
        $label  .= $name_str;
        
        $selected = false;
        for ( $j=0; $j < count( $selected_cat_array ); $j++) {
          if ( $selected_cat_array[$j] == $id_number ) {
            $selected = true;
            break;
          }
        }
        
        $item = array( 'label' => $label, 'value' => $id_number, 'selected' => $selected );
        array_push( $itemArray, $item );
      }
      // HTML_dropdown( $label=false, $id, $itemArray, $add_returns=true, $onchange=null, $width=0, $size=0, $multiple=false, $disabled=false )
      $str = HTML_dropdown( $lang_string[ 'label_categories' ], 'catlist[]', $itemArray, true, null, 0, min( 8, count($catArray) ), true );
    }
    
    echo( $str );
  }
	
	function category_combo_box ( $fieldname, $selected_item ) {
    // Returns categories HTML.
    global $lang_string;
    
		$str = NULL;
    $catArray = get_category_array();
    if ( count($catArray) > 0) {
      $itemArray = array();
      for ( $i = 0; $i < count( $catArray ); $i++ ) {
        $id_number = $catArray[$i][0];
        $name_str = $catArray[$i][1];
        $space_count = $catArray[$i][2];
        
        $label = '';
        for ( $j = 0; $j < $space_count; $j++ ) {
          $label  .= '&nbsp;';
        }
        $label  .= $name_str;
        
        $selected = false;
        if ( $selected_item == $id_number ) {
						$selected = true;
            //break;
        }
        
        $item = array( 'label' => $label, 'value' => $id_number, 'selected' => $selected );
        array_push( $itemArray, $item );
      }
      $str = HTML_dropdown( false, $fieldname, $itemArray);
    }
    
    echo( $str );
  }

?>
