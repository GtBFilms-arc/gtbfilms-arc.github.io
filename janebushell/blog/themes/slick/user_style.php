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
	
	a:link, a:visited {
		color: #<?php echo(get_user_color('link_reg_color')); ?>;
	}
	
	a:hover {
		color: #<?php echo(get_user_color('link_hi_color')); ?>;
	}
	
	a:active {
		color: #<?php echo(get_user_color('link_down_color')); ?>;
	}
	
	#maincontent .blog_title {
		border-color: #<?php echo(get_user_color('entry_border')); ?>;
		color: #<?php echo(get_user_color('entry_title_text')); ?>;
		background-color: #<?php echo(get_user_color('entry_bg')); ?>;
	}
	
	#maincontent .blog_body {
		border-color: #<?php echo(get_user_color('entry_border')); ?>;
		color: #<?php echo(get_user_color('entry_text')); ?>;
		background-color: #<?php echo(get_user_color('entry_bg')); ?>;
	}
	
	#maincontent .blog_comment {
		border-color: #<?php echo(get_user_color('entry_border')); ?>;
		color: #<?php echo(get_user_color('entry_text')); ?>;
		background-color: #<?php echo(get_user_color('entry_bg')); ?>;
	}
	
	#maincontent .blog_date {
		color: #<?php echo(get_user_color('date_txt_color')); ?>;
	}
	
	#sidebar .menu_title {
		border-color: #<?php echo(get_user_color('menu_border')); ?>;
		color: #<?php echo(get_user_color('menu_title_text')); ?>;
		/*background-color: #<?php echo(get_user_color('menu_title_bg')); ?>;*/
	}
	
	#sidebar .menu_body {
		border-color: #<?php echo(get_user_color('menu_border')); ?>;
		color: #<?php echo(get_user_color('menu_text')); ?>;
		/*background-color: #<?php echo(get_user_color('menu_bg')); ?>;*/
	}
	
	#sidebar .menu_body a:link {
		color: #<?php echo(get_user_color('menu_link_reg_color')); ?>;
	}
	
	#sidebar .menu_body a:visited {
		color: #<?php echo(get_user_color('menu_link_reg_color')); ?>;
	}
	
	#sidebar .menu_body a:hover {
		color: #<?php echo(get_user_color('menu_link_hi_color')); ?>;
	}
	
	#sidebar .menu_body a:active {
		color: #<?php echo(get_user_color('menu_link_down_color')); ?>;
	}
</style>
