<?php
class WpExhibitLens extends WpExhibitModel {
	
	protected $fields = array(
		'kind' => NULL,
		'klass'  => NULL,
		'class'  => NULL,
		'html' => NULL,
        'decoration' => NULL,
		'exhibitid' => NULL,
	);
	
	function WpExhibitLens($opts = NULL) {
		parent::WpExhibitModel($opts);		
	}

	function getTableName() {
		return WpExhibitConfig::table_name(WpExhibitConfig::$LENSES_TABLE_KEY);
	}
	
	function getFormPrefix() {
		return WpExhibitConfig::$LENSE_FORM_PREFIX;
	}
	
	function getShortKind() {
		switch($this->get('kind')) {
			case 'lens':
				return "Lens";
			default:
				return "UnknownLens";
		}
	}	

	function getLinkCaption() {
		return $this->getShortKind() . " for " . $this->get('klass');
	}	
	
	function htmlContent() {
		$klass = $this->get('klass');
		$html = $this->get('html');
		$massaged_html = $this->massage_html($html);
		$ret = "<div ex:role=\"lens\" itemTypes=\"$klass\" style=\"display: none;\">
           <div class=\"dp-lenswrapper\">
             <div class=\"tl\"></div>
             <div class=\"t\"></div>
             <div class=\"tr\"></div>

             <div class=\"l\"></div>
             <div class=\"center\">$massaged_html</div>
             <div class=\"r\"></div>

             <div class=\"bl\"></div>
             <div class=\"b\"></div>
             <div class=\"br\"></div>
			</div>
        </div>";
		return $ret;
	}

	function massage_html($html) {
	    // Replace images
		$image_pattern = "~{{image ([^\}]*)}}~";
	    $image_replacement = "<img ex:src-content=\"$1\" />";
        $first_pass = preg_replace($image_pattern, $image_replacement, $html);
		$text_pattern = "~{{([^\}]*)}}~";
		$text_replacement = "<span ex:content=\"$1\"></span>";
		$second_pass = preg_replace($text_pattern, $text_replacement, $first_pass);
		return $second_pass;
	}
	
	function getEditInfo() {
	    switch($this->get('kind')) {
			case 'lens':
                return 'editable: true, tabid: "exhibit-lenses-edit"';
	    	default:
        	    return 'editable: false';
		}
	}
}
?>
