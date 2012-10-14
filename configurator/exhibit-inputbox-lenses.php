<div class="outer-tabs-panel-header">
<div class="current">
<ul id="lens-list">
</ul>
<?php
	if ($exhibitConfig != NULL) {
		echo '<script type="text/javascript">';
		$lenses = $exhibitConfig->get('lenses'); 
		foreach ($lenses as $lens) {
			echo $lens->getAddLink('lens-list');
		}
		echo '</script>';				
	}
?>
</div>
</div>

<div id="exhibit-lenses-edit">
<?php include('exhibit-inputbox-lense-edit.php'); ?>
</div>

<script type="text/javascript">
jQuery(document).ready(function(){
	var datasource_tabs = jQuery("#exhibit-lenses-tabs > ul").tabs();
});
</script>
