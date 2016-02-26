<?php
	if (!$guessurl = site_url())
    	$guessurl = wp_guess_url();
    $baseuri = $guessurl;
    $exhibituri = $baseuri . '/wp-content/plugins/datapress';
require_once('wp-exhibit-geocoder.php');
global $exhibits_to_show;

if (isset($exhibits_to_show) && (count($exhibits_to_show) > 0)) {
    foreach ($exhibits_to_show as $exhibit_to_show) {
        foreach($exhibit_to_show->get('datasources') as $datasource) {
            echo($datasource->htmlContent() . "\n");
        }
    // This isn't necessary becasue we're dynamically adding it now
    // if(WpExhibitGeocoder::doesExhibitContainGeocodedData($exhibit_to_show->get('id'))) {
    //         echo("<link href='$exhibituri/wp-exhibit-geocode.php?exhibit-id=" . $exhibit_to_show->get('id') . "' type='application/json' rel='exhibit/data' alt='geocoded_data' />\n");
    // }
}
}
?>
