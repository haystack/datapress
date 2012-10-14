<?php
include_once("default-templates.php");

function show_template_editor_html() { 
	global $DPTEMPLATES;
	
	if (!$guessurl = site_url())
		$guessurl = wp_guess_url();
	$baseuri = $guessurl;
	$exhibituri = $baseuri . '/wp-content/plugins/datapress';
	$identifier = $_GET["identifier"];
	$repository = $_GET["repository"];
	?>
<div id="templateeditor">
<h1>Add a new <span id="template_name">Item</span></h1>
<div id="template_source"></div>
<form id="template-editor-form" action="javascript:return false;">
<table id="form_table">
	
</table>

<input type="hidden" value="save_data_template" name="action" />
<input id="save_btn" type="button" class="button savebutton" name="save" value="<?php echo attribute_escape( __( 'Save' ) ); ?>" />

</form>
</div>
<script>	    

	function postTemplate(e) {
		
		jQuery.post("<?php bloginfo('wpurl'); ?>/wp-admin/admin-ajax.php",
		            jQuery("#template-editor-form").serialize(),
			        function(data) {
						var win = window.dialogArguments || opener || parent || top;
						win.add_dataset_token_and_exit(data);
					});
					
	}
	
	jQuery(document).ready(function(){		    
		jQuery('#save_btn').bind("click", postTemplate);
	});

function get_template() {
<?php 
	if ($repository == "wibbit") {
?>
var datasource = "http://projects.csail.mit.edu/wibit/wiki/index.php?title=Special:GetTemplate/<?php echo $identifier ?>&callback=?";
<?php 
}
else {
?>
var datasource = "<?php echo $exhibituri ?>/data-editor/template-data.js.php?identifier=<?php echo $identifier ?>&jsoncallback=?";
<?php 
}
?>
    jQuery.getJSON(datasource, function(data) {
    	jQuery("#template_name").html(data.name);
		// Build the form
		for (var field in data.fields) {
			var spec = data.fields[field];
			jQuery("#form_table").append("<tr><th>" + spec.name + "</th><td><input style='width: 300px'	 name=\"" + spec.name + "\" /></td></tr>");
		}		
    });
}

	jQuery(function() {
		get_template();
    });
</script><?php 
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
function template_editor_iframe($content_func /* ... */) {
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

function show_datapress_template_editor() {
    wp_enqueue_script('dp-jquery');
    wp_enqueue_script('dp-jquery-ui');
	wp_enqueue_script('base64');
	wp_enqueue_style( 'global' );
	wp_enqueue_style( 'wp-admin' );
	wp_enqueue_style( 'colors' );
	wp_enqueue_style( 'media' );
	wp_enqueue_style( 'dp-template' );
	
	echo template_editor_iframe('show_template_editor_html');
	die();
}
?>
