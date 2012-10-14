<?php
if (!$guessurl = site_url())
	$guessurl = wp_guess_url();
$baseuri = $guessurl;
$exhibituri = $baseuri . '/wp-content/plugins/datapress';
?>
<p><b>A <i>List Facet</i> lets you browse through buckets of items in you Exhibit data.</b></p>
<table>
	<tr>
	    <td><i>Facet Title</i></td>
	    <td><input id="exhibit-facet-list-label" type="text" size="30" /></td>
	    <td></td>
	</tr>
	<tr class="help">
	    <td colspan=3>
	    </td>
	<tr>
	    <td><i>Use Field</i></td>
	    <td><select id="exhibit-facet-list-field" class="allpropbox"></select></td>
	    <td></td>
	</tr>
    <tr>
		<td>Facet Location Relative to View</td>	
		<td>
		  <select id="exhibit-facet-list-location">
		    <option value="left">Left</option>
		    <option value="right">Right</option>
    		<option value="top">Top</option>
    		<option selected value="bottom">Bottom</option>
	  	    <option value="widget">Sidebar Widget</option>
		  </select>
		</td>
	</tr>	
	<tr>
	    <td><i>Only filter items of type</i></td>
	    <td><select id="exhibit-facet-list-klass" class="alltypebox"></select></td>
	    <td>(Optional)</td>
	</tr>
</table>
<p align="right"><a href="#" class="addlink" onclick="submit_list_facet(); return false">Add List Facet</a></p>

<script type="text/JavaScript">
function submit_list_facet() {
	var label = jQuery('#exhibit-facet-list-label').val();
    var location = jQuery('#exhibit-facet-list-location').val();	
	var kind = 'browse';
	var field = jQuery('#exhibit-facet-list-field').val();
	var klass = jQuery('#exhibit-facet-list-klass').val();
	
	var params = 	{
			kind: kind,
			label: label,
			field: field,
			location: location
	};
	
	if (klass != null) {
		params['klass'] = klass;
	}
	
	addExhibitElementLink(
		"facet-list", 
		"List Facet (" + field + ")", 
		'facet',
		params,
        {
            editable: true,
            tabid: "exhibit-facet-list"
        }
	);	
}
</script>
