<?php
function show_datapress_html() { 
	if (!$guessurl = site_url())
		$guessurl = wp_guess_url();
	$baseuri = $guessurl;
	$exhibituri = $baseuri . '/wp-content/plugins/datapress';
	
	/* -------------------------------------------------
	 * Load up the exhibit if it exists
	 * ------------------------------------------------- */
	$exhibitID = $_GET['exhibitid'];
	$exhibitConfig = NULL;
	if ($exhibitID != NULL) {
		// See if we know about any data sources associated with this item.
		$exhibitConfig = new WpPostExhibit();
		$ex_success = DbMethods::loadFromDatabase($exhibitConfig, $exhibitID);
		if (! $ex_success) {
			$exhibitConfig = NULL;
		}
	}
?>
	<form id="exhibit-config-form" action="javascript:return false;">
	<div id="exhibit-input">
		<div class="inside">

		  <div id="exhibit-input-container">
			<ul id="ex-tabs" class="outer-tabs">
				<li class="ui-state-default ui-corner-top ui-tabs-selected ui-state-active"><a href="#exhibit-data"><span>Add Data</span></a></li>
				<li class="spacer">&gt;</li>
				<li class="ui-state-default ui-corner-top wp-no-js-hidden"><a href="#exhibit-views"><span>Add Visualizations</span></a></li>
				<li class="spacer">&gt;</li>
				<li class="ui-state-default ui-corner-top wp-no-js-hidden"><a href="#exhibit-facets" ><span>Add Facets</span></a></li>
				<li class="spacer">&gt;</li>
	    		<li class="ui-state-default ui-corner-top wp-no-js-hidden"><a href="#exhibit-display" ><span>Configure Display</span></a></li>
				<li class="spacer">&gt;</li>
				<li class="ui-state-default ui-corner-top wp-no-js-hidden" ><a href="#exhibit-lenses"><span>Lenses (Advanced)</span></a></li>
			</ul>

			<div id="exhibit-data" class="outer-tabs-panel ui-tabs-hide">
				<?php include("exhibit-inputbox-data.php") ?>
			</div>
			<div id="exhibit-views" class="outer-tabs-panel ui-tabs-hide">
                 <?php include("exhibit-inputbox-views.php") ?>
			</div>
			<div id="exhibit-lenses" class="outer-tabs-panel ui-tabs-hide">
				<?php include("exhibit-inputbox-lenses.php") ?>
			</div>
			<div id="exhibit-facets" class="outer-tabs-panel ui-tabs-hide">
				<?php include("exhibit-inputbox-facets.php") ?>
			</div>
			<div id="exhibit-display" class="outer-tabs-panel ui-tabs-hide">
				<?php include("exhibit-inputbox-display.php") ?>
			</div>
		  </div>

		</div>

		  <p align="right">
			<input type="hidden" value="<?php echo $exhibitID ?>" name="exhibitid" />
			<input type="hidden" value="save_exhibit_configuration" name="action" />
			<input id="save_btn" type="button" class="button savebutton" name="save" value="<?php echo attribute_escape( __( 'Save' ) ); ?>" />
		  </p>
     </div>
	</form>
	<script>	    
	    jQuery(function() {
    	    jQuery("#exhibit-input-container").tabs();
    	});
		jQuery(document).ready(function(){		    
			function postExhibit(e) {
				var paste_exhibit = false;
				var paste_footnotes = false;
				
				if (e.target.name == "save_insert") {
					paste_exhibit = true;
				}
				if (e.target.name == "save_insert_footnotes") {
					paste_exhibit = true;
					paste_footnotes = true;
				}

				jQuery.post("<?php bloginfo('wpurl'); ?>/wp-admin/admin-ajax.php",
				            jQuery("#exhibit-config-form").serialize(),
					        function(data) {
								if(geocodeFields.length != 0) {
									for(key in geocodeFields) {
										var itemProps = getItemProps(geocodeFields[key]);
										//these are the arrays to post to wp-exhibit-geocode.php
										var addresses = Array();
										var datumIds = Array();
										var i = 0;
										for(key2 in itemProps) {
											addresses[i] = itemProps[key2];
											datumIds[i] = key2;
											i++;
										}
										try {
										jQuery.post(<?php echo("'$exhibituri/wp-exhibit-geocode.php'"); ?>,
										{'exhibitid': data, 'datumids[]': datumIds, 'addresses[]': addresses, 'addressField': geocodeFields[key]});
										} catch(e) {
											console.log(e);
										}
									}
								}
								var win = window.dialogArguments || opener || parent || top;
								win.set_post_exhibit(data);
								win.add_exhibit_token_and_exit();

							});

			}
			
			jQuery('#save_btn').bind("click", postExhibit);
			jQuery('#save_insert_btn').bind("click", postExhibit);
			jQuery('#save_insert_footnotes_btn').bind("click", postExhibit);
			
			jQuery('#ex-tabs').tabs({
                select: function(event, ui) {
                    alert('selected');
                    return true;
                }    
			});
			remove_callbacks = new Array();
			db = Exhibit.Database.create();
				
			ex_load_links();
		});

		//print item properties (for debugging)
		function printItemProps(propertyName) {
			var props = getItemProps(propertyName);
			for(key in props) {
				alert(key + "->" + props[key]);
			}
		}

		function getItemProps(propertyName) {
			var ret = new Array();
			var items = db.getAllItems();
			items.visit(function(item) {
				var obj = db.getObject(item, propertyName);
				if(obj != null) {
					ret[item] = obj;
				}
			});
			return ret;
		}
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
function datapress_iframe($content_func /* ... */) {
	
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

wp_enqueue_style("dp-jquery");

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

function show_datapress_configurator() {
    wp_enqueue_script('exhibit-api');
    wp_enqueue_script('dp-jquery');
    wp_enqueue_script('dp-jquery-ui');
    wp_enqueue_script('dp-mce');
	wp_enqueue_script('configurator');	
	wp_enqueue_script('base64');
	wp_enqueue_style( 'global' );
	wp_enqueue_style( 'wp-admin' );
	wp_enqueue_style( 'colors' );
	wp_enqueue_style('dp-configurator');
	wp_enqueue_style( 'media' );
	
	echo datapress_iframe('show_datapress_html');
	die();
}
?>
