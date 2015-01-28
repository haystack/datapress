<?php
if (!$guessurl = site_url())
    $guessurl = wp_guess_url();
$baseuri = $guessurl;
$exhibituri = $baseuri . '/wp-content/plugins/datapress';
?>

<table>
    <tr>
        <td>URL</td>
        <td><input id="exhibit-datasource-link-uri" type="text" size="30"/>&nbsp; &nbsp; [ <a
                href="http://projects.csail.mit.edu/datapress/help/importing-data/" target="_blank">help with data
                importing</a> ]
        </td>
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
        <td><input id="exhibit-datasource-link-sourcename" type="text" size="30"/></td>
    </tr>
</table>
<p align="right">
    <!-- <a id="#upload_button" href="#" class="addlink">Upload File</a> -->
    <a href="#" class="addlink" onclick="submit_data_link(); return false">Add Data Link</a>
</p>
<div id="exhibit-worksheet-selection" style="position: absolute; top: 0px; left: 0px; width: 100%; height: 100%; display: inline; background-color: rgba(0, 0, 0, 0.6); display:none;">
    <div style="border:2px solid #99CC66; border-radius:4px; position:absolute; top:0; left:0; right:0; bottom:0; margin:auto; width: 50%; height:5em; padding:10px; background-color:#FFFFFF;">
        Your Spreadsheet contained multiple sheets. Please select which sheet you would like to use:
        <br>
        <select id="exhibit-worksheet-selection-dropdown" data-kind="google-spreadsheet" data-sourcename="test" data-key="1mbYtgzvP0Zv7Glc7pBjV3FBj2EIflPY37URRsfSikVQ" style="display:inline-block">
        </select>
        <div style="display:inline-block; position:absolute; padding:4px; border:1px solid black; background-color:#f5f5f5; font-weight:bold; margin:.1em 0 0 1em" onclick="select_sheet()">
            Select this sheet
        </div>
    </div>
</div>


<script type="text/JavaScript">

    jQuery(document).ready(function () {
        jQuery('#upload_button').click(function () {
            formfield = jQuery('#exhibit-datasource-link-uri').attr('name');
            tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
            return false;
        });

        window.send_to_editor = function (html) {
            imgurl = jQuery('img', html).attr('src');
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
            kind_for(kind) + ": " + sourcename + " (<a target='_blank' href='" + uri + "'>view data</a>)",
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
            {
                action: "insert_parrotable_url",
                url: encodeURIComponent(uri)
            },
            function (data) {
                setTimeout("ex_load_links()", 1000);
            });
    }

    function submit_data_link() {

        var uri = jQuery('#exhibit-datasource-link-uri').val();

        var kind = guess_type(uri);
        //var kind = jQuery('#exhibit-datasource-link-kind').val();
        var sourcename = jQuery('#exhibit-datasource-link-sourcename').val();

        jQuery(function () {
            jQuery.getJSON("<?php bloginfo('wpurl'); ?>/wp-admin/admin-ajax.php?action=import_datafiles&url=" + encodeURIComponent(uri) + "&name=" + encodeURIComponent(sourcename) + "&type=" + encodeURIComponent(kind), function (json) {
                // Process Errors
                if (json.errors) {
                    for (var i = 0; i < json.errors.length; i++) {
                        alert("Error: " + json.errors[i]);
                    }
                }

                // Process Warnings
                if (json.warnings) {
                    for (var i = 0; i < json.warnings.length; i++) {
                        alert("Warning: " + json.warnings[i]);
                    }
                }

                // Process Links
                if (json.links) {
                    for (var i = 0; i < json.links.length; i++) {
                        var link_kind = json.links[i].kind;
                        var link_sourcename = json.links[i].alt;
                        if (typeof json.links[i].multisheet != "undefined") {
                            var link_uri = sheet_dropdown(json.links[i].sheets_xml, json.links[i].key, link_kind, link_sourcename);
                        } else {
                            var link_uri = json.links[i].href;
                            add_datasource_link(link_kind, link_uri, link_sourcename, 'remote');
                        }

                    }
                }
            });
        });

    }

    function sheet_dropdown(xml, key, link_kind, link_sourcename) {
        // scrape page for sheet ids and sheet titles.
        var sheet_match = new RegExp(/([^<>\/]+)<\/id>/g);
        var title_match = new RegExp(/<title[^>]+>([^<]+)<\/title>/g);
        var sheets = [];
        var titles = [];
        var match_s;
        var match_t;
        while((match_s = sheet_match.exec(xml)) && (match_t = title_match.exec(xml))){
            sheets.push(match_s[1]);
            titles.push(match_t[1]);
        }
        // first entry in both lists is the full worksheet, don't care about that.
        sheets.shift();
        titles.shift();
        // set options on drop down.
        var select = document.getElementById('exhibit-worksheet-selection-dropdown');
        select.setAttribute('data-kind', link_kind);
        select.setAttribute('data-sourcename', link_sourcename);
        select.setAttribute('data-key', key);
        var option;
        for(var i=0; i<sheets.length; i++){
            option = document.createElement('OPTION');
            option.text = titles[i];
            option.value = sheets[i];
            select.add(option);
        }
        document.getElementById('exhibit-worksheet-selection').style.display = 'inline';
    }

    function select_sheet(){
        // user chose a sheet, add it
        var select = document.getElementById('exhibit-worksheet-selection-dropdown');
        var link_kind = select.getAttribute('data-kind');
        var link_uri = 'https://spreadsheets.google.com/feeds/list/' + select.getAttribute('data-key') + '/' + select.options[select.selectedIndex].value + '/public/basic?hl=en_US&alt=json-in-script';
        var link_sourcename = select.getAttribute('data-sourcename');
        document.getElementById('id').select.length = 0;
        document.getElementById('exhibit-worksheet-selection').style.display = 'none';
        add_datasource_link(link_kind, link_uri, link_sourcename, 'remote');
    }

</script>
