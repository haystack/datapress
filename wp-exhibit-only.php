<?php
	ob_start();
      $root = dirname(dirname(dirname(dirname(__FILE__))));
      if (file_exists($root.'/wp-load.php')) {
          // WP 2.6
          require_once($root.'/wp-load.php');
      } else {
          // Before 2.6
          require_once($root.'/wp-config.php');
      }
	ob_end_clean(); //Ensure we don't have output from other plugins.
	header('Content-Type: text/html; charset='.get_option('blog_charset'));

    /*
     * Load up the lightboxed exhibit
     */
    global $lightboxed_exhibit;
    $exhibitid = $_GET['exhibitid'];
    if (isset($exhibitid) && ($exhibitid != NULL)) {
        // We are going to try to load just this one
        $lightboxed_exhibit = new WpPostExhibit();
        DbMethods::loadFromDatabase($lightboxed_exhibit, $exhibitid);
    }
    $currentview = $_GET['currentview'];
    $postid = $_GET['postid'];
    $exhibit_html = $exhibit_html = WpExhibitHtmlBuilder::get_exhibit_html($lightboxed_exhibit, $currentview, $postid, true);
    //echo $exhibit_html;
    //die;
?>
<html>
<head>
<?php require('head.php'); ?>
</head>
<body>
<?php echo($exhibit_html); ?>
</body>
</html>
