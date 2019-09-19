<?php

require_once 'config.php';

$auth_url = SF_LOGIN_URI
        . "/services/oauth2/authorize?response_type=code&client_id="
        . SF_CLIENT_ID . "&redirect_uri=" . urlencode( SF_REDIRECT_URI );

header('Location: ' . $auth_url);

