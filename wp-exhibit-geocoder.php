<?php
require_once('wp-exhibit-config.php');

class WpExhibitGeocoder {
    // Perform the geocoding for a batch of items. Returns false if
    // $datum_ids and $addresses are a different size.
    static function batch_add($exhibit_id, $address_field, $datum_ids, $addresses) {
        /*
         * Delete everything that isn't one of datum_ids and is associated with exhibit_id,address_field
         */
    	$table = WpExhibitConfig::table_name(WpExhibitConfig::$GEOCODE_TABLE_KEY);
        global $wpdb;

        // Prepare the list of things they're asking about
        $list_of_ids = array();
        for ($i =0; $i<count($datum_ids); $i++) {
            $list_of_ids[$i] = $wpdb->prepare("%s",$datum_ids[$i]);
        }
        $theList = join(", ", $list_of_ids);

        $sql = "DELETE FROM $table WHERE exhibit_id = %d AND addressField = %s AND datum_id NOT IN ($theList);";
        $query = $wpdb->prepare($sql, $exhibit_id, $address_field);
	    $row = $wpdb->query($query);
    	if(count($datum_ids) == count($addresses)) {
    		for($i = 0; $i < count($datum_ids); $i++) {
    			WpExhibitGeocoder::lookup($exhibit_id, $address_field, $datum_ids[$i], $addresses[$i]);		
    		}
    		return true;
    	} else {
    		return false;
    	}
    }
    
    // Returns true if the Exhibit contains any geocoded data, false otherwise. 
    static function doesExhibitContainGeocodedData($exhibit_id) {
	global $wpdb;
	$table = WpExhibitConfig::table_name(WpExhibitConfig::$GEOCODE_TABLE_KEY);
	$query = "SELECT count(*) from $table WHERE exhibit_id = %d";
	$query = $wpdb->prepare($query, $exhibit_id);
	$row = $wpdb->get_row($query, ARRAY_N);
	if($row != NULL) {
		return $row[0] > 0;	
	}
	return false;
    }
    
    static function getGeocodedFieldsForExhibit($exhibit_id) {
    	global $wpdb;
    	$table = WpExhibitConfig::table_name(WpExhibitConfig::$GEOCODE_TABLE_KEY);
    	$query = "SELECT DISTINCT addressField FROM $table WHERE exhibit_id = %d";
    	$query = $wpdb->prepare($query, $exhibit_id);
    	return $wpdb->get_col($query);
    }
    
    //look up the lat and lng for a given exhibit, data set, and address.
    //If not found, geocode and store in table
    static function lookup($exhibit_id, $address_field, $datum_id, $address) {
	    global $wpdb;
	    $table = WpExhibitConfig::table_name(WpExhibitConfig::$GEOCODE_TABLE_KEY);
	    $sql = "SELECT lat, lng FROM $table WHERE exhibit_id = %d AND addressField = %s AND datum_id = %s AND address = %s;";
	    $query = $wpdb->prepare($sql, $exhibit_id, $address_field, $datum_id, $address);
	    $row = $wpdb->get_row($query, ARRAY_A);
	    if($row != NULL) {
		    return array($row['lat'], $row['lng']);
	    }
	    else {
		try {
		    // Delete any old address for <ExhibitID, AddressField, DatumID, *>
		    $deleteSQL = "DELETE FROM $table WHERE exhibit_id = %d AND addressField = %s AND datum_id = %s;";
		    $deleteQuery = $wpdb->prepare($deleteSQL, $exhibit_id, $address_field, $datum_id);
		    $wpdb->query($deleteQuery);
		    
			$geo_results = json_decode(WpExhibitGeocoder::geocode($address), true);
			$latlng = $geo_results['results'][0]['geometry']['location'];
			if($latlng['lat'] == 0 && $latlng['lng'] == 0) {
				return false;
			}
			$query = "INSERT INTO $table(lat, lng, exhibit_id, addressField, datum_id, address) VALUES(%f, %f, %d, %s, %s, %s)";
			$query = $wpdb->prepare($query, $latlng['lat'], $latlng['lng'], $exhibit_id, $address_field, $datum_id, $address);
			$wpdb->query($query);
		} catch(Exception $e) {
			return null;
		}
		return $latlng;
	    }
	}
    //Look up single address using Google Geocoding API
    //returns JSON. For full contents of the JSON, check Google's documentation.
    static function geocode($address) {
	    $address = urlencode($address);
	    $request = file_get_contents("http://maps.googleapis.com/maps/api/geocode/json?address=$address&sensor=false");
	    return $request;
    }

    
    // Pring out a data file for an exhibit
    static function json_for($exhibit_id) {
	    global $wpdb;
	    $table = WpExhibitConfig::table_name(WpExhibitConfig::$GEOCODE_TABLE_KEY);
	    $query = "SELECT lat, lng, addressField, datum_id FROM $table WHERE exhibit_id = %d";
	    $query = $wpdb->prepare($query, $exhibit_id);
	    $data = $wpdb->get_results($query, ARRAY_A);
	    $latlngs = array();
	    foreach ($data as $row) {
		$latlngs['items'][] = array('id' => $row['datum_id'], $row['addressField'] . '_generatedLatLng' => $row['lat'] . ',' . $row['lng']);
	    }
	    $latlng_json = json_encode($latlngs);

	    return $latlng_json;
    }

}


?>
