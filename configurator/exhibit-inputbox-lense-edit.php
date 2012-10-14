	<table>
		<tr>
			<td>Create a New Lens for</td>
			<td><select id="exhibit-lenses-edit-klass" class="alltypebox"></select></td>
		</tr>
	</table>
	<textarea id='exhibit-lenses-edit-html' class='' style="height: 300px; width: 100%;" ></textarea>
<table width="100%">
	<tr colspan="2">
		<td align="left">Available Properties: <select id="lense-prop-possibilities" class="allpropbox"></select>
		        <a href="#" class="addlink" onClick="appendToLens('{{.' + jQuery('#lense-prop-possibilities').val() + '}}'); return false;">Add as Text</a>
		        <a href="#" class="addlink" onClick="appendToLens('{{image .' + jQuery('#lense-prop-possibilities').val() + '}}'); return false;">Add as Image</a>
		</td>
    </tr>
    <tr>
        <td align="left">
            Lens Decoration:
            <select id="exhibit-lenses-edit-decoration">
                <option value="none">None</option>
                <option value="simple-box">Grey Border</option>
                <option value="simple-orange">Rounded Orange Box</option>
                <option value="pframe">Photo Frame</option>
            </select>
        </td>
        <td align="right"><a href="#" class="addlink" onclick="submit_lens(); return false">Save Lens</a></td>
	</tr>
</table>

</div>

<script type="text/javascript">
	jQuery().ready(function() {
		jQuery('#exhibit-lenses-edit-html').tinymce({
<?php
    $wp_datapress_plugin_url = trailingslashit( get_bloginfo('wpurl') ).PLUGINDIR.'/datapress';    
    $mce_url = "$wp_datapress_plugin_url/tinymce/tiny_mce.js";
?>
	        script_url : '<? echo $mce_url ?>',
			// General options
			theme : "advanced",
			plugins : "safari,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template",

			// Theme options
			theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,forecolor,backcolor,|,justifyleft,justifycenter,justifyright,justifyfull,formatselect,fontselect,fontsizeselect",
			theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|preview",
			theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,fullscreen",
			theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak",
			theme_advanced_toolbar_location : "top",
			theme_advanced_toolbar_align : "left",
			theme_advanced_statusbar_location : "bottom",
			theme_advanced_resizing : true,

			// Example content CSS (should be your site CSS)
			content_css : "css/content.css",

			// Drop lists for link/image/media/template dialogs
			template_external_list_url : "lists/template_list.js",
			external_link_list_url : "lists/link_list.js",
			external_image_list_url : "lists/image_list.js",
			media_external_list_url : "lists/media_list.js",

			// Replace values for the template plugin
			template_replace_values : {
				username : "Some User",
				staffid : "991234"
			}
		});
	});
</script>

<script type="text/javascript">
function submit_lens() {
		// var win = window.dialogArguments || opener || parent || top;
        var klass = jQuery('#exhibit-lenses-edit-klass').val();
        var html = jQuery('#exhibit-lenses-edit-html').val(); // win.tinyMCE.getInstanceById('lens-text').getContent(); //
        var decoration = jQuery('#exhibit-lenses-edit-decoration').val()
        var kind = 'lens';
        
        editinfo = {
            editable: true,
            tabid: "exhibit-lenses-edit"
        };
        
        addExhibitElementLink(
                "lens-list", 
                "Lens for " + klass, 
                'lens',
                {
                        kind: kind,
                        'klass': klass,
                        html: html,
                        decoration: decoration
                },
                editinfo);        
}
</script>
