<?php
class WpExhibitView extends WpExhibitModel {
	
	protected $fields = array(
		'kind' => NULL,
		'klass' => NULL,
		'field'  => NULL,
		'itemtype' => NULL,
		'label' => NULL,
		'caption' => NULL,
		'end' => NULL,
		'locationtype' => NULL,
		'coderfield' => NULL,
		'exhibitid' => NULL,
		'xField' => NULL,
		'yField' => NULL,
		'xLabel' => NULL,
		'yLabel' => NULL,
		'yScale' => NULL,
		'xScale' => NULL,
		'color' => NULL,
		'sortby' => NULL,
		'decoration' => NULL,
		'proxy' => NULL,
		'icon' => NULL,
		'bubblewidth' => NULL,
		'bubbleheight' => NULL,		
		'markerwidth' => NULL,
        'markerheight' => NULL,
        'height' => NULL,
        'topBandUnit' => NULL,
        'bottomBandUnit' => NULL,        
		'extra_attributes' => NULL,
        'ungrouped' => NULL 
    );
	
	function WpExhibitView($opts = NULL) {
		parent::WpExhibitModel($opts);		
	}

	function getTableName() {
		return WpExhibitConfig::table_name(WpExhibitConfig::$VIEWS_TABLE_KEY);
	}
	
	function getFormPrefix() {
		return WpExhibitConfig::$VIEW_FORM_PREFIX;
	}
	
	function getShortKind() {
		switch($this->get('kind')) {
			case 'view-table':
				return "Table";
			case 'view-timeline':
				return "Timeline";
			case 'view-map':
				return "Map";
			case 'view-tile':
				return "List";
			case 'view-scatter':
				return "Scatter Plot";
			case 'view-bar':
				return "Bar Chart";
			default:
				return "UnknownView";
		}
	}
	
	function getLinkCaption() {
		return $this->getShortKind() . ": " . $this->get('label');
	}	
    
    function propString($prop, $value, $default) {
      if ($value == NULL) {
          if ($default == NULL) {
              return "";
          }
          else {
              return " " . $prop . "=\"" . $default . "\" ";
          }
      }
      else {
          return " " . $prop . "=\"" . $value . "\" ";
      }
    }

