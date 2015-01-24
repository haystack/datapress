<?php
	if (!$guessurl = site_url())
    	$guessurl = wp_guess_url();
    $baseuri = $guessurl;
    $exhibituri = $baseuri . '/wp-content/plugins/datapress';
require_once('wp-exhibit-geocoder.php');
global $exhibits_to_show;
?>

// Start Exhibit Manually

Exhibit.Functions["contains"] = {
    f: function(args) {
        var result = args[0].size > 0;
        var set = args[0].getSet();
        
        args[1].forEachValue(function(v) {
            if (!set.contains(v)) {
                result = false;
                return true;
            }
        });
        return new Exhibit.Expression._Collection([ result ? "true" : "false" ], "boolean");
    }
};

SimileAjax.jQuery(document).ready(function() { 
<?php
$needGeocoding = false;

if (isset($exhibits_to_show) && (count($exhibits_to_show) > 0)) {
    foreach ($exhibits_to_show as $exhibit_to_show) {
	if(WpExhibitGeocoder::doesExhibitContainGeocodedData($exhibit_to_show->get('id'))) {    
        $needGeocoding = true;
	}
 }
}
?>
	
    window.database = Exhibit.Database.create(); 

    // If we don't need geocoding, load data links with onAllDataLoaded callback.
    // IF we do need geocoding, load data links with updateGeocode callback
    <?php if ($needGeocoding) { ?>
        window.database.loadDataLinks(updateGeocode); 
    <?php } else { ?>
        window.database.loadDataLinks(onAllDataLoaded); 
    <?php } ?>
});

function getItemProps(propertyName) {
	var ret = new Array();
	var items = window.database.getAllItems();
	items.visit(function(item) {
		var obj = window.database.getObject(item, propertyName);
		if(obj != null) {
			ret[item] = obj;
		}
        // else {
        //     ret[item] = "";
        // }
	});
	return ret;
}

function updateGeocode() {
    var geoExId = "";
    var geoAddressField = "";
    var geoIds = [];
    var geoAddresses = [];

<?php
   /* Step 0: Figure out how many calls to the geocoder we will make. */
    $callBacks = 0;
    if (isset($exhibits_to_show) && (count($exhibits_to_show) > 0)) {
        foreach ($exhibits_to_show as $exhibit_to_show) {
        $exhibit_id = $exhibit_to_show->get('id');
    	$fields = WpExhibitGeocoder::getGeocodedFieldsForExhibit($exhibit_id);
            foreach ($fields as $field) {
                $callBacks += 1;    
            }
        }
    }
?>
        var callBacks = <?php echo $callBacks ?>;

<?php

    /* Step 1: Figure out what fields we need to geocode. */

    if (isset($exhibits_to_show) && (count($exhibits_to_show) > 0)) {
        foreach ($exhibits_to_show as $exhibit_to_show) {

        $exhibit_id = $exhibit_to_show->get('id');
    	$fields = WpExhibitGeocoder::getGeocodedFieldsForExhibit($exhibit_id);
        
        foreach ($fields as $field) {
            /* Step 2: Build up a big list of all the data (itemID, field, value) for these fields */
?>
            geoExId = "<?php echo $exhibit_id ?>";
            geoAddressField = "<?php echo $field ?>";
            geoIds = Array();
            geoAddresses = Array();
            
            // TOOD: getItemProps
            var itemProps = getItemProps(geoAddressField);
			var i = 0;
			for(key2 in itemProps) {
				geoAddresses[i] = itemProps[key2];
				geoIds[i] = key2;
				i++;
			}

            // Step 3: Call wp-exhibit-geocoder.php with that data
            try {
                var payload = {'exhibitid': geoExId, 'datumids[]': geoIds, 'addresses[]': geoAddresses, 'addressField': geoAddressField};
                console.log(payload);
			    SimileAjax.jQuery.post(
			        <? echo("'$exhibituri/wp-exhibit-geocode.php'"); ?>,
                    payload,
			        function(data) {
                    	SimileAjax. jQuery('head').append("<?php echo("<link href='$exhibituri/wp-exhibit-geocode.php?exhibit-id=$exhibit_id' type='application/json' rel='exhibit/data' alt='geocoded_data' />") ?>");
			            callBacks -= 1;
                        if (callBacks == 0) {
                            window.database = Exhibit.Database.create();                             
                            window.database.loadDataLinks(onAllDataLoaded); 
                        }
			        }
			    );
			} catch(e) {
				console.log(e);
			} // Try catch
<?php
        } // Foreach field
     } // For each exhibit
    } // If exhibits
?>
};

function onAllDataLoaded() { 
    window.exhibit = Exhibit.create();
    createCollections();
    window.exhibit.configureFromDOM();
};

function createCollections() {
    var auto_union = new Exhibit.Collection.create2("auto_union", {}, window.exhibit.getUIContext()); 
    var collection_all = new Exhibit.Collection("collection_all", window.database);

	window.exhibit.setCollection("auto_union", auto_union);
    window.exhibit.setCollection("collection_all", collection_all); 

	/*
	 * For each type of item, create a collection
	 */
    var types = window.database._types;
    var collections = {};
    
    for (var key in types) {
        // if (key != "Item") {
    	    var id = types[key].getID();
			collections[id] = Exhibit.Collection.create2( 
			    'collection_'+id, 
			    {  
			        baseCollectionID: 'auto_union', 
			        expression:"filter(value, contains(.type, '" + id + "'))" 
			    }, 
			    window.exhibit.getUIContext() 
			);
			window.exhibit.setCollection('collection_' + id, collections[id]);       
        // }
    }	

    collection_all._update = function() {
        var dunnit = false;
        for (var key in collections) {
            if (!dunnit) {
            	this._items = new Exhibit.Set(collections[key].getRestrictedItems());
                dunnit = true;
            }
            else {
                this._items.addSet(collections[key].getRestrictedItems());
            }
        }
    	this._onRootItemsChanged();
    };
    
    collection_all._listener = { onItemsChanged: function() { collection_all._update(); } };

    for (var key in types) {
        collections[id].addListener(collection_all._listener);       
    }

    collection_all._update(); 
};
