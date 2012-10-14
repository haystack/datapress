<?php
function show_template_browser_html() { 
	if (!$guessurl = site_url())
		$guessurl = wp_guess_url();
	$baseuri = $guessurl;
	$exhibituri = $baseuri . '/wp-content/plugins/datapress';
?>
<div id="templatebrowser">
<h1>Please Select a Data Template</h1>
<div id="template_source">
    <span class="template_source_name">Template Source: </span>
    <select id="template_source_select">
        <option selected value="datapress">Built-In Templates</option>
        <option value="smw">Semantic MediaWiki Templates</option>
    </select>
</div>
<div id="template_items">
    <ul id="templates">
        <li>Temp Item</li>
    </ul>
</div>

</div>
<script>	    

function show_source() {
	var name = jQuery("#template_source_select").val();
	var datasource = "<?php echo $exhibituri ?>/data-editor/default-templates.js.php?jsoncallback=?";
	var repository = "local";
	    
    if (name == "smw") {
		datasource = "http://projects.csail.mit.edu/wibit/wiki/index.php?title=Special:JSONTemplates&callback=?";		
		repository = "wibbit";	
	}
    jQuery.getJSON(datasource, function(data) {
        jQuery("#templates").html("");
        for (var i=0; i<data.length; i++) {
        	jQuery("#templates").append("<li><a href=\"<?php echo wp_guess_url() ?>/wp-admin/admin-ajax.php?repository=" + repository + "&identifier=" + data[i].identifier + "&action=template_editor&TB_iframe=true&width=640&height=673\">" + data[i].name + "</a></li>");        
        }
    });
}

	jQuery(function() {
        show_source();
        jQuery("#template_source_select").change(function () {
			show_source();
        });
          
    });
</script>
<?php 
}

/**
 * {@internal Missing Short Description}}
 *
 * Wrap iframe content (produced by $content_func) in a doctype, html head/body
 * etc any additional function args will be passed to content_func.
 *
 * @since unknown
 *
 * @param unknown_type $content_func
 */
function template_browser_iframe($content_func /* ... */) {
	if (!$guessurl = site_url())
		$guessurl = wp_guess_url();
	$baseuri = $guessurl;
	$exhibituri = $baseuri . '/wp-content/plugins/datapress';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php do_action('admin_xml_ns'); ?> <?php language_attributes(); ?>>
<head>
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php echo get_option('blog_charset'); ?>" />
<title><?php bloginfo('name') ?> &rsaquo; <?php _e('Uploads'); ?> &#8212; <?php _e('WordPress'); ?></title>
<?php
wp_enqueue_style( 'global' );
wp_enqueue_style( 'wp-admin' );
wp_enqueue_style( 'colors' );
if ( 0 === strpos( $content_func, 'media' ) ) {
	wp_enqueue_style( 'media' );
}

do_action('admin_print_styles');
do_action('admin_print_scripts');
do_action('admin_head');
if ( is_string($content_func) )
	do_action( "admin_head_{$content_func}" );
?>
</head>
<body<?php if ( isset($GLOBALS['body_id']) ) echo ' id="' . $GLOBALS['body_id'] . '"'; ?>>
<?php
	$args = func_get_args();
	$args = array_slice($args, 1);
	call_user_func_array($content_func, $args);
?>
</body>
</html>
<?php
}

function show_datapress_template_browser() {
    wp_enqueue_script('dp-jquery');
    wp_enqueue_script('dp-jquery-ui');
	wp_enqueue_script('base64');
	wp_enqueue_style( 'global' );
	wp_enqueue_style( 'wp-admin' );
	wp_enqueue_style( 'colors' );
	wp_enqueue_style( 'media' );
	wp_enqueue_style( 'dp-template' );
	
	echo template_browser_iframe('show_template_browser_html');
	die();
}
?>
