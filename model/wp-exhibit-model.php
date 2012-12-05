<?php
abstract class WpExhibitModel {
	protected $fields = array(); /* This should be overridden by the child class */	
	abstract function getTableName();
	abstract function getFormPrefix();
	abstract function getLinkCaption();
    abstract function getEditInfo();
    
 	function WpExhibitModel($opts = NULL) {
 		$this->id = NULL;
		
		if (isset($opts['formid'])) {
			$formid = $opts['formid'];
			WpExhibitModel::loadFromForm($this, $formid);	
		}
	}

	function getId() {
		return $this->id;
	}
	
	function getFields() {
		return $this->fields;
	}
	
	function get($field, $nice=false) {
		if (array_key_exists($field, $this->fields)) {
			return $this->fields[$field];
		}
		else {
			if (! $nice) {
				die("Attempted to get a field that does not exist: $field.");							
      } else {
        return null;
      }
		}
	}
	
	function set($field, $value, $nice=false) {
		if (array_key_exists($field, $this->fields)) {
			$this->fields[$field] = $value;
		}
		else {
			if (! $nice) {
				die("Attempted to set a field that does not exist: $field.");				
			}
		}
	}

	static function formVar($prefix, $id, $var) {
		$name = $prefix . '_' . $id . '_' . $var;
		return $_POST["$name"];
	}
	
	static function formVars($prefix, $id) {
		$result = array();
		$start = $prefix . '_' . $id . '_';
		
		foreach ($_POST as $field => $value) {
			if (strpos($field, $start) === 0) {
				$variableName = substr($field, strlen($start));
				$result[$variableName] = $value;
			}
		}
		return $result;
	}
	
	static function findFormObjectsByPrefix($prefix) {
		$ids = array();
		foreach ($_POST as $field => $value) {			
			if (preg_match("/^" . $prefix . "_(\d+)_kind$/", $field, $id) > 0) {

				array_push($ids, $id[1]);
			}			
		}
		return $ids;
	}
	
	static function findFormObjectValue($name) {
		foreach ($_POST as $field => $value) {			
			if (preg_match("/^" . $name . "$/", $field, $id) > 0) {
                return $value;
			}			
		}
        return null;
	}
	
	static function loadFromForm($object, $formid) {
		$prefix = $object->getFormPrefix();
				
		if (WpExhibitModel::formVar($prefix, $formid, 'kind') != NULL) {
			$values = WpExhibitModel::formVars($prefix, $formid);
			if (sizeof($values) === 0) {
				return false;
			}
			foreach ($values as $field => $value) {
			    /*
			     * Note: we have to base64-Decode the values
			     */
				$object->set($field, base64_decode($value), true);
			}
			return true;
		}
		return false;
	}
	
	function getAddLink($list) {
		$caption = $this->getLinkCaption();
		$prefix = $this->getFormPrefix();
        $fields = "{";
        $added = 0;
		foreach ($this->fields as $key => $val) {
			if (($val != NULL) && ($val != '')) {
			    $base64val = base64_encode($val);
				$fields .= "$key: '$base64val',";
				$added++;
			}
		}
		if ($added > 0) {
			$fields = substr($fields, 0, -1);
		}
		$fields .= "}";    	
		$editinfo= " {" . $this->getEditInfo() . "}";  
		
		// Calling
		// function addExhibitElementLink(listId, caption, prefix, fields, editinfo, alreadyBase64Encoded);
		$ret =    "addExhibitElementLink('$list', '$caption', '$prefix', $fields, $editinfo, 1);";
		return $ret;
	}
}

require_once('datasource.php');
require_once('view.php');
require_once('lens.php');
require_once('exhibit.php');
require_once('facet.php');
?>
