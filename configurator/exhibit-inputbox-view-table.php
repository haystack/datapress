<p><b>A <b>Table</b> displays data in tabular format.</b></p>

	<table>
		<tr><td><i>Visualization Title</i></td><td><input id="exhibit-views-table-label" type="text" size="30" /></td><td></td></tr>
		<tr><td><i>Include Fields</i></td><td><select id="exhibit-views-table-field" style="height: 100px; width: 200px;" class="allpropbox" multiple></select></td><td></td></tr>
		<tr><td><i>Field Captions</i><br />(Comma-separated)</td><td><input id="exhibit-views-table-caption" title="Enter captions for the fields you have selected as a comme-separated list in the same order as they appear above." type="text" size="30" /></td><td>(Optional)</td></tr>
		<tr><td><i>Only show items of type</i></td><td><select id="exhibit-views-table-klass" class="alltypebox"></select></td><td>(Optional)</td></tr>

       <tr>
            <td><i>Extra Attributes (Advanced)</i></td>
            <td><input id="exhibit-views-table-extra-attributes" type="text" size="40" /></td>
        </tr>    


    </table>
	<br />
	<p align="right"><a href="#" class="addlink" onclick="submit_view_table_facet(); return false">Add Table</a></p>


<script type="text/JavaScript">

function submit_view_table_facet() {
	var kind = 'view-table';
	var label = jQuery('#exhibit-views-table-label').val();
	var klass = jQuery('#exhibit-views-table-klass').val();
	var fields = jQuery('#exhibit-views-table-field')[0];
	var caption = jQuery('#exhibit-views-table-caption').val();
	var extra_attributes = jQuery('#exhibit-views-table-extra-attributes').val();

	var field = "";
		
	for (var i=0; i<fields.options.length ;i++) {
		if (fields.options[i].selected) {
			field = field + "." + fields.options[i].value + ",";
		}
	}

	if (field != "") {
		field = field.substring(0,field.length-1);
	}
	
	var params = 	{
			kind: kind,
			field: field,
			caption: caption,
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
            tabid: "exhibit-views-table"
    };
	
	addExhibitElementLink(
		"views-list", 
		"Table: " + label, 
		'view', params,
		editinfo
	);
}
</script>

