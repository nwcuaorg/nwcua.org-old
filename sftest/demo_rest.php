<?php
session_start();

function show_accounts($instance_url, $access_token) {
    $query = "SELECT Name, Active__c, Associo_Product_ID__c, CreatedById, Description__c, EarlyDate__c, EventEnd__c, EventStart__c, Event_Type__c, Family__c, GLAccount__c, LastModifiedById, LateDate__c, LongName__c, OwnerId, ParentProduct__c, Price__c, ProductFamily__c, ShowOnWebsite__c from Product__c LIMIT 100";
    $url = "$instance_url/services/data/v20.0/query?q=" . urlencode($query);

    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_HEADER, false);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER,
            array("Authorization: OAuth $access_token"));

    $json_response = curl_exec($curl);
    curl_close($curl);

    $response = json_decode($json_response, true);

    $total_size = $response['totalSize'];

    echo "$total_size record(s) returned<br/><br/>";
    foreach ((array) $response['records'] as $record) {
        print_r( $record );
        print "<br/>";
    }
    echo "<br/>";
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>REST/OAuth Example</title>
    </head>
    <body>
        <tt>
            <?php
            $access_token = $_SESSION['access_token'];
            $instance_url = $_SESSION['instance_url'];

            if (!isset($access_token) || $access_token == "") {
                die("Error - access token missing from session!");
            }

            if (!isset($instance_url) || $instance_url == "") {
                die("Error - instance URL missing from session!");
            }

            show_accounts($instance_url, $access_token);
            ?>
        </tt>
    </body>
</html>