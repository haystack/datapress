<?php
/*
Plugin Name: Datapress
Plugin URI: http://projects.csail.mit.edu/datapress
Description: Show maps, timelines, and rich data visualizations in your blog!
Version: 1.5
Author: The Haystack Group @ MIT
Author URI: http://haystack.csail.mit.edu/
*/

include_once('wp-exhibit-config.php');
include_once('wp-exhibit-admin-options.php');
include_once('wp-exhibit-activation-tools.php');
include_once('wp-exhibit-insert-exhibit.php');
include_once('wp-exhibit-save-post.php');
include_once('save-exhibit.php');
include_once('model/wp-exhibit-model.php');
include_once('proxy/insert-parrotable-url.php');	
include_once('proxy/import-datafiles.php');
include_once('configurator/exhibit-configurator.php');
include_once('data-editor/template-browser.php');
include_once('data-editor/template-editor.php');
include_once('wp-exhibit-geocoder.php');
include_once('data-editor/save.php');

class WpExhibit {
 	var $wp_version;
	var $exhibit_from_admin_page;
	
	function WpExhibit() {
		global $wp_version;
		$this->wp_version = $wp_version;
	}

	function exhibit_admin_include() {
		include('exhibit_admin_include.php');		
	}
	
	/*
	 * Only include if the number of posts == 1 and it has an exhibit
	 * on the page. Else just include the other stuff
	 */
	function exhibit_include() {
        include('head.php');
	}
	
	function get_current_exhibit_from_admin_page() {
		// Returned cached version of the exhibit, or cached NULL:
		// NULL -> we have yet to check for exhibit
		// 0    -> we checked for exhibit, it wasn't there (this is a cached NULL)
		// else -> we found the exhibit
		
		if (isset($this->exhibit_from_admin_page) && ($this->exhibit_from_admin_page != null) && (! is_int($this->exhibit_from_admin_page)) ) {
			return $this->exhibit_from_admin_page;
		}
		else if (isset($this->exhibit_from_admin_page) && (is_int($this->exhibit_from_admin_page)) ) {
			return NULL; // cached null
		}
		
		$postID = $_GET['post'];
		if ($postID != NULL) {
			// See if we know about any data sources associated with this item.
		    $ex_exhibit = WpPostExhibit::getForPost($postID);
            if ($ex_exhibit != NULL) {
				$this->exhibit_from_admin_page = $ex_exhibit;
				return $ex_exhibit;
            }
		}
		$this->exhibit_from_admin_page = 0;
		return NULL;		
	}

	function add_options_page() {
		add_options_page('Datapress', 'Datapress', 8, 'datapressoptions', 'exhibit_options_page');		
	}
	
	function edit_page_inclusions() {
		$ex = $this->get_current_exhibit_from_admin_page();
/*		if ($ex == NULL) {
			echo "<input type='hidden' id='exhibitid' name='exhibitid' value='' />";
		}
		else {
			$ex_id = $ex->get('id');
			echo "<input type='hidden' id='exhibitid' name='exhibitid' value='$ex_id' />";
		}*/
	}
	
	function save_post($post_id, $post) {
		SaveExhibitPost::save($post_id);
	}
	
	function privacy_notice() {
		if (! get_option('datapress_privacy_notice_shown')) {
 			echo "<div id='datapress-privacy-notice' class='updated fade' style='font-size: bigger; border: 3px solid #FF9999;'><p style='font-size: 1.2em'><strong>Thank you for installing DataPress!</strong></p><p style='font-size: 1.2em'>DataPress collects some basic statistics about its use to aid in a research project analyzing data publishing on the web. To <em>turn off</em> this data collection, simply visit the <a href='options-general.php?page=datapressoptions'>DataPress Settings Page</a>.</p></div>";			
			add_option('datapress_privacy_notice_shown', '1');
		}
	}	
		
	function activate_plugin() {
        WpExhibitActivationTools::activate_plugin();
	}

	function migrate_plugin() {
        WpExhibitActivationTools::migrate();
	}
	
