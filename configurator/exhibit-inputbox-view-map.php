<p><b>A <b>Map</b> displays location-based data on a Google Map.</b></p>
	<table>
		<tr>
			<td><i>Visualization Title</i></td>
			<td><input id="exhibit-views-maps-label" type="text" size="30" /></td>
			<td></td>
		</tr>
		<tr>
			<td><i>Location field</i></td>
			<td><select id="exhibit-views-maps-field" class="allpropbox"></select> contains a 
				<select id="exhibit-views-maps-locationtype"><option selected value="latlng">Lat,Lng</option><option value="address">Address</option></select>
			</td>
			<td></td>
		</tr>	
		<tr>
			<td><i>Popup Size</i></td>
			<td>Width: <input id="exhibit-views-maps-bubblewidth" value="200" style="width: 40px;" />px, Height: <input style="width: 40px;" id="exhibit-views-maps-bubbleheight" value="200" />px</td>
			<td></td>
		</tr>
		<tr>
			<td><i>Marker Size</i></td>
			<td>Width: <input id="exhibit-views-maps-markerwidth" value="60" style="width: 40px;" />px, Height: <input style="width: 40px;" id="exhibit-views-maps-markerheight" value="60" />px</td>
			<td></td>
		</tr>
		<tr>
			<td><i>Only show items of type</i></td>
			<td><select id="view-map-klass" class="alltypebox"></select></td>
			<td>(Optional)</td>
		</tr>
		<tr>
			<td><i>Icon</i></td>
			<td><select id="exhibit-views-maps-icon" class="allpropbox"></select></td>
			<td>(Optional)</td>
		</tr>
	    <tr>
            <td><i>Extra Attributes (Advanced)</i></td>
            <td><input id="exhibit-views-maps-extra-attributes" type="text" size="40" /></td>
        </tr>   
     </table>
<!--	
<p><i>What field (if any) varies the size of the marker?</i><br /><select id="view-map-coderfield" class="allpropbox"></select></p> 
NOTE: Currently disabled. You have to put the coder definition OUTSIDE the view panel for it to work. Then we can add this back in.
-->
	<br />
	<p align="right"><a href="#" class="addlink" onclick="submit_view_map_facet(); return false">Add Map</a></p>

<script type="text/JavaScript">
var geocodeFields = Array();
function submit_view_map_facet() {
	var label = jQuery('#exhibit-views-maps-label').val();
	var kind = 'view-map';
	var field = jQuery('#exhibit-views-maps-field').val();
	var coderfield = jQuery('#exhibit-views-maps-coderfield').val();
	var locationtype = jQuery('#exhibit-views-maps-locationtype').val();
    var extra_attributes = jQuery('#exhibit-views-maps-extra-attributes').val();
    var icon = jQuery('#exhibit-views-maps-icon').val();
	var bw = jQuery('#exhibit-views-maps-bubblewidth').val();
	var bh = jQuery('#exhibit-views-maps-bubbleheight').val();
	var mw = jQuery('#exhibit-views-maps-markerwidth').val();
	var mh = jQuery('#exhibit-views-maps-markerheight').val();
	var klass = jQuery('#view-map-klass').val();
	
	var params = {
		kind: kind,
		field: field,
		label: label,
		bubblewidth: bw,
		bubbleheight: bh,		
		markerwidth: mw,
		markerheight: mh,		
		coderfield: coderfield,
		locationtype: locationtype
	};
	if (extra_attributes != null) {
	 	params['extra_attributes'] = extra_attributes;
    }
	if (icon != null) {
		params['icon'] = icon;
	}

	if (klass != null) {
		params['klass'] = klass;
	}

	editinfo = {
            editable: true,
            tabid: "exhibit-views-maps"
    };
    	//if the user selected "address", then geocode
    	if(locationtype == 'address') {
		geocodeFields.push(field);
	}
	

	addExhibitElementLink("views-list", "Map: " + label, 'view', params, editinfo);

}
</script>

