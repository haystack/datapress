<!-- ========~~~~~-------- begin DATAPRESS header --------~~~~~========  -->
<?php

error_reporting(E_ALL);
ini_set('display_errors', True);

/*
 * This file is run from within the <HEAD> element of a WordPress page load.
 * It determines what content to place in the head, and uses the helper methods
 * in head-lib.php to accomplish that.
 */
include('head-lib.php');

/*
 * The wp_query variable lets us access the data for the current post.
 */
global $wp_query;

/*
 * The lightboxed_exhibit contains an Exhibit if we're currently in a lightbox.
 */
global $lightboxed_exhibit;

/*
 * This is the helper library for putting things into the HEAD element.
 * The task is to determine when and what to put in.
 */
$datapressHead = new DatapressHead();

/*
 * We'll use this array to contain the exhibits we need to print info for.
 */
global $exhibits_to_show;
$exhibits_to_show = array();

/*
 * Currently Inside an Exhibit-Only Page
 * This is true if we're currently inside a lightbox;
 */
$currently_inside_exhibit_only_page = (isset($lightboxed_exhibit) && ($lightboxed_exhibit != NULL));

/* -------------------------------------
 * Step 1: Load the Exhibits we're interested in.
 * -------------------------------------
 */

if (isset($lightboxed_exhibit) && ($lightboxed_exhibit != NULL)) {
  /*
   * CASE 1: exhibit-only page (see: wp-exhibit-only.php)
   * 
   * We're in an exhibit-only page while executing this.
   * There's only one exhibit, so we just push it to the exhibits_to_show array.
   */
  array_push($exhibits_to_show, $lightboxed_exhibit);
} else {
  /*
   * CASE 2: WordPress page
   *
   * We're in a regular wordpress page, so we have to inspect the posts that
   * are being shown and add the exhibit for each post.
   */

  // Look at each post.
  if ($wp_query->posts > 0) {
    foreach($wp_query->posts as $post) {
      if ($post->datapress_exhibit) {
        array_push($exhibits_to_show, $post->datapress_exhibit);
      }
    }
  }	
}

/* -------------------------------------
 * Step 2: Print the appropriate data to the HEAD of the page.
 * -------------------------------------
 */

if (count($exhibits_to_show) == 0) {
  /*
   * CASE 1: No exhibits to show.
   *
   * Do nothing!
   */
} else if (count($exhibits_to_show) == 1) {
  /*
   * CASE 2: One exhibit to show.
   */

  $exhibit = $exhibits_to_show[0];
  if ($currently_inside_exhibit_only_page) {
    // We're showing the exhibit so boot it up!
    $datapressHead->print_exhibit_library_links();
    $datapressHead->print_exhibit_specific_links($exhibit);
    $datapressHead->print_exhibit_specific_datasources($exhibit);
    $datapressHead->print_exhibit_bootup_code();
  } else {
    // We're in a WordPress page.
    if ($exhibit->get('lightbox')) {
      // Show the lightbox preview? If so, just include the libraries
      $datapressHead->print_lightbox_library_links();
      // List the data links instead so others can scrape data sources
      $datapressHead->print_exhibit_specific_datasources($exhibit);
    } else {
      // Show the exhibit directly on the page.
      // Currently a NO-OP for the <HEAD> element, since this will happen
      // inside an IFRAME.
      //
      // TODO(daniel): Possibly include all the exhibit stuff here:
      	$datapressHead->print_exhibit_library_links();
      	$datapressHead->print_exhibit_specific_links($exhibit);
      	$datapressHead->print_exhibit_specific_datasources($exhibit);
	$datapressHead->print_exhibit_bootup_code();
      //
      // TODO(daniel): Make a corresponding change where the exhibit is
      // actually printed to remove the IFRAME tag **for only this case**
    }
  } 
} else {
  /*
   * CASE 3: More than one exhibit to show.
   *
   * When there is more than one exhibit to show, every exhibit is either a lightbox
   * preview or embedded in an iframe. So we only need to 1) decide whether to show the 
   * lightbox preview links and 2) print out the datasouce links for the benefit of web
   * scrapers.
   */

  $needLightboxLibrary = false;

  // Print out web scraping links.
  foreach ($exhibits_to_show as $exhibit) {
    $datapressHead->print_exhibit_specific_datasources($exhibit);
    if ($exhibit->get('lightbox', true)) {
      $needLightboxLibrary = true;
    }
  }

  // Maybe print the lightbox library
  if ($needLightboxLibrary) {
    $datapressHead->print_lightbox_library_links();
  }
}

?>
<!-- ========~~~~~-------- end DATAPRESS header --------~~~~~========  -->
