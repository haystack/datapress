<p><b>A <b>Scatter Plot</b> displays data as an graph of data points.</b></p>
	<table>
		<tr>
			<td><i>Visualization Title</i></td>
			<td><input id="exhibit-views-scatter-label" type="text" size="30" /></td>
			<td></td>
		</tr>
		<tr>
			<td><i>X Axis</i></td>
			<td><select id="exhibit-views-scatter-xField" class="allpropbox"></select></td>
			<td></td>
		</tr>
		<tr>
			<td><i>X Axis Label</i></td>
			<td><input id="exhibit-views-scatter-xLabel" /></td>
			<td></td>
		</tr>
		<tr>
			<td><i>Y Axis</i></td>
			<td><select id="exhibit-views-scatter-yField" class="allpropbox"></select></td>
			<td></td>
		</tr>
		<tr>
			<td><i>Y Axis Label</i></td>
			<td><input id="exhibit-views-scatter-yLabel" /></td>
			<td></td>
		</tr>
		<tr>
			<td><i>Only show items of type</i></td>
			<td><select id="view-scatter-klass" class="alltypebox"></select></td>
			<td>(Optional)</td>
		</tr>

       <tr>
            <td><i>Extra Attributes (Advanced)</i></td>
            <td><input id="exhibit-views-scatter-extra-attributes" type="text" size="40" /></td>
        </tr>    


    </table>
	<br />
	<p align="right"><a href="#" class="addlink" onclick="submit_view_scatter_facet(); return false">Add Scatter Plot</a></p>

<script type="text/JavaScript">

function submit_view_scatter_facet() {
	var kind = 'view-scatter';
	var label = jQuery('#exhibit-views-scatter-label').val();
	var xField = jQuery('#exhibit-views-scatter-xField').val();
	var yField = jQuery('#exhibit-views-scatter-yField').val();
	var xLabel = jQuery('#exhibit-views-scatter-xLabel').val();
	var yLabel = jQuery('#exhibit-views-scatter-yLabel').val();
	var klass = jQuery('#view-scatter-klass').val();
	var extra_attributes = jQuery('#exhibit-views-scatter-extra-attributes').val();
	
	var params = {
		kind: kind,
		xField: xField,
		yField: yField,
		xLabel: xLabel,
		yLabel: yLabel,
		label: label
	};
	
	if (extra_attributes != null) {
	 	params['extra_attributes'] = extra_attributes;
	}	
	if (klass != null) {
		params['klass'] = klass;
	}	
	
	editinfo = {
            editable: true,
            tabid: "exhibit-views-scatter"
    };
	
	addExhibitElementLink("views-list", "Scatter Plot: " + label, 'view', params, editinfo);
}
</script>

