<?php

class WpExhibitHtmlBuilder {
	 
	static $datapress_statistics_logger = "http://projects.csail.mit.edu/datapress/logger/logger.php";

    static function insert_exhibit($exhibit, $content) {
		global $wp_query;	
		$exhibit_string = '';
		$mycount = 0;
		foreach ($wp_query->posts as $mypost) {
			if($mypost->datapress_exhibit != null){
			$mycount += 1;
			}
		}		
		if ($exhibit->get('lightbox')) {
			$exhibit_string = self::get_exhibit_lightbox_link($exhibit);
		} elseif($mycount==1) {
	  		/*$lightboxed_exhibit = new WpPostExhibit();
        		DbMethods::loadFromDatabase($lightboxed_exhibit, $exhibitid);
			$currentview = $_GET['currentview'];
    			$postid = $_GET['postid'];*/
  			$exhibit_string = self::get_exhibit_html($exhibit, $currentview, $postid);
			echo $exhibit_string;
			//echo "I died";
			//die;
		} else{
			$exhibit_string = self::get_inline_exhibit($exhibit);
			//echo "else oops";
		}
        if (is_feed()) {
            $postid = $wp_query->post->ID;
            $permalink = get_permalink($postid);
            $exhibit_string .= "<p><b>Note: This post contains a interactive data presentation that may not show up in your feed reader.</b> For the full experience, visit <a href='$permalink'>this article</a> in your web browser.</p>";
        }

	if (!$guessurl = site_url())
    		$guessurl = wp_guess_url();
    		$exhibituri = $guessurl . '/wp-content/plugins/datapress';
    		$imageurl = $exhibituri . '/exhibit.png';   	
	
	

        $content = str_replace('<img src="' . $imageurl . '" alt="Your Exhibit" width="70" height="70" />', $exhibit_string, $content);
        $footnotes_string = self::get_data_footnotes_html($exhibit);
        $content = str_replace("{{Footnotes}}", $footnotes_string, $content);            
      
        return $content;
    }
    
    static function get_exhibit_lightbox_link($exhibit) {	
	    global $wp_query;
		if (!$guessurl = site_url())
	    	$guessurl = wp_guess_url();
	    $baseuri = $guessurl;
	    $exhibituri = $baseuri . '/wp-content/plugins/datapress';
	    $exhibitid = $exhibit->get('id');
	    $postid = $wp_query->post->ID;
	    $exhibit_html .= "<a href='$exhibituri/wp-exhibit-only.php?iframe&exhibitid=$exhibitid&postid=$postid&currentview=lightbox' class='exhibit_link_$exhibitid'>";
	    $exhibit_html .= "<div class='teaser' id='teaser_$exhibitid'>
                                      <iframe src='$exhibituri/wp-exhibit-only.php?iframe&exhibitid=$exhibitid&postid=$postid&currentview=preview' width='100%' height='300' scrolling='no' frameborder='0'>
                                      <p>Your browser does not support iframes.</p>
                                      </iframe>
                                      </div>
                                      <div class='cover' id='cover_$exhibitid'>
                                      </div>";
        $exhibit_html .= "</a>";
		return $exhibit_html;
	}
	
	static function get_inline_exhibit($exhibit) {
	    global $wp_query;
		if (!$guessurl = site_url())
	    	$guessurl = wp_guess_url();
	    $baseuri = $guessurl;
	    $exhibituri = $baseuri . '/wp-content/plugins/datapress';
	    $exhibitid = $exhibit->get('id');
	    $postid = $wp_query->post->ID;
	    $height = $exhibit->get('height');
       	//this works but should be moved later
	$mycount = 0;
	foreach ($wp_query->posts as $mypost) {
		if($mypost->datapress_exhibit != null){
		$mycount += 1;
		}
	}
 	if($mycount>1){
		$exhibit_html = "<iframe src='$exhibituri/wp-exhibit-only.php?iframe&exhibitid=$exhibitid&postid=$postid&currentview=inline' width='100%' height='$height' scrolling='auto' frameborder='0'>
                                      <p>Your browser does not support iframes.</p>
                                      </iframe>";
        	return $exhibit_html;
		}
	else{
		$exhibit_html = "<object data='$exhibituri/wp-exhibit-only.php?iframe&exhibitid=$exhibitid&postid=$postid&currentview=inline'></object>";

/*"<script>$('#testLoad').load('$exhibituri/wp-exhibit-only.php?iframe&exhibitid=$exhibitid&postid=$postid&currentview=inline');    
			<div id='testLoad'></div>";*/
		//global $widget_out = true;
        	return $exhibit_html;
		}	
	}

