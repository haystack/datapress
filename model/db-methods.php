<?php

class DbMethods {
    static function loadFromDatabase($object, $id, $keyfield='id') {
        global $wpdb;
        $table = $object->getTableName();
        $select = "SELECT * FROM $table WHERE $keyfield=%d;";
        $select = $wpdb->prepare($select, $id);
        $row = $wpdb->get_row($select, ARRAY_A);
        if ($row != NULL) {
            foreach ($row as $field => $value) {
                $value = stripslashes($value);
                $object->set($field, $value);
            }
            $object->id = $row['id'];
            $object->afterDbLoad();
            return true;
        }
        return false;
    }

    // Returns the id of the object if it was created, or NULL otherwise.
    // b0rken, since we can't sql-escape a variable number of arguments.
    static function save($object, $idfield='id') {
		global $wpdb;
		$table = $object->getTableName();
        $fields = $object->getFields();
	 	$sql = NULL;
        $sqlargs = array();
		if ($fields[$idfield] == NULL) {
			// Do an insert
			$sql = "INSERT INTO $table (";
			foreach ($fields as $field => $value) {
			    if ($field != $idfield) {
    				$sql = $sql . "$field,";
    			}
			}

			// Strip off the trailing comma
			$sql = substr($sql, 0, -1);
			$sql = $sql . ") VALUES (";

			foreach ($fields as $field => $value) {
				if ($field != $idfield) {
				    $sql = $sql . preparetype($value) . ",";
                    array_push($sqlargs, $value);
	    		}
			}
			// Strip off the trailing comma 
			$sql = substr($sql, 0, -1);
			$sql = $sql . ");";
		} else {
			$id = $fields[$idfield];
			// Do an update
			$sql = "UPDATE $table SET ";
			foreach ($fields as $field => $value) {
			    if ($field != $idfield) {
			        $sql = $sql . "$field=" . preparetype($value) . ",";
                    array_push($sqlargs, $value);
	    		}
			}
			// Strip off the trailing comma
			$sql = substr($sql, 0, -1);
			$sql = $sql . " WHERE $idfield=%d;";
			array_push($sqlargs, $id);
		}
		$sql = $wpdb->prepare($sql, $sqlargs);
		$result = $wpdb->query($sql);
		if ($fields[$idfield] == NULL) {
			// Get the ID of the insert and set it.
			$sql = "SELECT LAST_INSERT_ID()";
			$id = $wpdb->get_var($sql);
			return $id;
		}
        return NULL;		
	}
	
	static protected function preparetype($var) {
	if (is_numeric($value)) {
			return "%d";
		} else {
			return "%s";
		}
    }
}

?>
