<?php
class WpExhibitFacet extends WpExhibitModel {
	
	protected $fields = array(
		'kind' => NULL,
		'field'  => NULL,
		'label' => NULL,
		'exhibitid' => NULL,
		'location' => NULL,
		'klass' => NULL
	);
	
	function WpExhibitFacet($opts = NULL) {
		parent::WpExhibitModel($opts);		
	}

	function getTableName() {
		return WpExhibitConfig::table_name(WpExhibitConfig::$FACETS_TABLE_KEY);
	}
	
	function getFormPrefix() {
		return WpExhibitConfig::$FACET_FORM_PREFIX;
	}
	
	function getShortKind() {
		switch($this->get('kind')) {
			case 'search':
				return "Search Box";
			case 'browse':
				return "Data Browser";
			case 'tagcloud':
				return "Tag Cloud";				
			default:
				return "UnknownFacet";
		}
	}	

	function getLinkCaption() {
		return $this->getShortKind() . ": " . $this->get('label');
	}	
	
	function htmlContent($showLabel) {
		$kind = $this->get('kind');
		$field = $this->get('field');
    $label = $this->get('label');
    if (! $showLabel) {
      $label = "";
    }
		$klass = $this->get('klass');
		
		$collection_insert = '';
		if ($klass != NULL) {
			$collection_insert = "ex:collectionID='collection_$klass'";
		}
		else {
			$collection_insert = "ex:collectionID='auto_union'";
		}
		
		if ($kind == "search") {
			return "<div ex:role=\"facet\" $collection_insert ex:facetClass=\"TextSearch\" ex:facetLabel=\"$label\"></div>";
		} else if ($kind == "browse") {
			return "<div ex:role=\"facet\" $collection_insert ex:expression=\".$field\" ex:facetLabel=\"$label\"></div>";
		} else if ($kind == "tagcloud") {
			return "<div ex:role=\"facet\" $collection_insert ex:facetClass=\"Cloud\" ex:expression=\".$field\" ex:facetLabel=\"$label\"></div>";		
		}
	}
	
	function getEditInfo() {
	    switch($this->get('kind')) {
   			case 'search':
           	    return 'editable: true, tabid: "exhibit-facet-search"';
			case 'browse':
           	    return 'editable: true, tabid: "exhibit-facet-list"';
			case 'tagcloud':
           	    return 'editable: true, tabid: "exhibit-facet-tagcloud"';
	    	default:
        	    return 'editable: false';
		}
	}
}
?>
