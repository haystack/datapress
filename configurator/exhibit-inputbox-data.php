<div class="outer-tabs-panel-header">
<div class="current">
	<table width="100%">
		<col /><col />
		<tr>
			<td width="50%" valign="top">
				<p>Datapress currently supports JSON, JSONP, and Google Spreadsheet data sources. You can also import data sources from an existing Exhibit using its URL.</p>
				<p>[ <a href="#" onClick="ex_load_links(); return false;">Refresh Data</a> ]</p>
			</td>
			<td width="50%" valign="top">
				<p><b>Current Data Sources</b></p>
<ul id="data-source-list">
	<?php
		if ($exhibitConfig != NULL) {
			$ex_dataSources = $exhibitConfig->get('datasources');
			echo '<script type="text/javascript">';
			foreach ($ex_dataSources as $ex_dataSource) {
				echo 'var remove_id = ' . $ex_dataSource->getAddLink('data-source-list');
				echo("ex_add_head_link('" . $ex_dataSource->get('uri') . "', '" . $ex_dataSource->get('kind') . "', remove_id);");
			}
			echo '</script>';				
		}
	?>		
</ul>
	</td></tr></table>
</div>
</div>

<div id="exhibit-datasource-link" class="inner-tabs-panel">
	<?php include("exhibit-inputbox-data-link.php") ?>
</div>

<script type="text/javascript">
jQuery(document).ready(function(){
	var datasource_tabs = jQuery("#exhibit-datasource-tabs > ul").tabs();
});
</script>
