<?php
ob_start();
$root = dirname(dirname(dirname(dirname(dirname(__FILE__)))));
  if (file_exists($root.'/wp-load.php')) {
      // WP 2.6
      require_once($root.'/wp-load.php');
  } else {
      // Before 2.6
      require_once($root.'/wp-config.php');
  }
ob_end_clean(); //Ensure we don't have output from other plugins.

header("Content-type: text/javascript");

if (!$guessurl = site_url())
	$guessurl = wp_guess_url();
$baseuri = $guessurl;
$exhibituri = $baseuri . '/wp-content/plugins/datapress';

print <<<EOF

function ex_add_head_link(uri, kind, remove_id) {
	var link = "";
	if (kind == "google-spreadsheet") {
		var link = SimileAjax.jQuery('<link id = "' + remove_id + '" rel="exhibit/data" type="application/jsonp" href="' + uri + '" ex:converter="googleSpreadsheets" />');
	}
	else if (kind == "application/json") {
		var link = SimileAjax.jQuery('<link id = "' + remove_id + '" rel="exhibit/data" type="application/json" href="$exhibituri/proxy/parrot.php?url=' + encodeURIComponent(uri) + '" />');
	}
	SimileAjax.jQuery('head').append(link);
}

function addExhibitElementLink(listId, caption, prefix, fields, editinfo, alreadyBase64Encoded) {
	var next_id = -1;
	SimileAjax.jQuery('#' + listId + ' > li').each(function(i, val) {
	    id_str = SimileAjax.jQuery(val).attr('id');
	    id = id_str.substring(id_str.lastIndexOf("_")+1, id_str.length);
	    if (id > next_id) {
	        next_id = id;
	    }
	});
	next_id++;
	var liid = listId + "_" + next_id;
	var opStr = "";
	opStr = opStr + "<li class=\"ui-state-default\" id='" + liid + "'><span style=\"float:left;\" class=\"ui-icon ui-icon-arrowthick-2-n-s\"></span>" + caption + " ";
	SimileAjax.jQuery.each(fields, function(key, value) {
        var field_name = prefix + "_" + next_id + "_" + key;
        if (alreadyBase64Encoded) {
    		var field = '<input type="hidden" name="' + field_name + '" value="' + value + '" />';
	    	opStr = opStr + field;	            	            
	    }
	    else {
        	var field = '<input type="hidden" name="' + field_name + '" value="' + jQuery.base64Encode(value) + '" />';
    		opStr = opStr + field;	            
        }
	});
	opStr = opStr + "[ <a href='#' onclick='removeExhibitElementLink(\"" + liid + "\"); return false;'>remove</a> ]";
	if ((editinfo != undefined) && (editinfo.editable)) {
		opStr = opStr + "[ <a href='#' onclick='editExhibitElementLink(\"" + editinfo.tabid + "\", \"" + liid + "\"); return false;'>edit</a> ]";
    }
	opStr = opStr + "</li><script>jQuery(\"#" + liid + "\").parent().sortable();</script>";
	SimileAjax.jQuery('#' + listId).append(opStr);
	return removeID(liid);
}

function popup(url) {
	window.open(url);
}

function removeID(liid) {
    return liid + "_remove";
}

function removeExhibitElementLink(liid) {
    SimileAjax.jQuery("#" + liid).remove();
    SimileAjax.jQuery("#" + removeID(liid)).remove();
    if (liid.indexOf('data-source-list') != -1) {
        ex_load_links();
    }
}

function editExhibitElementLink(tabid, liid) {
    var inputs = new Array();
    jQuery("#" + liid + " input[type='hidden']").each(function(i, val) {
        var inputname = jQuery(val).attr('name');
        var oldkey = inputname.substring(inputname.lastIndexOf("_")+1, inputname.length);
        inputs[oldkey] = jQuery(val).attr('value');
    });

    jQuery("#" + tabid + " :input")
      .not("[multiple]").each(function(i, val) {
          var keyid = jQuery(val).attr('id');
          var key = keyid.substring(keyid.lastIndexOf("-")+1, keyid.length);
          var decodedkey = inputs[key];
          jQuery(val).val(jQuery.base64Decode(decodedkey));
        })
      .end()
      .filter("[multiple]").each(function(i, val) {
          var keyid = jQuery(val).attr('id');
          var key = keyid.substring(keyid.lastIndexOf("-")+1, keyid.length);

          var selected = jQuery.base64Decode(inputs[key]).replace(/\./g, '').split(',');
          jQuery(val).find("option").each(function(j, option) {
              if (jQuery.inArray(jQuery(option).attr("value"), selected) != -1) {
                  jQuery(option).attr("selected", "selected");
              } else {
                  jQuery(option).removeAttr("selected");
              }
          });
      })
      .end();
    tab_parent_id = tabid.substr(0, tabid.lastIndexOf("-")) + "-tabs";
    jQuery("#" + tab_parent_id).tabs('select', "#" + tabid);
    removeExhibitElementLink(liid);
}

function appendToPost(myValue) {
	window.tinyMCE.execInstanceCommand("content", "mceInsertContent",true,myValue);
}

function appendToLens(myValue) {
    jQuery('#exhibit-lenses-edit-html').tinymce().execCommand('mceInsertContent',false,myValue);
	// var win = window.dialogArguments || opener || parent || top;
    // jQuery('#exhibit-lenses-edit-html').val(jQuery('#exhibit-lenses-edit-html').val() + myValue);
	// win.tinyMCE.execInstanceCommand("exhibit-lenses-edit-html", "mceInsertContent",true,myValue);
}

function ex_data_types_changed(e, arr) {	
	var types = db._types;
	var props = db._properties;
	var type_choice = "<option selected value=''> - </option>";
	var prop_choice = "<option selected value=''> - </option>";

	for (var key in types) {
		if (key != "Item") {
			var id = types[key].getID();
			var label = types[key].getLabel();		
			type_choice = type_choice + "<option value='" + id + "'>" + label + "</option>";			
		}
	}	
	for (var key in props) {
		prop_choice = prop_choice + "<option value='" + key + "'>" + key + "</option>";
	}	
	SimileAjax.jQuery('.alltypebox').html(type_choice);		
	SimileAjax.jQuery('.allpropbox').html(prop_choice);
}

function ex_load_links() {
    db = Exhibit.Database.create();
	db.loadDataLinks(ex_data_types_changed);		
}

EOF
?>
