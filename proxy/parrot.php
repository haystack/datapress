<?php
require_once('../../../../wp-load.php');
require_once('web-tools.php');

function is_parrotable($url)
{
    global $wpdb;
    $table = WpExhibitConfig::table_name(WpExhibitConfig::$PARROTABLE_URLS_TABLE_KEY);
    $testurl = $wpdb->get_var($wpdb->prepare("SELECT url FROM $table where url = %s", $url));
    $testurl = stripslashes($testurl);
    return $url == $testurl;
}

$url = urldecode($_GET['url']);
if (is_parrotable($url)) {
    $contents = WebTools::do_get_request($url);
    //$contents = file_get_contents($url);
    echo "$contents";
} else {
    echo "URL not parrotable";
}
?>