	function deactivate_plugin() {
		delete_option('datapress_privacy_notice_shown');
        WpExhibitActivationTools::deactivate_plugin();
	}
	
	function load_exhibit() {
		global $wp_query;
		foreach ($wp_query->posts as $apost) {
			$apost->datapress_exhibit = WpPostExhibit::getForPost($apost->ID);
		}		
	}
	
	function insert_parrotable_url() {
        InsertParrotableUrl::insert_url();
	    die;
	}
	
	function save_exhibit_configuration() {
        SaveExhibitConfiguration::save();
	    die;
	}

  function make_exhibit_button() {
	$ex = $this->get_current_exhibit_from_admin_page();
	if ($ex == NULL) {
		echo "Visualization <a id='load_datapress_config_link' href='" . wp_guess_url() . "/wp-admin/admin-ajax.php?action=datapress_configurator&TB_iframe=true' id='add_exhibit' class='thickbox' title='Add an Exhibit'><img src='" . wp_guess_url() . "/wp-content/plugins/datapress/images/exhibit-small-RoyalBlue.png' alt='Add an Exhibit' /></a> &nbsp; &nbsp;<input type='hidden' id='exhibitid' name='exhibitid' value='' />";		
	}
	else {
		$ex_id = $ex->get('id');
		echo "Visualization <a id='load_datapress_config_link' href='" . wp_guess_url() . "/wp-admin/admin-ajax.php?action=datapress_configurator&exhibitid=$ex_id&TB_iframe=true' id='add_exhibit' class='thickbox' title='Add an Exhibit'><img src='" . wp_guess_url() . "/wp-content/plugins/datapress/images/exhibit-small-RoyalBlue.png' alt='Edit the Exhibit' /></a> &nbsp; &nbsp;<input type='hidden' id='exhibitid' name='exhibitid' value='$ex_id' />";				
	}
	
	// Show show the type adder
    // echo "Data Item <a id='load_data_template_editor' href='" . wp_guess_url() . "/wp-admin/admin-ajax.php?action=template_picker&TB_iframe=true' id='add_new_template' class='thickbox' title='Add a Data Item'><img src='" . wp_guess_url() . "/wp-content/plugins/datapress/images/database_link.png' alt='Add some Data' /></a> &nbsp; &nbsp;";
    // 
    // echo "Data Set <a id='load_data_template_editor' href='" . wp_guess_url() . "/wp-admin/admin-ajax.php?action=template_picker&TB_iframe=true' id='add_new_template' class='thickbox' title='Add a Data Item'><img src='" . wp_guess_url() . "/wp-content/plugins/datapress/images/database_link.png' alt='Add some Data' /></a> &nbsp; &nbsp;";
    
        // Context menu for data editor, and location for changing url that is to
        // be clicked if someone wants to edit data.
        echo '<div class="contextMenu" id="dataEditMenu">
          <ul>
            <li id="edit">Edit Data</li>
            <li id="delete">Delete</li>
          </ul>
          <span id="dpEditDataLinkContainer"></span>
        </div>';
    }

	function insert_exhibit($content) {
		global $wp_query;
		if ($wp_query->post->datapress_exhibit != NULL) {
		    return WpExhibitHtmlBuilder::insert_exhibit($wp_query->post->datapress_exhibit, $content);			
		}
		else {
			return $content;
		}
	}

    // Add tinymce plugin for editing data
    function tinymce_editdata_plugin($plugin_array) {
        $plugin_array['dpeditdata'] = wp_guess_url() . "/wp-content/plugins/datapress/js/tinymce_editdata_plugin.js";
         return $plugin_array;
    }
    function tinymce_plugin_css($css) {
        return $css . "," . wp_guess_url() . "/wp-content/plugins/datapress/css/tinymce_editdata_plugin.css";
    }
}

$exhibit = new WpExhibit();
$exhibit->migrate_plugin();

add_action('wp_head', array($exhibit, 'exhibit_include'));
add_action('admin_head', array($exhibit, 'exhibit_admin_include'));
add_action('admin_menu', array($exhibit, 'add_options_page'));

