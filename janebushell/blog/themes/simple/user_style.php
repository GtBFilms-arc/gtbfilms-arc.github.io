<style type="text/css">
	body {
		color: #<?php echo(get_user_color('txt_color')); ?>;
		background-color: #<?php echo(get_user_color('main_bg_color')); ?>;
	}
	
	pre {
		width: <?php global $theme_vars; echo( $theme_vars[ 'max_image_width' ] ); ?>px;
		border-color: #<?php echo(get_user_color('inner_border_color')); ?>;
	}
	
	blockquote {
		border-color: #<?php echo(get_user_color('inner_border_color')); ?>;
	}

	h1, h2, h3, h4, h5, h6 {
		color: #<?php echo(get_user_color('headline_txt_color')); ?>;
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

	hr	
	{
		color: #<?php echo(get_user_color('inner_border_color')); ?>;
		background-color: #<?php echo(get_user_color('inner_border_color')); ?>;
	}

	.copyright {
		color: #<?php echo(get_user_color('footer_txt_color')); ?>;
	}

	.subject {
		color: #<?php echo(get_user_color('headline_txt_color')); ?>;
	}

	.byline {
		color: #<?php echo(get_user_color('date_txt_color')); ?>;
	}
	
	#navigation {
		color: #<?php echo(get_user_color('header_txt_color')); ?>;
	}
	
	#header {
		background-color: #<?php echo(get_user_color('header_bg_color')); ?>;
	}
	
	#footer {
		background-color: #<?php echo(get_user_color('footer_bg_color')); ?>;
	}
	
	#sidebar a:link, #sidebar a:visited
	{
		color: #<?php echo(get_user_color('menu_link_reg_color')); ?>;
	}
	
	#sidebar a:hover
	{
		color: #<?php echo(get_user_color('menu_link_hi_color')); ?>;
	}
	
	#sidebar a:active
	{
		color: #<?php echo(get_user_color('menu_link_down_color')); ?>;
	}
	
	#sidebar .menu_title,  #sidebar .menu_title a:link, #sidebar .menu_title a:visited, #sidebar .menu_title a:hover, #sidebar .menu_title a:active {
		color: #<?php echo(get_user_color('menu_title_text')); ?>;
		background-color: #<?php echo(get_user_color('menu_title_bg')); ?>;
		border-color: #<?php echo(get_user_color('menu_border')); ?>;
	}
	
	#sidebar .menu_body {
		color: #<?php echo(get_user_color('menu_text')); ?>;
		background-color: #<?php echo(get_user_color('menu_bg')); ?>;
		border-color: #<?php echo(get_user_color('menu_border')); ?>;
	}
	
	.subject {
		color: #<?php echo(get_user_color('entry_title_text')); ?>;
	}
	
	/* entry_bg
	entry_title_bg
	entry_border
	entry_title_text
	entry_text */
</style>