    static function get_view_html($exhibit, $currentView, $postid) {
        // add statistics gathering
        $tracker_html = self::statistics_html($exhibit, $currentView, $postid);
               
        // add views
        $view_html = "";
	
        if ($currentView == 'preview') {
          foreach ($exhibit->get('views') as $view) {
             $view_html .= $view->htmlContent();
             return "$tracker_html $view_html"; 
          }
        
        }
        else {
	
        $grouped_html = "";
	//print_r($exhibit->get('views'));
	//die;
        foreach ($exhibit->get('views') as $view) {
            if ($view->get('ungrouped') == NULL) {
		                
		$grouped_html .= "\n" . $view->htmlContent();			
		
            }
        }
	echo $grouped_html;
	die;
        $printedGroup = 0;
        foreach ($exhibit->get('views') as $view) {
            if (($view->get('ungrouped') == NULL) && (0 == $printedGroup)) {
                $view_html .= "<div ex:role=\"viewPanel\">$grouped_html</div>";
                $printedGroup = 1;
            }
            else {
              //$view_html .= "\n" . $view->htmlContent();
            }
        }

        }
	    
        if ($view_html == "") {
            $view_html = "<div ex:role=\"view\"></div>";
        }
       //  ex:role=\"viewPanel\"> 
        return "$tracker_html $view_html";
	}

    static function get_exhibit_html($exhibit, $currentView, $postid) {	
        $view_html = self::get_view_html($exhibit, $currentView, $postid);
        
        $lens_html = "";
        foreach ($exhibit->get('lenses') as $lens) {
            $lens_html = $lens_html . $lens->htmlContent();
            $lens_html = $lens_html . "\n";
        }
       
        if ($currentView == 'preview') {
            $exhibit_html = "$lens_html $view_html";
        } else {
            $top_facet_html = self::facet_html($exhibit->get('facets'), 'top');
            $bottom_facet_html = self::facet_html($exhibit->get('facets'), 'bottom');
            $left_facet_html = self::facet_html($exhibit->get('facets'), 'left');
            $right_facet_html = self::facet_html($exhibit->get('facets'), 'right');
            if ($exhibit->get('lightbox')) {
	            $right_facet_html .= self::facet_html($exhibit->get('facets'), 'widget');	
		    }

            $exhibit_colspan = 3;
            if (strlen($left_facet_html) > 0) {
                $exhibit_colspan--;
                $left_facet_html = "<td width=\"15%\"> $left_facet_html </td>";
            }
            if (strlen($right_facet_html) > 0) {
                $exhibit_colspan--;
                $right_facet_html = "<td width=\"15%\"> $right_facet_html </td>";
            }
	
            $custom_html = "";
            if ($exhibit->get('custom_html') != NULL) {
                $custom_html .= $exhibit->get('custom_html');
            }

            $exhibit_html = "
                $custom_html            
		$lens_html
                <table class=\"dpcontainer\" width=\"100%\">
                    <tr>
                        <td colspan='3'>
                            $top_facet_html
                        </td>
                    </tr>
                    <tr valign=\"top\">
                        $left_facet_html
                        <td colspan='$exhibit_colspan'>$view_html</td>
                        $right_facet_html
                    </tr>
                    <tr>
                        <td colspan='3'>
                            $bottom_facet_html
			</td>
                    </tr>
                </table>";
        }

        return $exhibit_html;
    }

    static function statistics_html($exhibit, $currentView, $postid) {
        $html = "";
        if (get_option('datapress_et_phone_home') == "Y") {
            $html .= "<script type='text/javascript'>\n";
	        $report = $exhibit->getStatisticReport($currentView, $postid);
            $html .= "$.get('" . self::$datapress_statistics_logger . "', $report, null, 'script');\n";
	        $html .= "</script>\n";
	    }
	    return $html;
    }

    static function facet_html($facets, $location) {
        $facet_html = "";
        foreach ($facets as $facet) {
            if ($facet->get('location') == $location) {
                $facet_html = $facet_html . $facet->htmlContent();
                $facet_html = $facet_html . "\n";
            }
        }
        return $facet_html;
    }
    
    static function get_data_footnotes_html($exhibit) {
        $html = "<ul>";
        $ex_datasources = $exhibit->get('datasources');
		foreach($ex_datasources as $ex_datasource) {
			$sourcename = $ex_datasource->get('sourcename');
			$uri = $ex_datasource->get('uri');
    		$html .= "<li> <a href='$uri'>$sourcename</a>\n";
		}
		$html .= "</ul>";
		return $html;
    }
}

?>

