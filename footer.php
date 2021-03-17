<?php
/**
 * The template for displaying the footer
 *
 * Contains footer content and the closing of the #main and #page div elements.
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */
?>	
	
		</div>

	</section>
	
	<footer class="footer">
		<div class="wrap">
			<div class="column quarter no-border">
				<h3>Connect With Us</h3>
				<?php print do_shortcode( '[snippet slug="footer-address" /]' ); ?>
				<div class="social">
					<a href="https://twitter.com/nwcua" target="_blank"><img src="<?php bloginfo('template_url'); ?>/img/icon-circle-twitter.png" alt="Visit our Twitter Page" /></a><a href="https://www.facebook.com/nwcua" target="_blank"><img src="<?php bloginfo('template_url'); ?>/img/icon-circle-facebook.png" alt="Visit our Facebook Page" /></a><a href="https://www.linkedin.com/company/northwest-credit-union-association" target="_blank"><img src="<?php bloginfo('template_url'); ?>/img/icon-circle-linkedin.png" alt="Visit our LinkedIn page." /></a><a href="https://www.youtube.com/channel/UCbwLRYw-m8Jcszv-59M6joA" target="_blank"><img src="<?php bloginfo('template_url'); ?>/img/icon-circle-youtube.png" alt="Visit our Youtube Channel" /></a>
				</div>
			</div>
			<div class="column quarter">
				<h3>Links</h3>
				<nav role="navigation">
				<?php wp_nav_menu( array( 
					'theme_location' => 'footer-links', 
					'menu_class' => 'nav-menu' ) 
				); ?>
			</div>
			<div class="column quarter no-border">
				<h3>Resources</h3>
				<nav role="navigation">
				<?php wp_nav_menu( array( 
					'theme_location' => 'footer-resources', 
					'menu_class' => 'nav-menu' ) 
				); ?>
			</div>
			<!--
			<div class="column quarter">
				<h3>Customize
				<br>Your Experience</h3>
				<p>Sign up for our Anthem Newsletter, and other alerts, updates, and marketing messages.</p>
				<p><a href="/assets_site/ajax/subscribe-form.htm" class="btn-arrow lightbox-iframe" style="background-color: #aabb38;">Subscribe</a></p>
			</div>
			-->
		</div>
	</footer><!-- #colophon -->
	<div class='wrapper'>
		<div class='colophon'>
			<p>Copyright &copy; <?php print date( 'Y' ); ?> NWCUA. All Rights Reserved.</p>
		</div>
	</div>
</div><!-- #page -->
<script type="text/javascript">
(function(e,t,o,n,p,r,i){e.visitorGlobalObjectAlias=n;e[e.visitorGlobalObjectAlias]=e[e.visitorGlobalObjectAlias]||function(){(e[e.visitorGlobalObjectAlias].q=e[e.visitorGlobalObjectAlias].q||[]).push(arguments)};e[e.visitorGlobalObjectAlias].l=(new Date).getTime();r=t.createElement("script");r.src=o;r.async=true;i=t.getElementsByTagName("script")[0];i.parentNode.insertBefore(r,i)})(window,document,"https://diffuser-cdn.app-us1.com/diffuser/diffuser.js","vgo");
vgo('setAccount', '252687469');
vgo('setTrackByDefault', true);
vgo('process');
</script>
<?php wp_footer(); ?>
</body>
</html>