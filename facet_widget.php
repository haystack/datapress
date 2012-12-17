<?php
function widget_datapressFacet($args) {
  extract($args);
 
	global $wp_query;
	$datapress_exhibit = null;
	foreach ($wp_query->posts as $apost) {
		if (isset($apost->datapress_exhibit)) {
			$datapress_exhibit = $apost->datapress_exhibit;			
		}
	}		

  if (isset($datapress_exhibit) && ($datapress_exhibit != NULL)) {
	if (! $datapress_exhibit->get('lightbox')) {
		foreach ($datapress_exhibit->get('facets') as $facet) {
		if ($facet->get('location') == 'widget') {
			echo $before_widget;
			echo $before_title . $facet->get('label') . $after_title;
			echo $facet->htmlContent(false);
			echo $after_widget;
	      }
	  }
	}
  }
  
}

function datapressFacet_init()
{
  register_sidebar_widget(__('DataPress Facets'), 'widget_datapressFacet');
}
add_action("plugins_loaded", "datapressFacet_init");
?>
