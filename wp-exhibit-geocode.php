<?php
ob_start();
$root = dirname(dirname(dirname(dirname(__FILE__))));
  if (file_exists($root.'/wp-load.php')) {
      // WP 2.6
      require_once($root.'/wp-load.php');
  } else {
      // Before 2.6
      require_once($root.'/wp-config.php');
  }
ob_end_clean(); //Ensure we don't have output from other plugins.

require_once('wp-exhibit-geocoder.php');
if(isset($_GET['exhibit-id'])) {
	header('Content-type: application/json');
	echo WpExhibitGeocoder::json_for($_GET['exhibit-id']);
} else if (isset($_POST['exhibitid']) and isset($_POST['datumids']) and isset($_POST['addresses'])) {
	WpExhibitGeocoder::batch_add($_POST['exhibitid'], $_POST['addressField'], $_POST['datumids'], $_POST['addresses']);	
} else {
}

?>
