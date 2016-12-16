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
				<p><a href="tel:1-900-995-9064">800.995.9064</a> <span>Phone</span><br /> 877.928.6397 <span>Fax</span></p>
				<p><strong>Idaho Office:</strong><br>2770 S. Vista Ave<br>Boise, ID 83705</p>
				<p><strong>Oregon Office:</strong><br /> 13221 SW 68<sup>th</sup> Pkwy, <br />Suite 400<br />Tigard, OR 97223</p>
				<p><strong>Washington Office:</strong><br /><a class="lightbox-iframe" href="https://www.google.com/maps?f=q&amp;source=s_q&amp;hl=en&amp;geocode=&amp;q=18000+International+Blvd.+Ste.+350+SeaTac,+WA+98188&amp;aq=&amp;sll=45.460137,-122.794095&amp;sspn=0.009166,0.023378&amp;ie=UTF8&amp;hq=&amp;hnear=18000+International+Blvd+%23350,+SeaTac,+Washington+98188&amp;t=m&amp;z=14&amp;iwloc=A&amp;output=embed">18000 International Blvd,<br />Suite 350<br /> SeaTac, WA 98188</a></p>
				<div class="social">
					<a href="https://twitter.com/nwcua" target="_blank"><img src="<?php bloginfo('template_url'); ?>/img/icon-circle-twitter.png" alt="" /></a><a href="https://www.facebook.com/nwcua" target="_blank"><img src="<?php bloginfo('template_url'); ?>/img/icon-circle-facebook.png" alt="" /></a><a href="https://plus.google.com/112861856693910313753" target="_blank"><img src="<?php bloginfo('template_url'); ?>/img/icon-circle-google.png" alt="" /></a><a href="http://www.linkedin.com/company/northwest-credit-union-association" target="_blank"><img src="<?php bloginfo('template_url'); ?>/img/icon-circle-linkedin.png" alt="" /></a><a href="http://www.youtube.com/user/theNWCUA" target="_blank"><img src="<?php bloginfo('template_url'); ?>/img/icon-circle-youtube.png" alt="" /></a>
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
			<div class="column quarter">
				<h3>SIGN UP FOR Emails</h3>
				<p>Sign up for our Anthem Newsletter, and other alerts, updates, and marketing messages.</p>
				<div class="subscribe-form"><input id="subscribe-email" type="text" /> <button value="Subscribe"> </button></div>
			</div>
		</div>
	</footer><!-- #colophon -->

</div><!-- #page -->

<?php wp_footer(); ?>
</body>
</html>