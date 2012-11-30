<?php
global $exhibits_to_show;

if (count($exhibits_to_show) == 1) {
   // if ((! $exhibits_to_show[0]->get('lightbox')) || (isset($lightboxed_exhibit))) {
        $google_map_api_key = get_option( 'google_map_api_key' );
        $plugin_dir = trailingslashit( get_bloginfo('wpurl') ).PLUGINDIR.'/datapress';    

        ?>
        <script src="http://projects.csail.mit.edu/datapress/exhibit-files/exhibit-api/exhibit-api.js?autoCreate=false" type="text/javascript"></script> 
        <script src="http://projects.csail.mit.edu/datapress/exhibit-files/exhibit-api/extensions/time/time-extension.js" type="text/javascript"></script>
        <script src="http://projects.csail.mit.edu/datapress/exhibit-files/exhibit-api/extensions/chart/chart-extension.js" type="text/javascript"></script>
        <link rel="stylesheet" type="text/css" href="<?php echo $plugin_dir ?>/exhibit.css" />

        <?php
        // Insert lense decoration css files for each lens decoration
        foreach ($exhibits_to_show[0]->get('lenses') as $lens) {
            if ($lens->get('decoration') != 'none') {
                ?>
                <link rel="stylesheet" type="text/css" href="<?php echo $plugin_dir ?>/css/<?php echo $lens->get('decoration') ?>.css" />
                <?php
            }
        }
        ?>

        <?php 
        if ($google_map_api_key != null) { 
            ?><script src="http://projects.csail.mit.edu/datapress/exhibit-files/exhibit-api/extensions/map/map-extension.js?gmapkey=<?php echo $google_map_api_key ?>"></script><?php
        }
        
        $css = $exhibits_to_show[0]->get('css');
        if ($css != NULL) {
    		?><link rel="stylesheet" href="<?php echo $css ?>" type="text/css" /><?php
    	}
    	
    	echo('<script type="text/javascript">');
    	include('head-start-exhibit.js.php');    
    	echo('</script>');
    //}
}

?>
