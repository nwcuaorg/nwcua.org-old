<?php 

/*
Template Name: Microsite
*/

global $is_anthem; 

?><!DOCTYPE html>
<!--[if IE 7]><html class="ie ie7" <?php language_attributes(); ?>><![endif]-->
<!--[if IE 8]><html class="ie ie8" <?php language_attributes(); ?>><![endif]-->
<!--[if !(IE 7) | !(IE 8)  ]><!--><html <?php language_attributes(); ?>><!--<![endif]-->
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta name="viewport" content="width=device-width,initial-scale=1" />

<title><?php wp_title( '|', true, 'right' ); ?> Northwest Credit Union Association</title>

<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />

<!--[if lt IE 9]>
<script src="<?php echo get_template_directory_uri(); ?>/js/html5.js" type="text/javascript"></script>
<![endif]-->

<?php wp_head(); ?>
<link href="<?php bloginfo( "template_url" ) ?>/css/src/micro-unprefixed.css?v=1" rel="stylesheet" type="text/css">

</head>
<body <?php body_class(); ?>>

<div class="microsite-container">
	<header>

		<div class="wrap microsite-header">
			<img src="<?php bloginfo('template_url') ?>/img/micro-header.jpg" />
		</div>

	</header>

	<section class="content">
		<div class="grid-row">
			<div class="two-third">
				<h3>The Credit Union Difference </h3>
				<p><img src="<?php bloginfo('template_url') ?>/img/micro-book.png" class="imageright" />An <span class="text-orange"><strong>independent analysis by economists at ECONorthwest</strong></span> finds Idaho’s cooperative, not-for-profit credit unions delivered $90 million in direct financial benefits to their nearly one million members last year. The analysis measured jobs, economic output, and income and determined Idaho credit unions drove an overall $638 million impact on the state’s economy.</p>
				<p><a href="https://nwcua.org/wp-content/uploads/2018/01/2017-IDAHO-Executive-Summary.pdf" class="text-orange"><strong>Learn more &raquo;</strong></a></p>
			</div>
			<div class="third bg-seafoam">
				<img src="<?php print bloginfo('template_url'); ?>/img/micro-cu-difference.png">
			</div>
		</div>
		<div class="grid-row bg-grey-light-light-light">
			<div class="two-third">
				<h3>The Cooperative Choice</h3>
				<p>Nearly one million Idaho consumers belong to a credit union – that’s 55 percent of the Gem State’s population. </p>
				<p>Not-for-profit, cooperative credit unions reinvest in their Idaho members on Main Street. They don’t have stockholders on Wall Street. All earnings in excess of operating expenses and required reserves are returned to members. That’s why fees are often lower, loan rates are usually better, and why members may earn more on their savings. In Idaho, credit unions provided an average direct benefit to each member household of at least $204.* </p>
			</div>
			<div class="third bg-grey-dark">
				<img src="<?php print bloginfo('template_url'); ?>/img/micro-cooperative-choice.png">
			</div>
		</div>
		<div class="grid-row">
			<div class="two-third">
				<h3>The Cooperative Principles</h3>
				<div class="half">
					<ul>
						<li>Voluntary and Open Membership</li>
						<li>Democratic Member Control</li>
						<li>Member Economic Participation</li>
						<li>Autonomy and Independence</li>
					</ul>
				</div>
				<div class="half">
					<ul>
						<li>Education, Training and Information</li>
						<li>Cooperation among Co-operatives</li>
						<li>Concern for Community</li>
					</ul>
				</div>
				<p class="group">Not-for-profit, cooperative credit unions exist for one reason: to meet their member-owners' financial services needs.</p>
			</div>
			<div class="third bg-lime">
				<img src="<?php print bloginfo('template_url'); ?>/img/micro-nine-billion.png">
			</div>
		</div>
		<div class="micro-subfooter">
			<h4>The cooperative principles are as relevant as ever, while technology and services available to you are convenient and contemporary.</h4>
		</div>
		<div class="micro-footer bg-orange text-white">
			<a href="http://www.asmarterchoice.org/" target="_blank"><img src="<?php bloginfo('template_url') ?>/img/micro-footer-search.png" class="imageright" /></a>
			<div class="content">
				<h4>Credit Unions are Open to Everyone!</h4>
				<p>Nearly everyone who lives, works, worships, or attends school in Idaho can join a credit union. To find the one that’s ideal for you, visit the <a href="http://www.asmarterchoice.org/" target="_blank">A Smarter Choice</a> website.</p>
			</div>
		</div>
		<div class="colophon">
			<p>* Source: National Credit Union Administration Data, Credit Union National Association analysis, Datatrac research for 12 months ending December 31, 2017).</p>
		</div>
	</section>
		
	<footer class="footer">
		
	</footer><!-- #colophon -->

</div>
<?php wp_footer(); ?>
</body>
</html>