<style type="text/css">
  body {
    color: #<?php echo(get_user_color('txt_color')); ?>;
  }

  hr
  {
    color: #<?php echo(get_user_color('inner_border_color')); ?>;
    background-color: #<?php echo(get_user_color('inner_border_color')); ?>;
  }

  #header_image {
    border-color: #<?php echo(get_user_color('border_color')); ?>;
  }

  #header {
    border-color: #<?php echo(get_user_color('border_color')); ?>;
    color: #<?php echo(get_user_color('headline_txt_color')); ?>;
    background-color: #<?php echo(get_user_color('header_bg_color')); ?>;
  }

  #footer {
    color: #<?php echo(get_user_color('footer_txt_color')); ?>;
    background: #<?php echo(get_user_color('footer_bg_color')); ?>;
    border-top: 1px solid #<?php echo(get_user_color('border_color')); ?>;
  }

  h1, h2, h3, h4, h5, h6 {
    color: #<?php echo(get_user_color('headline_txt_color')); ?>;
  }

  #maincontent .blog_subject {
    color: #<?php echo(get_user_color('entry_title_text')); ?>;
    border-color: #<?php echo(get_user_color('entry_border')); ?>;
    background-color: #<?php echo(get_user_color('entry_border')); ?>;
  }

  #maincontent .blog_date {
    color: #<?php echo(get_user_color('date_txt_color')); ?>;
  }

  #maincontent .blog_categories {
    color: #<?php echo(get_user_color('date_txt_color')); ?>;
  }

  #maincontent .blog_body {
    border-color: #<?php echo(get_user_color('entry_border')); ?>;
    color: #<?php echo(get_user_color('entry_text')); ?>;
  }

  #maincontent .blog_body_solid  {
    border-color: #<?php echo(get_user_color('entry_border')); ?>;
    color: #<?php echo(get_user_color('entry_text')); ?>;
    background-color: #<?php echo(get_user_color('entry_border')); ?>;
  }

  #maincontent .entry_top {
    border-color: #<?php echo(get_user_color('entry_border')); ?>;
    background-color: #<?php echo(get_user_color('entry_border')); ?>;
  }

  #maincontent .entry_bottom {
    border-color: #<?php echo(get_user_color('entry_border')); ?>;
    background-color: #<?php echo(get_user_color('entry_border')); ?>;
  }

  #maincontent .blog_comment {
    background-color: #<?php echo(get_user_color('entry_title_bg')); ?>;
    border-color: #<?php echo(get_user_color('entry_border')); ?>;
  }

  /* Comment differences */

  #maincontent .blog_subject_comment {
    color: #<?php echo(get_user_color('menu_title_text')); ?>;
    border-color: #<?php echo(get_user_color('menu_border')); ?>;
    background-color: #<?php echo(get_user_color('menu_border')); ?>;
  }

  #maincontent .entry_top_comment {
    border-color: #<?php echo(get_user_color('menu_border')); ?>;
    background-color: #<?php echo(get_user_color('menu_border')); ?>;
  }

  #maincontent .entry_bottom_comment {
    border-color: #<?php echo(get_user_color('menu_border')); ?>;
    background-color: #<?php echo(get_user_color('menu_border')); ?>;
  }

  #maincontent .blog_body_comment {
    border-color: #<?php echo(get_user_color('menu_border')); ?>;
    color: #<?php echo(get_user_color('entry_text')); ?>;
  }

  #maincontent .blog_comment_comment {
    background-color: #<?php echo(get_user_color('menu_bg')); ?>;
    border-color: #<?php echo(get_user_color('menu_border')); ?>;
  }
  /* Side bar */

  #sidebar .menu_title {
    background-color: #<?php echo(get_user_color('menu_border')); ?>;
    color: #<?php echo(get_user_color('menu_title_text')); ?>;
    border-color: #<?php echo(get_user_color('menu_border')); ?>;
  }

  #sidebar .menu_body {
    border-color: #<?php echo(get_user_color('menu_border')); ?>;
    background-color: #<?php echo(get_user_color('menu_bg')); ?>;
  }

  a:link, a:visited {
    color: #<?php echo(get_user_color('link_reg_color')); ?>;
  }

  a:hover {
    color: #<?php echo(get_user_color('link_hi_color')); ?>;
  }

  a:active {
    color: #<?php echo(get_user_color('link_down_color')); ?>;
  }

  pre {
    width: <?php global $theme_vars; echo( $theme_vars[ 'max_image_width' ] ); ?>px;
    border-color: #<?php echo(get_user_color('inner_border_color')); ?>;
  }
</style>
