<p><b>A <b>List</b> displays an intelligent list of data items.</b></p>


<table>
	<tr>
		<td><i>Visualization Title</i></td>
		<td><input id="exhibit-views-list-label" type="text" size="30" /></td>
		<td></td>
	</tr>
	<tr>
		<td><i>List Type</i></td>
		<td><select id="exhibit-views-list-decoration">
            <option value="numbered">Numbered List</option>
            <option value="bulleted">Bullet Points</option>
            <option value="squared">Square Bullet Points</option>
            <option value="none">No Decoration</option>
		</select>	
		</td>
		<td></td>
	</tr>
	<tr>
		<td><i>Only show items of type</i></td>
		<td><select id="view-list-klass" class="alltypebox"></select></td>
		<td>(Optional)</td>
	</tr>
	<tr>
		<td><i>Default sort by field</i></td>
		<td><select id="exhibit-views-list-sortby" class="allpropbox"></select></td>
		<td>(Optional)</td>
	</tr>

        <tr>
            <td><i>Extra Attributes (Advanced)</i></td>
            <td><input id="exhibit-views-list-extra-attributes" type="text" size="40" /></td>
        </tr>    



</table>
<br />
<p align="right"><a href="#" class="addlink" onclick="submit_view_list_facet(); return false">Add List</a></p>

<script type="text/JavaScript">

function submit_view_list_facet() {
	var label = jQuery('#exhibit-views-list-label').val();
	var kind = 'view-tile';
	var sortby = jQuery('#exhibit-views-list-sortby').val();
	var decoration = jQuery('#exhibit-views-list-decoration').val()
	var klass = jQuery('#view-list-klass').val();
	var extra_attributes = jQuery('#exhibit-views-list-extra-attributes').val();
	
	var params = 	{
			kind: kind,
			label: label,
			decoration: decoration
	};
	
	if (sortby != null) {
		params['sortby'] = sortby;
	}
	if (klass != null) {
		params['klass'] = klass;
	}

	if (extra_attributes != null) {
		params['extra_attributes'] = extra_attributes;
	}
	
	editinfo = {
            editable: true,
            tabid: "exhibit-views-list"
    };
	
	addExhibitElementLink("views-list", "List: " + label, 'view', params, editinfo);
}
</script>