    function htmlContent() {
		$kind = $this->get('kind');
		$label = $this->get('label');
		$klass = $this->get('klass');
		$collection_insert = '';
		/*if ($klass != NULL) {
			$collection_insert = "ex:collectionID='collection_$klass'";
		}
		else {
			$collection_insert = "ex:collectionID='auto_union'";
		}*/

		if ($kind == "view-tile") {
			// Todo: add the actual date and time stuff
			$bulletstyle = $this->get('decoration');
            $inner = "";

            if ($this->get('extra_attributes') != NULL) {
                $inner .= " " . $this->get('extra_attributes') . " ";
            }
 
            return "<div class=\"$bulletstyle\" ex:role=\"exhibit-view\" ex:viewClass=\"Exhibit.TileView\" $collection_insert $inner ex:label=\"$label\"></div>";
		}		
		
		if ($kind == "view-timeline") {
			// Todo: add the actual date and time stuff
			$start = $this->get('field');
			
			$proxy = $this->get('proxy');
			$inner = "";
			if ($proxy != NULL) {
				$inner .= " ex:proxy='.$proxy' ";
            }
            
            $inner .= $this->propString("ex:timelineHeight", $this->get('height'), "170");
            $inner .= $this->propString("ex:topBandUnit", $this->get('topBandUnit'), NULL);
            $inner .= $this->propString("ex:bottomBandUnit", $this->get('bottomBandUnit'), NULL);
            if ($this->get('extra_attributes') != NULL) {
                $inner .= " " . $this->get('extra_attributes') . " ";
            }
           
            $ret =  "<div ex:role=\"view\" ex:viewClass=\"Timeline\" $collection_insert ex:bubbleWidth='320' ex:topBandPixelsPerUnit='400' $inner ex:label=\"$label\" ex:start=\".$start\"";
			if ($this->get('end') != null) {
				$end = $this->get('end');
				$ret = $ret . " ex:end=\".$end\"";
			}
			$ret = $ret . "></div>";        
			return $ret;
		}
		else if ($kind == "view-map") {		
			// Todo: add the actual location stuff
			
			
			$field = $this->get('field');
			$locationtype = $this->get('locationtype');
			$where = "ex:latlng='.$field'";
			$icon = $this->get('icon');
			$proxy = $this->get('proxy');
			$bw = $this->get('bubblewidth');
			$bh = $this->get('bubbleheight');
			$mw = $this->get('markerwidth');
			$mh = $this->get('markerheight');
			
			$after = "";
			$inner = "";

			if ($icon != NULL) {
				$inner .= " ex:icon='.$icon' ";
			}
			if ($proxy != NULL) {
				$inner .= " ex:proxy='.$proxy' ";
			}
            
            if ($this->get('extra_attributes') != NULL) {
                $inner .= " " . $this->get('extra_attributes') . " ";
            }
	    	//This case will handle if we are using geocoded data.
			if($locationtype == 'address') {
				$field = $field . '_generatedLatLng';	
			} 
			// NOTE: There is currently no nocderfeld thing being put in here.
			$ret = "<div ex:role='view' ex:viewClass='Map' ex:label='$label' $collection_insert ex:latlng='.$field' ex:bubbleWidth='$bw' ex:bubbleHeight='$bh' ex:shapeWidth='$mw' ex:shapeHeight='$mh' $inner ></div>";   
			return $ret;
		}
		else if ($kind == "view-table") {
			$columns = $this->get('field');
			$columnlabels = $this->get('caption');
			$klass = $this->get('klass');

            $inner = "";

            if ($this->get('extra_attributes') != NULL) {
                $inner .= " " . $this->get('extra_attributes') . " ";
            }
 
			// Todo: add the actual location stuff
			$ret = "<div ex:role=\"view\" ex:viewClass=\"Exhibit.TabularView\" $collection_insert $inner ex:label=\"$label\" ex:columns=\"$columns\" ex:columnLabels=\"$columnlabels\">";
			$ret .= "<table style=\"display: none;\"><tr>";			
			$columns_arr = explode(",", $columns);
			foreach($columns_arr as $column) {
				$ret .= "<td><span ex:content=\"$column\"></span></td>";			 
			}
			$ret .= "</tr></table></div>";
			return $ret;
		}  
		if ($kind == "view-scatter") {
			// Todo: add the actual date and time stuff
			$field_x = $this->get("xField");
			$field_y = $this->get("yField");
			$field_xLabel = $this->get("xLabel");
            $field_yLabel = $this->get("yLabel");
            $inner = "";
            if ($this->get('extra_attributes') != NULL) {
                $inner .= " " . $this->get('extra_attributes') . " ";
            }
 
			$ret =  "<div ex:role=\"view\" ex:viewClass=\"Exhibit.ScatterPlotView\" $collection_insert $inner ex:label=\"$label\" ex:x=\".$field_x\" ex:y=\".$field_y\" ex:xLabel=\"$field_xLabel\" ex:yLabel=\"$field_yLabel\"";
			$ret = $ret . "></div>";        
			return $ret;
		}		      
		if ($kind == "view-bar") {
			// Todo: add the actual date and time stuff
			$field_x = $this->get("xField");
			$field_y = $this->get("yField");
			$field_xLabel = $this->get("xLabel");
            $field_yLabel = $this->get("yLabel");
            $inner = "";
            if ($this->get('extra_attributes') != NULL) {
                $inner .= " " . $this->get('extra_attributes') . " ";
            }
 
			$ret =  "<div ex:role=\"view\" ex:viewClass=\"Exhibit.BarChartView\" $collection_insert $inner ex:label=\"$label\" ex:x=\".$field_x\" ex:y=\".$field_y\" ex:xLabel=\"$field_xLabel\" ex:yLabel=\"$field_yLabel\"";
			$ret = $ret . "></div>";        
			return $ret;
		}		      
	}
	
	function getEditInfo() {
	    switch($this->get('kind')) {
			case 'view-table':
        	    return 'editable: true, tabid: "exhibit-views-table"';
			case 'view-timeline':
        	    return 'editable: true, tabid: "exhibit-views-timeline"';
			case 'view-map':
        	    return 'editable: true, tabid: "exhibit-views-maps"';
			case 'view-tile':
        	    return 'editable: true, tabid: "exhibit-views-list"';
			case 'view-scatter':
        	    return 'editable: true, tabid: "exhibit-views-scatter"';
			case 'view-bar':
        	    return 'editable: true, tabid: "exhibit-views-bar"';
			default:
        	    return 'editable: false';
		}
	}
	
}
?>
