<style type="text/css">
  /* OVERRIDE COLORS */

  /* GENERAL SETTINGS */
  body {
    background-color: #<?php echo(get_user_color('bg_color')); ?>;
    color: #<?php echo(get_user_color('txt_color')); ?>;
  }

  hr  
  {
    color: #<?php echo(get_user_color('inner_border_color')); ?>;
    background-color: #<?php echo(get_user_color('inner_border_color')); ?>;
  }
  
  /* TYPEOGRAPHY */
  
  a:link, a:visited { color: #<?php echo(get_user_color('link_reg_color')); ?>; }
  a:hover { color: #<?php echo(get_user_color('link_hi_color')); ?>; }
  a:active { color: #<?php echo(get_user_color('link_down_color')); ?>; }
  
  pre {
    width: <?php global $theme_vars; echo( $theme_vars[ 'max_image_width' ] ); ?>px;
    border-color: #<?php echo(get_user_color('inner_border_color')); ?>;
  }
  
  /* HEADERS */
  
  h1, h2, h3, h4, h5, h6 {
    /* color: #<?php echo(get_user_color('headline_txt_color')); ?>; */
  }
  
  blockquote {
    color: #<?php echo(get_user_color('txt_color')); ?>;
    border-color: #<?php echo(get_user_color('inner_border_color')); ?>;
  }
  
  /* LAYOUT / PLACEMENT */
  
  #page {
    background-color: #<?php echo(get_user_color('main_bg_color')); ?>;
    border-color: #<?php echo(get_user_color('border_color')); ?>;
  }
  
  #popup {
    background-color: #<?php echo(get_user_color('main_bg_color')); ?>;
    border-color: #<?php echo(get_user_color('border_color')); ?>;
  }
  
  #header {
    border-color: #<?php echo(get_user_color('border_color')); ?>;
  }
  
  #title {
    color: #<?php echo(get_user_color('header_txt_color')); ?>;
    border-color: #<?php echo(get_user_color('border_color')); ?>;
    background-color: #<?php echo(get_user_color('header_bg_color')); ?>;
  }
  
  #footer {
    color: #<?php echo(get_user_color('footer_txt_color')); ?>;
    border-color: #<?php echo(get_user_color('border_color')); ?>;
    background: #<?php echo(get_user_color('footer_bg_color')); ?>;
  }
  
  /* CONTENT */
  
  /* REGULAR ENTRY */
  #content .entry .blog_title
  {
    border-color: #<?php echo(get_user_color('entry_border')); ?>;
    color: #<?php echo(get_user_color('entry_title_text')); ?>;
    background-color: #<?php echo(get_user_color('entry_title_bg')); ?>;
  }
  
  #content .entry .blog_body
  {
    border-color: #<?php echo(get_user_color('entry_border')); ?>;
    color: #<?php echo(get_user_color('entry_text')); ?>;
    background-color: #<?php echo(get_user_color('entry_bg')); ?>;
  }

  #content .entry .blog_body_solid  {
    border-color: #<?php echo(get_user_color('entry_border')); ?>;
    color: #<?php echo(get_user_color('entry_text')); ?>;
    background-color: #<?php echo(get_user_color('entry_border')); ?>;
  }
  
  #content .entry .blog_comment
  {
    border-color: #<?php echo(get_user_color('entry_border')); ?>;
    color: #<?php echo(get_user_color('entry_text')); ?>;
    background-color: #<?php echo(get_user_color('entry_bg')); ?>;
  }
  
  #content .entry .blog_date,
  #content .static .blog_date,
  #content .comment .blog_date
  {
    color: #<?php echo(get_user_color('date_txt_color')); ?>;
  }
  
  /* STATIC ENTRY */
  #content .static .blog_title
  {
    border-color: #<?php echo(get_user_color('static_border')); ?>;
    color: #<?php echo(get_user_color('static_title_text')); ?>;
    background-color: #<?php echo(get_user_color('static_title_bg')); ?>;
  }
  
  #content .static .blog_body
  {
    border-color: #<?php echo(get_user_color('static_border')); ?>;
    color: #<?php echo(get_user_color('static_text')); ?>;
    background-color: #<?php echo(get_user_color('static_bg')); ?>;
  }
  
  #content .static .blog_comment
  {
    border-color: #<?php echo(get_user_color('static_border')); ?>;
    color: #<?php echo(get_user_color('static_text')); ?>;
    background-color: #<?php echo(get_user_color('static_bg')); ?>;
  }
  
  /* COMMENT ENTRY */
  #content .comment .blog_title
  {
    border-color: #<?php echo(get_user_color('comment_border')); ?>;
    color: #<?php echo(get_user_color('comment_title_text')); ?>;
    background-color: #<?php echo(get_user_color('comment_title_bg')); ?>;
  }
  
  #content .comment .blog_body
  {
    border-color: #<?php echo(get_user_color('comment_border')); ?>;
    color: #<?php echo(get_user_color('comment_text')); ?>;
    background-color: #<?php echo(get_user_color('comment_bg')); ?>;
  }
  
  #content .comment .blog_comment
  {
    border-color: #<?php echo(get_user_color('comment_border')); ?>;
    color: #<?php echo(get_user_color('comment_text')); ?>;
    background-color: #<?php echo(get_user_color('comment_bg')); ?>;
  }
  
  /* SIDEBAR */
  
  #sidebar {
    border-color: #<?php echo(get_user_color('inner_border_color')); ?>;
    background-color: #<?php echo(get_user_color('menu_bg_color')); ?>;
  }
  
  #sidebar .menu_body a:link { color: #<?php echo(get_user_color('menu_link_reg_color')); ?>; }
  #sidebar .menu_body a:visited { color: #<?php echo(get_user_color('menu_link_reg_color')); ?>; }
  #sidebar .menu_body a:hover { color: #<?php echo(get_user_color('menu_link_hi_color')); ?>; }
  #sidebar .menu_body a:active { color: #<?php echo(get_user_color('menu_link_down_color')); ?>; }
  
  #sidebar .menu_title {
    border-color: #<?php echo(get_user_color('menu_border')); ?>;
    color: #<?php echo(get_user_color('menu_title_text')); ?>;
    background-color: #<?php echo(get_user_color('menu_title_bg')); ?>;
  }
  
  #sidebar .menu_body {
    border-color: #<?php echo(get_user_color('menu_border')); ?>;
    color: #<?php echo(get_user_color('menu_text')); ?>;
    background-color: #<?php echo(get_user_color('menu_bg')); ?>;
  }
</style>
