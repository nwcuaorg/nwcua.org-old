<?php
/**
 * The template for displaying 404 pages (Not Found)
 */

get_header(); 

?>

	<div id="primary" class="content-area">
		<div id="content" class="site-content content-narrow" role="main">

			<div class="page-content">
				<h1 class="page-title">Page Not Found</h1>
				<p>It looks like nothing was found at this location. Maybe try a search?</p>

				<form role="search" method="get" id="searchform" class="searchform" action="/" _lpchecked="1">
					<input type="text" value="" name="s" id="s" placeholder="Search">
					<input type="submit" id="searchsubmit" value="Search" class="btn-arrow">
				</form>
				<p>&nbsp;</p>
			</div><!-- .page-content -->

		</div><!-- #content -->
	</div><!-- #primary -->

<?php

get_footer();

?>