<style type="text/css">
  body {
    background-color: #<?php echo(get_user_color('bg_color')); ?>;
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
    color: #<?php echo(get_user_color('header_txt_color')); ?>;
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
    color: #<?php echo(get_user_color('headline_txt_color')); ?>;
  }

  #maincontent .blog_date {
    color: #<?php echo(get_user_color('date_txt_color')); ?>;
  }

  #maincontent .blog_categories {
    color: #<?php echo(get_user_color('date_txt_color')); ?>;
  }

  #maincontent .blog_body_solid  {
    color: #<?php echo(get_user_color('entry_text')); ?>;
    background-color: #<?php echo(get_user_color('bg_color')); ?>;
    border-color: #<?php echo(get_user_color('entry_text')); ?>;
  }
  
  #sidebar .menu_body {
    border-color: #<?php echo(get_user_color('inner_border_color')); ?>;
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
