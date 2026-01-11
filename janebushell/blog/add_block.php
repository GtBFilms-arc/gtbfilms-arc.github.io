<?php
  // -------------------------------
  // Simple PHP Blog Add Blocks File
  // -------------------------------
  //
  // Name: Modern Theme v2
  // Author: Alexander Palmo
  // Version: 0.4.5b
  // Revised by Ridgarou

  // ---------------
  // INITIALIZE PAGE
  // ---------------
  require_once('scripts/sb_functions.php');
  global $logged_in;
  $logged_in = logged_in( true, true );
  
  read_config();
  
  require_once('languages/' . $blog_config[ 'blog_language' ] . '/strings.php');
  sb_language( 'add_block' );
  
  // ---------------
  // POST PROCESSING
  // ---------------
  if ( isset( $_POST[ 'block_name' ] ) ) {
    if ( !isset($_POST[ 'block_id' ] ) ) {
      $_POST[ 'block_id' ] = '';
    }
    $ok = write_block( sb_stripslashes( $_POST[ 'block_name' ] ), sb_stripslashes( $_POST[ 'block_content' ] ), $_POST[ 'block_id' ] );
  }
  
  if ( isset( $_GET[ 'action' ] ) ) {
    $action = sb_stripslashes( $_GET[ 'action' ] );
    if ( $action === 'edit' ) {
      $block_id = sb_stripslashes( $_GET[ 'block_id' ] );
      // nothing
    } else {
      $ok = modify_block( $action, sb_stripslashes( $_GET[ 'block_id' ] ) );
    }
  }
  
  // ------------
  // PAGE CONTENT
  // ------------
  function page_content() {
    global $block_id, $block_name, $block_content, $action, $lang_string, $theme_vars;
  
    // SUBJECT
    $entry_array = array();
    $entry_array[ 'subject' ] = $lang_string[ 'title' ];
    
    // PAGE CONTENT BEGIN
    ob_start();
    
    // Read blocks file.
    $filename = CONFIG_DIR.'blocks.txt';
    $result = sb_read_file( $filename );
    
    // Create array.
    $str = NULL;
    if ( $result ) {  
  
      $block_content = '';
      $block_name = '';
      if ($action != "edit") {
        $block_id = NULL;
      }
      
      $array = explode('|', $result);
      for ( $i = 0; $i < count( $array ); $i+=2 ) {
        // Create HTML
        
        // 1 - Name of Block
        $str  .= ( 1 + ($i/2) ) . ' - ' . $array[$i] . '<br />';
        
        //  up | down | edit | delete
        $str  .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
        if ( $i > 1 ) {
          $str  .= '<a href="add_block.php?action=up&block_id='.$i.'">' . $lang_string[ 'up' ] . '</a> | ';
        } else {
          $str  .= $lang_string[ 'up' ] . ' | ';
        }
        if ( $i < ( count( $array ) - 2 ) ) {
          $str  .= '<a href="add_block.php?action=down&block_id='.$i.'">' . $lang_string[ 'down' ] . '</a> | ';
        } else {
          $str  .= $lang_string[ 'down' ] . ' | ';
        }
        $str  .= '<a href="add_block.php?action=edit&block_id='.$i.'">' . $lang_string[ 'edit' ] . '</a> | ';
        $str  .= '<a href="add_block.php?action=delete&block_id='.$i.'">' . $lang_string[ 'delete' ] . '</a> ';
        $str  .= '<br /><br />';
        if ( $action === "edit" && $i == $block_id ) {
          $block_name = $array[$i];
          $block_content = $array[$i+1];
        } 
      }
      
      
      if ($action === "edit") {
        echo $lang_string[ 'instructions_edit' ] . '<p />';
      } else {
        echo $lang_string[ 'instructions_modify' ] . '<p />';
      }
    } else {
      echo $lang_string[ 'instructions' ] . '<p />';
    }
    
    echo( $str );
    
    echo( '<hr />' );
  ?>
  
    <form action='add_block.php' method="post" name="editor" id="editor" onsubmit="return validate_block(this)">
    
      <label for="blog_subject"><?php echo( $lang_string[ 'block_name' ] ); ?></label><br />
      <input type="text" name="block_name" autocomplete="OFF" value="<?php echo $block_name; ?>" size="40"><br /><br />
      
      <?php echo( $lang_string[ 'label_insert' ] ); ?><br />
      <input type="button" class="bginput" value="<?php echo( $lang_string[ 'btn_bold' ] ); ?>" onclick="ins_styles(this.form.block_content,'b','');" />
      <input type="button" class="bginput" value="<?php echo( $lang_string[ 'btn_italic' ] ); ?>" onclick="ins_styles(this.form.block_content,'i','');" />
      <input type="button" class="bginput" value="<?php echo( $lang_string[ 'btn_url' ] ); ?>" onclick="ins_url(this.form.block_content);" />
      <input type="button" class="bginput" value="<?php echo( $lang_string[ 'btn_image' ] ); ?>" onclick="ins_image_v2(this.form.block_content);"/>
      
      <select name="style_dropdown" onchange="ins_style_dropdown(this.form.block_content,this.form.style_dropdown.value);">
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
      <input type="button" class="bginput" value="ok" onclick="ins_style_dropdown(this.form.block_content,this.form.style_dropdown.value);" /><br /><br />
      
      <a href="javascript:openpopup('image_list.php',<?php echo( $theme_vars[ 'popup_window' ][ 'width' ] ); ?>,<?php echo( $theme_vars[ 'popup_window' ][ 'height' ] ); ?>,true);"><?php echo( $lang_string[ 'view_images' ] ); ?></a><br />
      <?php echo image_dropdown(); ?><br /><br />
      
      <label for="blog_text"><?php echo( $lang_string[ 'block_content' ] ); ?></label><br />
      <textarea style="width: <?php global $theme_vars; echo( $theme_vars[ 'max_image_width' ] ); ?>px;" id="text" name="block_content" rows="20" cols="50" autocomplete="OFF"><?php echo $block_content; ?></textarea><br /><br />
      
      <?php if( isset( $block_id ) ) { ?>
      <input type="hidden" name="block_id" value="<?php echo $block_id; ?>" />
      <?php } ?>
      <input type="submit" name="submit" value="&nbsp;<?php if ( isset ( $block_id ) && $action === 'edit' ) { echo $lang_string[ 'submit_btn_edit' ]; } else { echo $lang_string[ 'submit_btn_add' ]; } ?>&nbsp;" onclick="this.form.action='add_block.php';" />
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
  
  <?php require_once('scripts/sb_editor.php'); ?>
  <title><?php echo($blog_config[ 'blog_title' ]); ?> - <?php echo( $lang_string[ 'title' ] ); ?></title>
</head>
  <?php 
    // ------------
    // BEGIN OUTPUT
    // ------------
    theme_pagelayout();
  ?>
</html>
