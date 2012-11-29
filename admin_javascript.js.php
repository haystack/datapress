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
header("Content-type: text/javascript");

if (!$guessurl = site_url())
	$guessurl = wp_guess_url();
$baseuri = $guessurl;
$exhibituri = $baseuri . '/wp-content/plugins/datapress';
$imageurl = $exhibituri . '/exhibit.png';

print <<<EOF

function guess_type(input) {
	var siteUrl = location.hostname;
	var jsonRegEx = RegExp("https?:\/\/(w{3}.)?("  + siteUrl + "|.+)\..+\.js(on)?");
	if(input.match(/https?:\/\/.+\.google.com.+/)) {
		return "google-spreadsheet";
	} else if(match = jsonRegEx.exec(input)) {
		return "application/json";
	}
	return "exhibit";
}


function set_post_exhibit(exhibit_id) {
	var datapress_link = jQuery('#load_datapress_config_link');
	datapress_link[0].href = '$baseuri/wp-admin/admin-ajax.php?action=datapress_configurator&exhibitid=' + exhibit_id + '&TB_iframe=true';	
	var exhibit_id_element = jQuery('#exhibitid');
	exhibit_id_element[0].value = exhibit_id;
}

// send html to the post editor
function add_exhibit_token_and_exit() {
	var imagestring = "<img src='$imageurl' alt='Your Exhibit' height='70' width='70'/>";	
	var h = imagestring + " {{Footnotes}}";
	var searchfor = '$imageurl';
	
	if ( typeof tinyMCE != 'undefined' && ( ed = tinyMCE.activeEditor ) && !ed.isHidden() ) {
		ed.focus();
		if (ed.getContent().indexOf(searchfor) == -1) {
			if (tinymce.isIE)
				ed.selection.moveToBookmark(tinymce.EditorManager.activeEditor.windowManager.bookmark);
			ed.execCommand('mceInsertContent', false, h);			
		}
	} else if ( typeof edInsertContent == 'function' ) {
		if ((typeof edCanvas.value != 'undefined') && (edCanvas.value.indexOf(searchfor) == -1)) {
			edInsertContent(edCanvas, h);
		}
	} else {
		if (jQuery(edCanvas).val().indexOf(searchfor) == -1) {
			jQuery( edCanvas ).val( jQuery( edCanvas ).val() + h );			
		}
	}

	tb_remove();
}

// Add dataset marker to the current editing location in the editor
function add_dataset_token_and_exit(dbid) {
    var length = jQuery.fn.contextMenu.displayfuncs ? jQuery.fn.contextMenu.displayfuncs.length : 0;
    var spanopen = "<span class='dp_editdata' dbid='" + dbid + "' displayid='" + length + "'>";
	var h = spanopen + "&nbsp;</span>";
	var searchfor = spanopen + "\\s+</span>";
	
	if ( typeof tinyMCE != 'undefined' && ( ed = tinyMCE.activeEditor ) && !ed.isHidden() ) {
		ed.focus();
		if (ed.getContent().search(searchfor) == -1) {
			if (tinymce.isIE)
				ed.selection.moveToBookmark(tinymce.EditorManager.activeEditor.windowManager.bookmark);
			ed.execCommand('mceInsertContent', false, h);
		}
	} else if ( typeof edInsertContent == 'function' ) {
		if ((typeof edCanvas.value != 'undefined') && (edCanvas.value.search(searchfor) == -1)) {
			edInsertContent(edCanvas, h);
		}
	} else {
		if (jQuery(edCanvas).val().search(searchfor) == -1) {
			jQuery( edCanvas ).val( jQuery( edCanvas ).val() + h );			
		}
	}
	
	tb_remove();

    jQuery(".dp_editdata[dbid=" + dbid + "]").contextMenu('dataEditMenu', {
      bindings: {
        'edit': function(t) {
          jQuery("span#dpEditDataLinkContainer").html("<a href=\"$baseuri/wp-admin/admin-ajax.php?identifier=" + dbid + "&action=template_editor&TB_iframe=true&width=640&height=673\" id=\"dpEditDataLink\" class=\"thickbox\">test</a>");
          tb_init('a#dpEditDataLink')
          jQuery("a#dpEditDataLink").click();
        },
        'delete': function(t) {
            tinyMCE.activeEditor.execCommand('mceRemoveNode', false, null);
        },
      }
    });
}
EOF
?>
