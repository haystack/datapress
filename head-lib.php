<?php
global $exhibits_to_show;

class DatapressHead {

  function plugin_dir() {
    $plugin_dir = trailingslashit( get_bloginfo('wpurl') ).PLUGINDIR.'/datapress';    
    return $plugin_dir;
  }

  function print_exhibit_library_links() {
    ?>
    <link rel="stylesheet" type="text/css" href="<?php echo $this->plugin_dir() ?>/exhibit.css" />
    <script src="http://static.simile.mit.edu/exhibit/api-2.0/exhibit-api.js?autoCreate=false " type="text/javascript"></script> 
    <script src="http://projects.csail.mit.edu/datapress/exhibit-files/exhibit-api/extensions/time/time-extension.js" type="text/javascript"></script>
    <script src="http://projects.csail.mit.edu/datapress/exhibit-files/exhibit-api/extensions/chart/chart-extension.js" type="text/javascript"></script>
    <?php
    // TODO(eob): Migrate this to GM3, which doesn't need a map key anymore.
    $google_map_api_key = get_option( 'google_map_api_key' );
    if ($google_map_api_key != null) { 
      ?><script src="http://projects.csail.mit.edu/datapress/exhibit-files/exhibit-api/extensions/map/map-extension.js?gmapkey=<?php echo $google_map_api_key ?>"></script>script><?php
    }
  }

  function print_exhibit_specific_links($exhibit) {

    // If the exhibit has a lens that contains a 
    // prepacked decoration file, print it.
    foreach ($exhibit->get('lenses') as $lens) {
      if ($lens->get('decoration') != 'none') {
        ?>
        <link rel="stylesheet" type="text/css" href="<?php echo $this->plugin_dir() ?>/css/<?php echo $lens->get('decoration') ?>.css" />
        <?php
      }
    }

    // If the exhibit configuration contains a link 
    // to a specific CSS file, print it.
    $css = $exhibit->get('css');
    if ($css != NULL) {
      ?><link rel="stylesheet" href="<?php echo $css ?>" type="text/css" /><?php
    }
  }

  function print_exhibit_bootup_code() {
   	echo('<script type="text/javascript">');
    include('head-start-exhibit.js.php');    
   	echo('</script>');
  }

  function print_exhibit_specific_datasources($exhibit) {
    echo("<!-- [BEGIN] Datasource Link Set -->\n");
    foreach($exhibit->get('datasources') as $datasource) {
      echo($datasource->htmlContent() . "\n");
    }
    echo("<!-- [END] Datasource Link Set -->\n");
  }

  function print_lightbox_library_links() {
    include_once('head-lightbox-library.php');
  }
 
}


?>
