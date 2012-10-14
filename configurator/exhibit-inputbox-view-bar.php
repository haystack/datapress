<p><b>A <b>Bar Chart</b> displays numeric data as set of bars.</b></p>


	<table>
		<tr><td><i>Visualization Title</i></td><td><input id="exhibit-views-bar-label" type="text" size="30" /></td><td></td></tr>
		<tr><td><i>Categories</i></td><td><select id="exhibit-views-bar-yField" class="allpropbox"></select></td><td></td></tr>
		<tr><td><i>Categories Label</i></td><td><input id="exhibit-views-bar-yLabel" /></td><td></td></tr>
		<tr><td><i>Values</i></td><td><select id="exhibit-views-bar-xField" class="allpropbox"></select></td><td></td></tr>
		<tr><td><i>Values Label</i></td><td><input id="exhibit-views-bar-xLabel" /></td><td></td></tr>
		<tr>
			<td><i>Only show items of type</i></td>
			<td><select id="view-bar-klass" class="alltypebox"></select></td>
			<td>(Optional)</td>
		</tr>

       <tr>
            <td><i>Extra Attributes (Advanced)</i></td>
            <td><input id="exhibit-views-bar-extra-attributes" type="text" size="40" /></td>
        </tr>    

    </table>
	<br />
	<p align="right"><a href="#" class="addlink" onclick="submit_view_bar_facet(); return false">Add Bar Chart</a></p>

<script type="text/JavaScript">

function submit_view_bar_facet() {
		var kind = 'view-bar';
		var label = jQuery('#exhibit-views-bar-label').val();
		var xField = jQuery('#exhibit-views-bar-xField').val();
		var yField = jQuery('#exhibit-views-bar-yField').val();
		var xLabel = jQuery('#exhibit-views-bar-xLabel').val();
		var yLabel = jQuery('#exhibit-views-bar-yLabel').val();
		var klass = jQuery('#view-bar-klass').val();
		var extra_attributes = jQuery('#exhibit-views-bar-extra-attributes').val();

		var params = 	{
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
            tabid: "exhibit-views-bar"
        };
	
		addExhibitElementLink("views-list", "Bar Chart: " + label, 'view', params, editinfo);

	}
</script>

