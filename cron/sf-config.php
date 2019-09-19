<?php

// start a session
session_start();


// set up some salesforce parameters
define("SF_CLIENT_ID", "3MVG9sLbBxQYwWqsFCnCBGsG1ESl_DCwk7jzK04KzgV67_HqGw1bqA6ISmQaado6XIFZBnLnf1PXEBKGnRiJW");
define("SF_CLIENT_SECRET", "888A32DECF4776D2CA7241DF8E220B7A53D19B0EB68C6F3178211D734EBB3DE6");
define("SF_REDIRECT_URI", "https://staging.nwcua.org/wp-content/themes/nwcua/cron/sf-token.php");
define("SF_LOGIN_URI", "https://test.salesforce.com");


// include database functionality
require( 'db.php' );
