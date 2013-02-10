<?php
/*
Template Name: Full Width
*/
get_header(); ?>
<div class="subpage">
<div class="subpageinner">
<div class="row">

		<!-- Row for main content area -->
		<div id="content" class="twelve columns" role="main">
	
			<div class="post-box">
				<?php get_template_part('loop', 'page'); ?>
			</div>

		</div><!-- End Content row -->
		
		<?php get_sidebar(); ?>
		</div></div></div>
<?php get_footer(); ?>