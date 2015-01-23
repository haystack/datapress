<?php
if (!$guessurl = site_url())
	$guessurl = wp_guess_url();
$baseuri = $guessurl;
$exhibituri = $baseuri . '/wp-content/plugins/datapress';
?>

<table>
	<tr>
		<td>URL</td>
		<td><input id="exhibit-datasource-link-uri" type="text" size="30" />&nbsp; &nbsp; [ <a href="http://projects.csail.mit.edu/datapress/help/importing-data/" target="_blank">help with data importing</a> ]</td>
	</tr>
	<!--<tr>
		<td>Type</td>
		<td><p>
		    <select id="exhibit-datasource-link-kind">
		        <option  value="exhibit">Exhibit</option>
		        <option value="google-spreadsheet">Google Spreadsheet</option>
		        <option value="application/json">Exhibit-Style JSON</option>
		    </select>
		    <a href="#" onclick="popup('http://projects.csail.mit.edu/datapress/contacthelp/importing-data/importing-data/'); return false;"><img align="middle" src="<?php echo $exhibituri ?>/images/help.png" width="20" height="20" /></a>
		</p></td>
	</tr>-->
	<tr>
    	<td>Name</td>
		<td><input id="exhibit-datasource-link-sourcename" type="text" size="30" /></td>
	</tr>
</table>
<p align="right">
<!-- <a id="#upload_button" href="#" class="addlink">Upload File</a> -->
<a href="#" class="addlink" onclick="submit_data_link(); return false">Add Data Link</a></p>

<script type="text/JavaScript">

    jQuery(document).ready(function() {
        jQuery('#upload_button').click(function() {
         formfield = jQuery('#exhibit-datasource-link-uri').attr('name');
         tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
         return false;
        });

        window.send_to_editor = function(html) {
         imgurl = jQuery('img',html).attr('src');
         jQuery('#exhibit-datasource-link-uri').val(imgurl);
         tb_remove();
        }
   });

  function kind_for(kind) {
  	if (kind == "google-spreadsheet") {
		return "Google Spreadsheet";
	}
	if (kind == "application/json") {
		return "JSON File";
	}
	return "Unknown Type";
  }

  function add_datasource_link(kind, uri, sourcename, where) {
		var remove_id = addExhibitElementLink(
			"data-source-list",
			kind_for(kind) + ": " + sourcename + " (<a href='" + uri + "'>view data</a>)",
			'data', 
			{
				kind: kind, 
				uri: uri,
				sourcename: sourcename,
				data_location: where
		    },
            {
                editable: true,
                tabid: "exhibit-datasource-link"
            });
		ex_add_head_link(uri, kind, remove_id);
		$.post("<?php bloginfo('wpurl'); ?>/wp-admin/admin-ajax.php",
		       { action: "insert_parrotable_url",
		         url: encodeURIComponent(uri) },
               function(data){
                 setTimeout("ex_load_links()", 1000);
               });
  }

  function submit_data_link() {

	var uri = jQuery('#exhibit-datasource-link-uri').val();

	var kind = guess_type(uri);
	//var kind = jQuery('#exhibit-datasource-link-kind').val();
    var sourcename = jQuery('#exhibit-datasource-link-sourcename').val();
        
	jQuery(function() {
		jQuery.getJSON("<?php bloginfo('wpurl'); ?>/wp-admin/admin-ajax.php?action=import_datafiles&url=" + encodeURIComponent(uri) + "&name=" + encodeURIComponent(sourcename) + "&type=" + encodeURIComponent(kind), function(json) {
            // Process Debug information
			if (json.debugging) {
				for (var i=0; i<json.debugging.length; i++) {;
					console.log(json.debugging[i]);
				}
			}

            // Process Errors
            if (json.errors) {
                for (var i=0; i<json.errors.length; i++) {
                    alert("Error: " + json.errors[i]);
                }                
            }
            
            // Process Warnings
            if (json.warnings) {
                for (var i=0; i<json.warnings.length; i++) {
                    alert("Warning: " + json.warnings[i]);
                }                
            }

            // Process Links
			if (json.links) {
    			for(var i=0; i<json.links.length; i++) {
    				var link_kind = json.links[i].kind;
    				var link_uri = json.links[i].href;
    				var link_sourcename = json.links[i].alt;
    				add_datasource_link(link_kind, link_uri, link_sourcename, 'remote');					
    			}
			}
		});	
	});
		
  }

</script>
