<?php
/*
Template Name: Landing Page
*/
get_header(); ?>

<div class="topper">
		<div class="row"><!-- Row for main content area -->
		<div id="content" class="twelve columns" role="main">
	
			<div class="post-box seven columns first">
				
				<h1 style="font-family:'<?php the_field('font_used','options'); ?>';"><?php the_field('headline','options'); ?></h1>
				<p><?php the_field('header_text','options'); ?></p>
				<p><a class="button" href="<?php the_field('header_call_to_action_url','options'); ?>, title="<?php the_field('header_call_to_action_name','options'); ?>""><?php the_field('header_call_to_action_name','options'); ?></a></p>
				
			</div>
			<div class="cartoon five columns last ">
						
											</div>
	
			
			</div><!-- End Content row -->
			</div>
<div class="clearfix"></div>
</div>
<div id="featureswrap">
<div class="row">
<div class="featured twelve colums">



<?php while(the_repeater_field('features','options')): ?>
<div class="features columns four"><img class="features_icon"src="<?php the_sub_field('feature_icon'); ?>" />
<h2><?php the_sub_field('feature_headline'); ?></h2>
<p><?php the_sub_field('feature_text'); ?></p>
</div>
<?php endwhile; ?>

<div class="hr">
<hr/>
</div>

<?php while(the_repeater_field('features_bottom_row','options')): ?>
<div class="columns four"><img class="features_icon" src="<?php the_sub_field('feature__icon_row_2'); ?>" />
<h2><?php the_sub_field('feature_headline_row_2'); ?></h2>
<p><?php the_sub_field('feature_text_row_2'); ?></p>
</div>
<?php endwhile; ?>



</div>



	</div>
</div>
<?php get_footer(); ?>