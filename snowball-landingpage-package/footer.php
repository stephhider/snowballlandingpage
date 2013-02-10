		</div><!-- End Main row -->
		
		<footer id="content-info" role="contentinfo">
			<div class="foot row">
				<?php dynamic_sidebar("Footer"); ?>
			</div>
			
			<div class="row">
			<div class="social">
			
			<p>
			
			<a href=" <?php the_field('twitter_url','options'); ?>" class="sb circle no-shadow no-border blue twitter">Twitter</a>
			
			<a href=" <?php the_field('facebook_url','options'); ?>" class="sb circle no-shadow no-border blue facebook">Facebook</a>
			
			<a href=" <?php the_field('linkedin_url','options'); ?>" class="sb circle no-shadow no-border blue linkedin">Linkedin</a>
			
			
			<a href=" <?php the_field('googleplus_url','options'); ?>" class="sb circle no-shadow no-border blue googleplus">Google</a>
			
			<a href=" <?php the_field('pinterest_url','options'); ?>" class="sb circle no-shadow no-border blue pinterest">Pinterest</a>
			
			</p>
			</div>
			</div>
			
			<div class="row">
			<hr class="footerhr" />
			</div>
			
						<div class="row">
				<div class="twelve columns">
				<?php wp_nav_menu(array('theme_location' => 'utility_navigation', 'container' => false, 'menu_class' => 'footer-nav')); ?>
				<br />
					&copy; 2008-<?php echo date('Y'); ?> All rights reserved.
				 <?php bloginfo('name');  ?>
					 Theme Design by <a href="http://themegoodness.com" title="Themegoodness">Themegoodness</a>.
				</div>
				
			</div>
		</footer>
			
	</div><!-- Container End -->
		</div><!-- wrap -->
	<!-- Prompt IE 6 users to install Chrome Frame. Remove this if you want to support IE 6.
	     chromium.org/developers/how-tos/chrome-frame-getting-started -->
	<!--[if lt IE 7]>
		<script defer src="//ajax.googleapis.com/ajax/libs/chrome-frame/1.0.3/CFInstall.min.js"></script>
		<script defer>window.attachEvent('onload',function(){CFInstall.check({mode:'overlay'})})</script>
	<![endif]-->
	<script type="text/javascript"><?php the_field('google_analytics_code','options'); ?></script>
	<?php wp_footer(); ?>
</body>
</html>