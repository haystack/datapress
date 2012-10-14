<?php
if (!$guessurl = site_url())
	$guessurl = wp_guess_url();
$baseuri = $guessurl;
$exhibituri = $baseuri . '/wp-content/plugins/datapress';
?>
<p><b>A <i>Search Facet</i> lets you search across the text content of your Exhibit data.</b></p>

<table>
	<tr>
	    <td><i>Facet Title</i></td>
	    <td><input id="exhibit-facet-search-label" type="text" size="30" /></td>
	    <td></td>
	</tr>
    <tr>
		<td>Facet Location Relative to View</td>	
		<td>
		  <select id="exhibit-facet-search-location">
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
	    <td><select id="exhibit-facet-search-klass" class="alltypebox"></select></td>
	    <td>(Optional)</td>
	</tr>
</table>
	<p align="right"><a href="#" class="addlink" onclick="submit_search_facet(); return false">Add Search Facet</a></p>

<script type="text/JavaScript">

function submit_search_facet() {
	var label = jQuery('#exhibit-facet-search-label').val();
    var location = jQuery('#exhibit-facet-search-location').val();
	var kind = 'search';
	var klass = jQuery('#exhibit-facet-search-klass').val();
	
	var params = 	{
			kind: kind,
			label: label,
			location: location
	};
	if (klass != null) {
		params['klass'] = klass;
	}
	
	addExhibitElementLink(
		"facet-list", 
		"Search Facet", 
		'facet',
        params,
        {
            editable: true,
            tabid: "exhibit-facet-search"
        }
	 );	
}

</script>
