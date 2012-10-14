<div class="outer-tabs-panel-header">
<div class="current">
</div>

<?php
    $checked = "";
	$css = "";
	$custom_html = "";
	$height = 700;
	if ($exhibitConfig != NULL) {
 		if ($exhibitConfig->get('lightbox') == false) {
			$checked = "";
		}
		else {
		    $checked = "checked";
		}
		if ($exhibitConfig->get('css') != NULL) {
			$css = $exhibitConfig->get('css');
		}
		if ($exhibitConfig->get('height', true) != NULL) {
			$height = $exhibitConfig->get('height');
        }
        if ($exhibitConfig->get('custom_html') != NULL) {
            $custom_html = $exhibitConfig->get('custom_html');
        }
	}
?>

<div id="exhibit-datasource-link" class="inner-tabs-panel">
<table>
	<tr>
		<td><i>Lightbox the exhibit?</i></td> 
		<td><input name="display-configuration-lightbox" id="display-configuration-lightbox" type="checkbox" value="show-lightbox" <?php echo $checked; ?>></td>
	</tr>
	<tr>
		<td><i>Unlightboxed exhibit height</i></td> 
		<td><input name="display-configuration-height" id="display-configuration-height" value="<?php echo $height; ?>"></td>
	</tr>

	<tr>
		<td><i>Attach custom CSS<br /> (provide URL)</i></td> 
		<td><input style="width: 300px;" name="display-configuration-css" id="display-configuration-css" value="<?php echo $css ?>" /></td>
	</tr>
    <tr>
        <td><i>Custom HTML</i></td>
        <td>This will be inserted into the Exhibit configuration<br />
        <textarea name="display-configuration-custom-html" id="display-configuration-custom-html" rows="10" cols="40"><?php echo $custom_html ?></textarea>
        </td>


</table>	
</div>

