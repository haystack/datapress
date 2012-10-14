<?php
class InsertParrotableUrl {
    static function insert_url() {
        $url = urldecode($_POST['url']);
        if ($url) {
            global $wpdb; 
            $table = WpExhibitConfig::table_name(WpExhibitConfig::$PARROTABLE_URLS_TABLE_KEY);
            $wpdb->query($wpdb->prepare("INSERT INTO $table (url) VALUES (%s);", $url));
        }
    }
}
?>
