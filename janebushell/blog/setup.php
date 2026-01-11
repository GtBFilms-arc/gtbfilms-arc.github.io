<?php
  // ---------------
  // INITIALIZE PAGE
  // ---------------
  require_once('scripts/sb_functions.php');
  global $logged_in, $blog_config;
  $logged_in = logged_in( true, true );

  // Validate Language
  $temp_lang = '';
  if ( isset( $_POST['blog_language'] ) ) {
    $temp_lang = sb_stripslashes( $_POST['blog_language'] );
  } else if ( array_key_exists( 'blog_language', $_GET ) ) {
    $temp_lang = sb_stripslashes( $_GET['blog_language'] );
  }
  if (validate_language($temp_lang) == false) {
    $temp_lang = 'english';
  }

  $blog_config[ 'blog_language' ] = $temp_lang;

  require_once('languages/' . $blog_config[ 'blog_language' ] . '/strings.php');
  sb_language( 'setup' );

  read_config();

  // ---------------
  // POST PROCESSING
  // ---------------
  

  // ------------
  // PAGE CONTENT
  // ------------
  function page_content() {
    global $lang_string, $user_colors, $logged_in, $theme_vars, $blog_theme, $blog_config;
    
    // SUBJECT
    $entry_array = array();
    $entry_array[ 'subject' ] = $lang_string[ 'title' ];
    
    // PAGE CONTENT BEGIN
    ob_start(); ?>
    <?php echo( $lang_string[ 'instructions' ] ); ?><p />

    <!-- FORM -->
    <form action="setup_cgi.php" method="post" name="setup" name="setup" onsubmit="return validate(this)">

      <!-- LANGUAGE -->
      <a id="linkSetupLanguage" href="javascript:toggleBlock('SetupLanguage');">
        <img src="<?php global $blog_theme; $img_hide = 'themes/' . $blog_theme . '/images/minus.gif'; echo( $img_hide ); ?>" name="twisty">
        <h4 style="display: inline;"><?php echo( $lang_string[ 'title_language' ] ); ?></h4>
      </a><p />
      <div id="toggleSetupLanguage"><p />
        <?php
          $arr = array();
          $dir = 'languages/';

          clearstatcache();
          if ( is_dir($dir) ) {
            $dhandle = opendir($dir);
            if ( $dhandle ) {
              $sub_dir = readdir( $dhandle );
              while ( $sub_dir ) {
                if ( is_dir( $dir . $sub_dir ) == true && $sub_dir != '.' && $sub_dir != '..' ) {
                  $lang_dir = $sub_dir;
                  $lang_name = sb_read_file( $dir . $sub_dir . '/id.txt' );
                  if ( $lang_name ) {
                    $item = array();
                    $item['label'] = $lang_name;
                    $item['value'] = $lang_dir;
                    if ( $blog_config[ 'blog_language' ] == $item['value'] ) {
                      $item['selected'] = true;
                    }
                    array_push( $arr, $item );
                  }
                }
                $sub_dir = readdir( $dhandle );
              }
            }
            closedir( $dhandle );
          }

          // HTML_dropdown( $label=false, $id, $itemArray, $add_returns=true, $onchange=null, $width=0, $size=0, $multiple=false, $disabled=false )
          echo( HTML_dropdown( $lang_string[ 'blog_choose_language' ], "blog_language", $arr ) );
        ?>
      </div>

      <p />

      <!-- GENERAL -->
      <a id="linkSetupGeneral" href="javascript:toggleBlock('SetupGeneral');">
        <img src="<?php global $blog_theme; $img_hide = 'themes/' . $blog_theme . '/images/minus.gif'; echo( $img_hide ); ?>" name="twisty">
        <h4 style="display: inline;"><?php echo( $lang_string[ 'title_general' ] ); ?></h4>
      </a><p />
      <div id="toggleSetupGeneral" class="toggleBody"><p />
        <input type="checkbox" id="blog_enable_title" name="blog_enable_title"<?php if ( $blog_config[ 'blog_enable_title' ] == 1 ) { echo ( ' checked' ); } ?>> <?php echo( $lang_string[ 'blog_enable_title' ] ); ?><p />

        <label for="blog_header_graphic"><?php echo( $lang_string[ 'blog_header' ] ); ?></label><br />
        <input type="text" name="blog_header_graphic" value="<?php echo($blog_config[ 'blog_header_graphic' ]); ?>" autocomplete="OFF" size="40" style="width: <?php global $theme_vars; echo( $theme_vars[ 'max_image_width' ] - 20 ); ?>px;"><p />

        <label for="blog_title"><?php echo( $lang_string[ 'blog_title' ] ); ?></label><br />
        <input type="text" name="blog_title" value="<?php echo($blog_config[ 'blog_title' ]); ?>" autocomplete="OFF" size="40" style="width: <?php global $theme_vars; echo( $theme_vars[ 'max_image_width' ] - 20 ); ?>px;"><p />

        <label for="blog_author"><?php echo( $lang_string[ 'blog_author' ] ); ?></label><br />
        <input type="text" name="blog_author" value="<?php echo($blog_config[ 'blog_author' ]); ?>" autocomplete="OFF" size="40" style="width: <?php global $theme_vars; echo( $theme_vars[ 'max_image_width' ] - 20 ); ?>px;"><p />

        <label for="blog_email"><?php echo( $lang_string[ 'blog_email' ] ); ?></label><br />
        <input type="text" name="blog_email" value="<?php echo($blog_config[ 'blog_email' ]); ?>" autocomplete="OFF" size="40" style="width: <?php global $theme_vars; echo( $theme_vars[ 'max_image_width' ] - 20 ); ?>px;"><p />

        <label for="blog_avatar"><?php echo( $lang_string['blog_avatar' ] ); ?></label><br />
        <input type="text" name="blog_avatar" value="<?php echo($blog_config[ 'blog_avatar' ]); ?>" autocomplete="OFF" size="40" style="width: <?php global $theme_vars; echo( $theme_vars[ 'max_image_width' ] - 20 ); ?>px;"><p />

        <label for="blog_footer"><?php echo( $lang_string[ 'blog_footer' ] ); ?></label><br />
        <input type="text" name="blog_footer" value="<?php echo($blog_config[ 'blog_footer_only' ]); ?>" autocomplete="OFF" size="40" style="width: <?php global $theme_vars; echo( $theme_vars[ 'max_image_width' ] - 20 ); ?>px;"><p />
      </div>

      <p />

      <!-- ENTRIES -->
      <a id="linkSetupEntries" href="javascript:toggleBlock('SetupEntries');">
        <img src="<?php global $blog_theme; $img_hide = 'themes/' . $blog_theme . '/images/minus.gif'; echo( $img_hide ); ?>" name="twisty">
        <h4 style="display: inline;"><?php echo( $lang_string[ 'title_entries' ] ); ?></h4>
      </a><p />
      <div id="toggleSetupEntries" class="toggleBody"><p />
        <input type="checkbox" id="blog_enable_voting" name="blog_enable_voting"<?php if ( $blog_config[ 'blog_enable_voting' ] == 1 ) { echo ( ' checked' ); } ?>> <?php echo( $lang_string[ 'blog_enable_voting' ] ); ?><br />
        <input type="checkbox" id="blog_enable_permalink" name="blog_enable_permalink"<?php if ( $blog_config[ 'blog_enable_permalink' ] == 1 ) { echo ( ' checked' ); } ?>> <?php echo( $lang_string[ 'blog_enable_permalink' ] ); ?><br />
        <input type="checkbox" id="blog_enable_capcha" name="blog_enable_capcha"<?php if ( $blog_config[ 'blog_enable_capcha' ] == 1 ) { echo ( ' checked' ); } ?>> <?php echo( $lang_string[ 'blog_enable_capcha' ] ); ?><br />
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" id="blog_enable_capcha_image" name="blog_enable_capcha_image"<?php if ( $blog_config[ 'blog_enable_capcha_image' ] == 1 ) { echo ( ' checked' ); } if ( function_exists( 'imagecreate' )==false ) { echo(' disabled'); }?>> <?php echo( $lang_string[ 'blog_enable_capcha_image' ] ); ?><br />
        <input type="checkbox" id="blog_enable_cache" name="blog_enable_cache"<?php if ( $blog_config[ 'blog_enable_cache' ] == 1 ) { echo ( ' checked' ); } ?>> <?php echo( $lang_string[ 'blog_enable_cache' ] ); ?><br />
        <input type="checkbox" id="blog_send_pings" name="blog_send_pings"<?php if ( $blog_config[ 'blog_send_pings' ] == 1 ) { echo ( ' checked' ); } ?>> <?php echo( $lang_string[ 'blog_send_pings' ] ); ?><p />
        <label for="blog_ping_urls"><?php echo( $lang_string[ 'blog_ping_urls' ] ); ?></label><br />
        <input type="text" name="blog_ping_urls" value="<?php echo($blog_config[ 'blog_ping_urls' ]); ?>" autocomplete="OFF" size="40" style="width: <?php global $theme_vars; echo( $theme_vars[ 'max_image_width' ] - 20 ); ?>px;"><br />
        <br />
        <label for="blog_max_entries"><?php echo( $lang_string[ 'blog_max_entries' ] ); ?></label><br />
        <input type="text" name="blog_max_entries" value="<?php echo( $blog_config[ 'blog_max_entries' ] ); ?>" autocomplete="OFF" size="6">
        <p />
        <?php
          // Entry Order Drop Down
          $arr = array();
          array_push( $arr, array( 'label' => $lang_string[ 'select_new_to_old' ], 'value' => 'new_to_old', 'selected' => $blog_config[ 'blog_entry_order' ] == 'new_to_old' ) );
          array_push( $arr, array( 'label' => $lang_string[ 'select_old_to_new' ], 'value' => 'old_to_new', 'selected' => $blog_config[ 'blog_entry_order' ] == 'old_to_new' ) );
          echo( HTML_dropdown( $lang_string[ 'label_entry_order' ], "blog_entry_order", $arr ) );
        ?>
        <?php
          // Comment Order Drop Down
          $arr = array();
          array_push( $arr, array( 'label' => $lang_string[ 'select_new_to_old' ], 'value' => 'new_to_old', 'selected' => $blog_config[ 'blog_comment_order' ] == 'new_to_old' ) );
          array_push( $arr, array( 'label' => $lang_string[ 'select_old_to_new' ], 'value' => 'old_to_new', 'selected' => $blog_config[ 'blog_comment_order' ] == 'old_to_new' ) );
          echo( HTML_dropdown( $lang_string[ 'label_comment_order' ], "blog_comment_order", $arr ) );
        ?>
				<input type="checkbox" id="blog_enable_start_category" name="blog_enable_start_category"<?php if ( $blog_config[ 'blog_enable_start_category' ] == 1 ) { echo ( ' checked' ); } ?>> <?php echo( $lang_string[ 'blog_enable_start_category' ] ); ?>
          <?php
            category_combo_box( 'blog_enable_start_category_selection', intval($blog_config[ 'blog_enable_start_category_selection' ]) );             
          ?>
        </select></br>
      </div>

      <p />

      <!-- SIDEBAR -->
      <a id="linkSetupSidebar" href="javascript:toggleBlock('SetupSidebar');">
        <img src="<?php global $blog_theme; $img_hide = 'themes/' . $blog_theme . '/images/minus.gif'; echo( $img_hide ); ?>" name="twisty">
        <h4 style="display: inline;"><?php echo( $lang_string[ 'title_sidebar' ] ); ?></h4>
      </a><p />
      <div id="toggleSetupSidebar" class="toggleBody"><p />
        <input type="checkbox" id="blog_enable_login" name="blog_enable_login"<?php if ( $blog_config[ 'blog_enable_login' ] == 1 ) { echo ( ' checked' ); } ?>> <?php echo( $lang_string[ 'blog_enable_login' ] ); ?><br />
        <input type="checkbox" id="blog_enable_stats" name="blog_enable_stats"<?php if ( $blog_config[ 'blog_enable_stats' ] == 1 ) { echo ( ' checked' ); } ?>> <?php echo( $lang_string[ 'blog_enable_stats' ] ); ?><br />
        <input type="checkbox" id="blog_enable_lastcomments" name="blog_enable_lastcomments"<?php if ( $blog_config[ 'blog_enable_lastcomments' ] == 1 ) { echo ( ' checked' ); } ?>> <?php echo( $lang_string[ 'blog_enable_lastcomments' ] ); ?><br />
        <input type="checkbox" id="blog_enable_lastentries" name="blog_enable_lastentries"<?php if ( $blog_config[ 'blog_enable_lastentries' ] == 1 ) { echo ( ' checked' ); } ?>> <?php echo( $lang_string[ 'blog_enable_lastentries' ] ); ?><br />
        <input type="checkbox" id="blog_enable_archives" name="blog_enable_archives"<?php if ( $blog_config[ 'blog_enable_archives' ] == 1 ) { echo ( ' checked' ); } ?>> <?php echo( $lang_string[ 'blog_enable_archives' ] ); ?><br />
        <input type="checkbox" id="blog_footer_counter" name="blog_footer_counter"<?php if ( $blog_config[ 'blog_footer_counter' ] == 1 ) { echo ( ' checked' ); } ?>> <?php echo( $lang_string[ 'blog_footer_counter' ] ); ?><br />
        <input type="checkbox" id="blog_enable_counter" name="blog_enable_counter"<?php if ( $blog_config[ 'blog_enable_counter' ] == 1 ) { echo ( ' checked' ); } ?>> <?php echo( $lang_string[ 'blog_enable_counter' ] ); ?><br />
        <label for="blog_counter_hours"><?php echo( $lang_string[ 'blog_counter_hours' ] ); ?></label>
        <input type="text" name="blog_counter_hours" value="<?php echo( $blog_config[ 'blog_counter_hours' ] ); ?>" autocomplete="OFF" size="3"><p />
        <input type="checkbox" id="blog_enable_calendar" name="blog_enable_calendar"<?php if ( $blog_config[ 'blog_enable_calendar' ] == 1 ) { echo ( ' checked' ); } ?>> <?php echo( $lang_string[ 'blog_enable_calendar' ] ); ?><br />
        <label for="blog_calendar_start"><?php echo( $lang_string[ 'label_calendar_start' ] ); ?></label>
        <select name="blog_calendar_start">
          <option label="<?php echo( $lang_string[ 'cal_sunday' ] ); ?>" value="sunday"<?php if ($blog_config[ 'blog_calendar_start' ] == 'sunday') { echo ' selected'; } ?>><?php echo( $lang_string[ 'cal_sunday' ] ); ?></option>
          <option label="<?php echo( $lang_string[ 'cal_monday' ] ); ?>" value="monday"<?php if ($blog_config[ 'blog_calendar_start' ] == 'monday') { echo ' selected'; } ?>><?php echo( $lang_string[ 'cal_monday' ] ); ?></option>
        </select><p />
        <input type="checkbox" id="blog_search_top" name="blog_search_top"<?php if ( $blog_config[ 'blog_search_top' ] == 1 ) { echo ( ' checked' ); } ?>> <?php echo( $lang_string[ 'blog_search_top' ] ); ?><br />
        <input type="checkbox" id="blog_enable_static_block" name="blog_enable_static_block"<?php if ( $blog_config[ 'blog_enable_static_block' ] == 1 ) { echo ( ' checked' ); } ?>> <?php echo( $lang_string[ 'blog_enable_static_block' ] ); ?>
        <br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <select name="static_block_options">
          <?php
            $blocktitles = get_block_list();
            for ( $i = 0; $i < count( $blocktitles ); $i+=2 ) {
            if ( isset( $blocktitles[ $i ] ) ) {
              echo( '<option label="' . $blocktitles[ $i ] . '" value="' . $blocktitles[ $i ] . '"' );
                if ( $blog_config[ 'static_block_options' ] == $blocktitles[ $i ] ) {
                  echo( ' selected>' );
                } else {
                  echo( '>' ); }
                echo( $blocktitles[ $i ] . '</option>' );
              }
            }
          ?>
        </select>
        <select name="static_block_border">
          <option label="<?php echo( $lang_string[ 'static_block_border' ] ); ?>" value="border"<?php if ($blog_config[ 'static_block_border' ] == 'border') { echo ' selected'; } ?>><?php echo( $lang_string[ 'static_block_border' ] ); ?></option>
          <option label="<?php echo( $lang_string[ 'static_block_noborder' ] ); ?>" value="noborder"<?php if ($blog_config[ 'static_block_border' ] == 'noborder') { echo ' selected'; } ?>><?php echo( $lang_string[ 'static_block_noborder' ] ); ?></option>
        </select><br />

      </div>

      <p />

      <!-- TRACKBACK -->
      <a id="linkSetupTrackbacks" href="javascript:toggleBlock('SetupTrackbacks');">
        <img src="<?php global $blog_theme; $img_hide = 'themes/' . $blog_theme . '/images/minus.gif'; echo( $img_hide ); ?>" name="twisty">
        <h4 style="display: inline;"><?php echo( $lang_string[ 'title_trackback' ] ); ?></h4>
      </a><p />
      <div id="toggleSetupTrackbacks" class="toggleBody"><p />
        <?php echo( $lang_string[ 'blog_trackback_about' ] ); ?><p />
        <input type="checkbox" id="blog_trackback_enabled" name="blog_trackback_enabled"<?php if ( $blog_config[ 'blog_trackback_enabled' ] == 1 ) { echo ( ' checked' ); } ?>> <?php echo( $lang_string[ 'blog_trackback_enabled' ] ); ?><br />
        <input type="checkbox" id="blog_trackback_auto_discovery" name="blog_trackback_auto_discovery"<?php if ( $blog_config[ 'blog_trackback_auto_discovery' ] == 1 ) { echo ( ' checked' ); } ?>> <?php echo( $lang_string[ 'blog_trackback_auto_discovery' ] ); ?><p />
      </div>

      <p />

      <!-- COMMENTS -->
      <a id="linkSetupComments" href="javascript:toggleBlock('SetupComments');">
        <img src="<?php global $blog_theme; $img_hide = 'themes/' . $blog_theme . '/images/minus.gif'; echo( $img_hide ); ?>" name="twisty">
        <h4 style="display: inline;"><?php echo( $lang_string[ 'title_comments' ] ); ?></h4>
      </a><p />
      <div id="toggleSetupComments" class="toggleBody"><p />
        <input type="checkbox" id="blog_enable_comments" name="blog_enable_comments"<?php if ( $blog_config[ 'blog_enable_comments' ] == 1 ) { echo ( ' checked' ); } ?>> <?php echo( $lang_string[ 'blog_enable_comments' ] ); ?><br />
        <input type="checkbox" id="blog_email_notification" name="blog_email_notification"<?php if ( $blog_config[ 'blog_email_notification' ] == 1 ) { echo ( ' checked' ); } ?>> <?php echo( $lang_string[ 'blog_email_notification' ] ); ?><br />
        <input type="checkbox" id="blog_comments_popup" name="blog_comments_popup"<?php if ( $blog_config[ 'blog_comments_popup' ] == 1 ) { echo ( ' checked' ); } ?>> <?php echo( $lang_string[ 'blog_comments_popup' ] ); ?><p />

        <label for="blog_comment_days_expiry"><?php echo( $lang_string[ 'blog_comment_days_expiry' ] ); ?></label>
        <input type="text" name="blog_comment_days_expiry" value="<?php echo( $blog_config[ 'blog_comment_days_expiry' ] ); ?>" autocomplete="OFF" size="3"><p />

        <label for="area"><?php echo( $lang_string[ 'blog_comment_tags' ] ); ?></label><br />

        <table border="0" width="<?php global $theme_vars; echo( $theme_vars[ 'max_image_width' ] - 20 ); ?>" cellspacing="0" cellpadding="0">
          <tr align="left" valign="top">
            <td width="50%">
              <input type="checkbox" id="b" name="b"<?php if ( in_array( 'b', $blog_config[ 'comment_tags_allowed' ] ) ) { echo ( ' checked' ); } ?>> [b] = Bold<br />
              <input type="checkbox" id="i" name="i"<?php if ( in_array( 'i', $blog_config[ 'comment_tags_allowed' ] ) ) { echo ( ' checked' ); } ?>> [i] = Italic<br />
              <input type="checkbox" id="strong" name="strong"<?php if ( in_array( 'strong', $blog_config[ 'comment_tags_allowed' ] ) ) { echo ( ' checked' ); } ?>> [strong] = Strong<br />
							<input type="checkbox" id="center" name="center"<?php if ( in_array( 'center', $blog_config[ 'comment_tags_allowed' ] ) ) { echo ( ' checked' ); } ?>> [center] = Center<br />
              <input type="checkbox" id="em" name="em"<?php if ( in_array( 'em', $blog_config[ 'comment_tags_allowed' ] ) ) { echo ( ' checked' ); } ?>> [em] = Emphasis<br />
              <br />
              <input type="checkbox" id="del" name="del"<?php if ( in_array( 'del', $blog_config[ 'comment_tags_allowed' ] ) ) { echo ( ' checked' ); } ?>> [del] = Delete<br />
              <input type="checkbox" id="ins" name="ins"<?php if ( in_array( 'ins', $blog_config[ 'comment_tags_allowed' ] ) ) { echo ( ' checked' ); } ?>> [ins] = Insert<br />
              <input type="checkbox" id="strike" name="strike"<?php if ( in_array( 'strike', $blog_config[ 'comment_tags_allowed' ] ) ) { echo ( ' checked' ); } ?>> [strike] = Strike-out<br />
            </td>
            <td width="50%">
              <input type="checkbox" id="img" name="img"<?php if ( in_array( 'img', $blog_config[ 'comment_tags_allowed' ] ) ) { echo ( ' checked' ); } ?>> [img] = Image<br />
              <input type="checkbox" id="url" name="url"<?php if ( in_array( 'url', $blog_config[ 'comment_tags_allowed' ] ) ) { echo ( ' checked' ); } ?>> [url] = URL<br />
              <br />
              <input type="checkbox" id="blockquote" name="blockquote"<?php if ( in_array( 'blockquote', $blog_config[ 'comment_tags_allowed' ] ) ) { echo ( ' checked' ); } ?>> [blockquote] = Block Quote<br />
              <input type="checkbox" id="hN" name="hN"<?php if ( in_array( 'hN', $blog_config[ 'comment_tags_allowed' ] ) ) { echo ( ' checked' ); } ?>> [hN] = Headline<br />
              <br />
              <input type="checkbox" id="pre" name="pre"<?php if ( in_array( 'pre', $blog_config[ 'comment_tags_allowed' ] ) ) { echo ( ' checked' ); } ?>> [pre] = Preformatted<br />
              <input type="checkbox" id="code" name="code"<?php if ( in_array( 'code', $blog_config[ 'comment_tags_allowed' ] ) ) { echo ( ' checked' ); } ?>> [code] = Code<br />
              <input type="checkbox" id="html" name="html"<?php if ( in_array( 'html', $blog_config[ 'comment_tags_allowed' ] ) ) { echo ( ' checked' ); } ?>> [html] = HTML<br />
            </td>
          </tr>
        </table><p />
        <label for="area"><b><?php echo( $lang_string[ 'comment_moderation' ] ); ?></b></label><br />
        <input type="checkbox" id="blog_comments_moderation" name="blog_comments_moderation"<?php if ( $blog_config[ 'blog_comments_moderation' ] == 1 ) { echo ( ' checked' ); } ?>> <?php echo( $lang_string[ 'blog_comments_moderation' ] ); ?><p />

      </div>

      <p />

      <!-- COMPRESSION -->
      <a id="linkSetupCompression" href="javascript:toggleBlock('SetupCompression');">
        <img src="<?php global $blog_theme; $img_hide = 'themes/' . $blog_theme . '/images/minus.gif'; echo( $img_hide ); ?>" name="twisty">
        <h4 style="display: inline;"><?php echo( $lang_string[ 'title_compression' ] ); ?></h4>
      </a><p />
      <div id="toggleSetupCompression" class="toggleBody"><p />
        <?php echo( $lang_string[ 'blog_gzip_about' ] ); ?><p />
        <input type="checkbox" id="blog_enable_gzip_txt" name="blog_enable_gzip_txt"<?php if ( $blog_config[ 'blog_enable_gzip_txt' ] == 1 ) { echo ( ' checked' ); } if ( extension_loaded( 'zlib' ) == false ) { echo ( ' disabled' ); } ?>> <?php echo( $lang_string[ 'blog_enable_gzip_txt' ] ); ?><br />
        <input type="checkbox" id="blog_enable_gzip_output" name="blog_enable_gzip_output"<?php if ( $blog_config[ 'blog_enable_gzip_output' ] == 1 ) { echo ( ' checked' ); } if ( extension_loaded( 'zlib' ) == false ) { echo ( ' disabled' ); } ?>> <?php echo( $lang_string[ 'blog_enable_gzip_output' ] ); ?><p />
      </div>

      <p />

      <!-- SUBMIT -->
      <input type="submit" name="submit" value="<?php echo( $lang_string[ 'submit_btn' ] ); ?>" />
    </form>

    <?php
    // PAGE CONTENT END
    $entry_array[ 'entry' ] = ob_get_clean();
    
    // THEME ENTRY
    echo( theme_staticentry( $entry_array ) );
  }
  
  // ----
  // HTML
  // ----
?>
  <?php echo( get_init_code() ); ?>
  <?php require_once('themes/' . $blog_theme . '/user_style.php'); ?>

  <style type="text/css">
    div #toggleSetupLanguage, #toggleSetupGeneral, #toggleSetupEntries, #toggleSetupSidebar, #toggleSetupTrackbacks, #toggleSetupComments, #toggleSetupCompression
    {
      padding: 0px 10px 0px 10px;
      border-color: #<?php echo(get_user_color('inner_border_color')); ?>;
      border-width: 1px;
      border-style: dashed;
      display: block;
    }
  </style>

  <script type="text/javascript">
    <!--

    function validate(theform) {
      if (theform.blog_title.value=="" || theform.blog_author.value=="" ) {
        alert("<?php echo( $lang_string[ 'form_error' ] ); ?>");
        return false;
      } else {
        return true;
      }
    }

    //-->
  </script>
  <title><?php echo( $blog_config[ 'blog_title' ] ); ?> - <?php echo( $lang_string[ 'title' ] ); ?></title>
</head>
  <?php 
    // ------------
    // BEGIN OUTPUT
    // ------------
    theme_pagelayout();
  ?>
</html>
