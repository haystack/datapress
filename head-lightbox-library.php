<?php
	if (!$guessurl = site_url())
    	$guessurl = wp_guess_url();
        $baseuri = $guessurl;
        $exhibituri = $baseuri . '/wp-content/plugins/datapress';
 
    $linkstring = "";
    $overlaystring = "";
    foreach ($exhibits_to_show as $exhibit) {
        $exhibitid = $exhibit->get('id');
        $linkstring .= "$('a.exhibit_link_$exhibitid').fancybox({ 'padding':0, 'frameWidth': $(window).width()*.9, 'frameHeight': $(window).height()*.9 });\n";
        $overlaystring .= "cover = $('div[id=cover_$exhibitid]');\n";
        $overlaystring .= "teaser = $('div[id=teaser_$exhibitid]');\n";
        $overlaystring .= "teaserpos = teaser.position();\n";
        $overlaystring .= "if (teaserpos) {\n";
        $overlaystring .= "cover.css('top', teaserpos.top);\n";
        $overlaystring .= "cover.css('left', teaserpos.left);\n";
        $overlaystring .= "cover.css('height', teaser.height());\n";
        $overlaystring .= "cover.css('width', teaser.width());\n";
        $overlaystring .= "}\n";

    }
?>
<script src="<?php echo $exhibituri ?>/js/jquery-1.3.2.min.js" type="text/javascript"></script>
<script src="<?php echo $exhibituri ?>/js/jquery.fancybox-1.2.1.pack.js" type="text/javascript"></script> 
<link rel="stylesheet" href="<?php echo $exhibituri ?>/css/jquery.fancybox.css" type="text/css" media="screen"/>
    
<script type="text/javascript">
$(document).ready(function() { 
    <?php echo $linkstring ?>
});
</script>

<!-- Lightbox teaser overlay stuff -->
<link rel="stylesheet" type="text/css" href="<?php echo $exhibituri ?>/css/overlay.css" />
<script type="text/javascript">
    $(document).ready(function() {
        var cover;
        var teaser;
        var teaserpos;
        <?php echo $overlaystring ?>
    });
</script>
<!-- End lightbox teaser overlay stuff -->
