<?php
class WpExhibitDatasource extends WpExhibitModel {
	
	protected $fields = array(
		'kind' => NULL,
		'uri'  => NULL,
		'sourcename' => NULL,
		'data_location' => NULL,
		'local_path' => NULL
	);
	
	function WpExhibitDatasource($opts = NULL) {
		parent::WpExhibitModel($opts);		
	}

	function getTableName() {
		return WpExhibitConfig::table_name(WpExhibitConfig::$DATASOURCES_TABLE_KEY);
	}
	
	function getFormPrefix() {
		return WpExhibitConfig::$DATA_FORM_PREFIX;
	}
	
	function getShortKind() {
		switch($this->get('kind')) {
			case 'google-spreadsheet':
				return "Google Spreadsheet";
			case 'application/json':
				return "JSON File";
			default:
				return "Unknown Data Type";
		}
	}	

	function getLinkCaption() {
		return $this->getShortKind() . ": " . $this->get('sourcename') . " (<a onclick=\\'popup(this.href); return false;\\' href=\\'" . $this->get('uri') . "\\'>view data</a>)";
	}
	
	function htmlContent() {
		$kind = $this->get('kind');
		
		$uri = $this->get('uri');
    	$sourcename = $this->get('sourcename');
		if ($kind == 'google-spreadsheet') {
			return "<link rel=\"exhibit/data\" type=\"application/jsonp\" href=\"$uri\" ex:converter=\"googleSpreadsheets\" alt=\"$sourcename\"/>";
		}
		else if ($kind == 'application/json') {		
			if ($this->get('data_location') == 'local') {
				return "<link href=\"$uri\" type\"application/json\" rel=\"exhibit/data\" alt=\"$sourcename\" />";							
			}	
			else {
				if (!$guessurl = site_url())
					$guessurl = wp_guess_url();
				$baseuri = $guessurl;
				$exhibituri = $baseuri . '/wp-content/plugins/datapress';
				$parrotbase = $exhibituri . '/proxy/parrot.php';
				return "<link href=\"$parrotbase" . '?url=' . urlencode($uri) . "\" type=\"application/json\" rel=\"exhibit/data\" alt=\"$sourcename\" />";											
			}
		}  
	}
	
	function getEditInfo() {
	    return 'editable: true, tabid: "exhibit-datasource-link"';
	}
}
?>
