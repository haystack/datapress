<!-- ========~~~~~-------- begin DATAPRESS header --------~~~~~========  -->
<?php
global $exhibits_to_show;
global $lightboxed_exhibit;
global $wp_query;

/*
 * Figure out which exhibits to show
 */
$exhibits_to_show = array();
if (isset($lightboxed_exhibit) && ($lightboxed_exhibit != NULL)) {
    // We're inside a lightbox
    array_push($exhibits_to_show, $lightboxed_exhibit);
}
else {
    // We're inside a wordpress page
	if ($wp_query->posts > 0) {
        foreach($wp_query->posts as $post) {
            if ($post->datapress_exhibit) {
                array_push($exhibits_to_show, $post->datapress_exhibit);
            }
        }
    }	
}

/*
 * Now determine which sub-parts to include
 */
if (isset($lightboxed_exhibit) && ($lightboxed_exhibit != NULL)) {
    // Weire inside the lightbox
    	include('head-exhibit.php');
    	include('head-datasources.php');
}
else {
    // We're not inside the lightbox
	include('head-datasources.php');
    include('head-lightbox-library.php');
    // Taken out because now we're in an iframe. 
    // if (count($exhibits_to_show) == 1) {
    //  include('head-exhibit.php');
    // }
}
?>

<!-- ========~~~~~-------- end DATAPRESS header --------~~~~~========  -->
