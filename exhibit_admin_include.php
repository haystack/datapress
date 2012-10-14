<?php
	if (!$guessurl = site_url())
    	$guessurl = wp_guess_url();
    $exhibituri = $guessurl . '/wp-content/plugins/datapress';
?>
<script type="text/javascript" src="<?php echo $exhibituri ?>/admin_javascript.js.php"></script>
<script src="<?php echo $exhibituri ?>/js/jquery.contextMenu.js" type="text/javascript"></script>
