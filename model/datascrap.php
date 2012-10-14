<?php
require_once('db-methods.php');

class DataScrap {
    // Used to save the exhibit in a DB
	protected $dbfields = array(
	    'id' => NULL,
    	'kind' => NULL,
	    'datascrap' => NULL,
	);
	
	static function getForPost($postid) {
        global $wpdb;
        $table = WpExhibitConfig::table_name(WpExhibitConfig::$DATASCRAPS_ASSOC_TABLE_KEY);
        $scrapid = $wpdb->get_var("SELECT datascrapid FROM $table WHERE postid=$postid ;");
        if (!($scrapid == 0)) {
    	 	$scrap= new DataScrap();
    		if (DbMethods::loadFromDatabase($scrap, $scrapid)) {
    			return $scrap;
    		}
        }
		return NULL;
	}
	
	function DataScrap() {
    }

	function getTableName() {
		return WpExhibitConfig::table_name(WpExhibitConfig::$DATASCRAPS_TABLE_KEY);
	}
	
	function getFormPrefix() {
		return WpExhibitConfig::$DATASCRAP_FORM_PREFIX;
	}
	
	function getLinkCaption() {
		return "DataScrap #" . $this->getId();
	}
	
	function afterDbLoad() {
	   $this->dbfields['datascrap'] = base64_decode($this->dbfields['datascrap']);
	}
	
	function getFields() {
	    return $this->fields;
	}
	
    function get($field, $nice=false) {
        if (array_key_exists($field, $this->dbfields)) {
            return $this->dbfields[$field];
		} else {
			if (! $nice) {
				die("Attempted to get a field that does not exist: $field.");							
			}
		}
	}
	
	function set($field, $value, $nice=false) {
		if (array_key_exists($field, $this->dbfields)) {
			$this->dbfields[$field] = $value;
		} else {
			if (! $nice) {
				die("Attempted to set a field that does not exist: $field.");				
			}
		}
	}
	
	function save() {
  		global $wpdb;

	    $table = $this->getTableName();
	    if ($this->dbfields['id'] == NULL) {
			// Do an insert
			$sql = "INSERT INTO $table (id, datascrap, kind) VALUES (%d, %s, %d);";
			$sql = $wpdb->prepare($sql, $this->dbfields['id'], base64_encode($this->dbfields['datascrap']), $this->dbfields['kind']);
		} else {
			// Do an update
			$sql = "UPDATE $table SET datascrap=%s, kind=%d WHERE id=%d";
			$sql = $wpdb->prepare($sql, base64_encode($this->dbfields['datascrap']), $this->dbfields['kind'], $this->dbfields['id']);
		}

		$result = $wpdb->query($sql);
		
		if ($this->dbfields['id'] == NULL) {
			// Get the ID of the insert and set it.
			$sql = "SELECT LAST_INSERT_ID();";
			$this->set('id', $wpdb->get_var($sql));
		}
	}

}
?>