#
# Old lozenge interface.
# add_action('edit_page_form', array($exhibit, 'edit_page_inclusions'));
# add_action('edit_form_advanced', array($exhibit, 'edit_page_inclusions'));
#

add_action('wp', array($exhibit, 'load_exhibit'));
add_action('wp_ajax_insert_parrotable_url', array($exhibit, 'insert_parrotable_url') );
add_action('wp_ajax_save_exhibit_configuration', array($exhibit, 'save_exhibit_configuration') );
add_action('wp_ajax_save_data_template', 'save_data_template' );
add_action('wp_ajax_datapress_configurator', 'show_datapress_configurator' );
add_action('wp_ajax_template_picker', 'show_datapress_template_browser' );
add_action('wp_ajax_template_editor', 'show_datapress_template_editor' );
add_action('wp_ajax_import_datafiles', 'import_data_files' );
add_action('media_buttons', array($exhibit, 'make_exhibit_button'));
add_filter('the_content', array($exhibit, 'insert_exhibit'));
add_action('edit_page_form', array($exhibit, 'edit_page_inclusions'));
add_action('edit_form_advanced', array($exhibit, 'edit_page_inclusions'));
add_action('admin_notices', array($exhibit, 'privacy_notice'));
add_filter('save_post', array($exhibit, 'save_post'), 10, 2);
add_filter("mce_external_plugins", array($exhibit, 'tinymce_editdata_plugin'));
add_filter("mce_css", array($exhibit, "tinymce_plugin_css"));

register_activation_hook(__FILE__, array($exhibit, 'activate_plugin'));
register_deactivation_hook(__FILE__, array($exhibit, 'deactivate_plugin'));



/* ---------------------------------------------------------------------------
 * JavaScript Registration
 * --------------------------------------------------------------------------- */

if (!$guessurl = site_url())
	$guessurl = wp_guess_url();
$baseuri = $guessurl;
$wp_datapress_plugin_url = trailingslashit( get_bloginfo('wpurl') ).PLUGINDIR.'/datapress';    

wp_register_script( 'exhibit-api', 'http://projects.csail.mit.edu/datapress/exhibit-files/exhibit-api/exhibit-api.js', array(  ) );
wp_register_script( 'exhibit-chart', 'http://projects.csail.mit.edu/datapress/exhibit-files/exhibit-api/extensions/chart/chart-extension.js', array( 'exhibit-api' ) );
wp_register_script( 'exhibit-time', 'http://projects.csail.mit.edu/datapress/exhibit-files/exhibit-api/extensions/time/time-extension.js', array( 'exhibit-api' ) );

wp_register_script( 'dp-jquery', "$wp_datapress_plugin_url/js/jquery-1.3.2.min.js", array() );
wp_register_script( 'dp-jquery-ui', "$wp_datapress_plugin_url/js/jquery-ui-1.7.3.custom.min.js", array('dp-jquery') );
wp_register_script( 'dp-mce', "$wp_datapress_plugin_url/tinymce/jquery.tinymce.js", array('dp-jquery') );


wp_register_script( 'base64', "$wp_datapress_plugin_url/js/jquery.base64.js", array() );
wp_register_script( 'configurator', "$wp_datapress_plugin_url/configurator/configurator.js.php");

function datapress_ScriptsAction() {  
    if (is_admin()) { 	  
        wp_enqueue_script('jquery'); 
        wp_enqueue_script('wp_datapress_test_script', $wp_datapress_plugin_url.'/TEST.js', array('jquery')); 
    }
}


/* ---------------------------------------------------------------------------
 * Stylesheet Registration
 * --------------------------------------------------------------------------- */

wp_register_style( 'dp-configurator', "$wp_datapress_plugin_url/css/wpexhibit.css");
wp_register_style( 'dp-jquery', "$wp_datapress_plugin_url/js/jQueryUI1.7.3/smoothness/jquery-ui-1.7.3.custom.css");
wp_register_style( 'dp-template', "$wp_datapress_plugin_url/css/template.css");

include_once('facet_widget.php');
?>
