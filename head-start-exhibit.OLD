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
    window.database = Exhibit.Database.create(); 
    window.database.loadDataLinks(onDataLoaded); 
});

function onDataLoaded() { 
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
