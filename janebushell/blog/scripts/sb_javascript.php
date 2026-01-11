
<script type="text/javascript">
	<!--
	// BLOG SETTINGS
	var blogSettings = Array();
	blogSettings['theme'] = '<?php global $blog_theme; echo( $blog_theme ); ?>';
	blogSettings['img_path'] = '<?php global $blog_theme; echo( "themes/" . $blog_theme . "/images/" ); ?>';
	blogSettings['content_width'] = <?php global $theme_vars; echo( $theme_vars[ 'content_width' ] ? $theme_vars[ 'content_width' ] : 0 ); ?>;
	blogSettings['menu_width'] = <?php global $theme_vars; echo(  $theme_vars[ 'menu_width' ] ? $theme_vars[ 'menu_width' ] : 0 ); ?>;
	-->
</script>
