<?php
require_once('wp-exhibit-config.php');
require('wp-exhibit-geocoder.php');

class SaveExhibitPost {
    function save($ex_post_id) {
        global $wpdb;
        
	    $ex_exhibit_id = $_POST['exhibitid'];
	    if ($ex_exhibit_id != null) {
            // See if the association already exists
            $table = WpExhibitConfig::table_name(WpExhibitConfig::$EXHIBITS_ASSOC_TABLE_KEY);
    		
            $assoc = $wpdb->get_row("SELECT * FROM $table WHERE postid = $ex_post_id ;", ARRAY_A);
            if ($assoc === false) {
            }
            else if (($assoc === 0) || (! $assoc)) {
                // There wasn't an association. Add a new one
                $assoc = $wpdb->query("INSERT INTO $table (postid, exhibitid) VALUES ($ex_post_id, $ex_exhibit_id) ;", ARRAY_A);                
            }
            else {
                // There was an association. Upate it
                $assoc = $wpdb->query("UPDATE $table SET postid = $ex_post_id, exhibitid = $ex_exhibit_id WHERE postid = $ex_post_id ;", ARRAY_A);
            }
            
            
            // $ex_exhibit = new WpPostExhibit();
            // $ex_success = DbMethods::loadFromDatabase($ex_exhibit, $ex_exhibit_id);
            // if ($ex_success == true) {
            //              // Create a new one
            //              $ex_exhibit->set('postid', $ex_post_id);
            // }
            //             $ex_exhibit->save();
        }
    }
}
?>
