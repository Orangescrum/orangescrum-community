<?php
class CaseFileDrive extends AppModel {

    var $name = 'CaseFileDrive';

    function getFileDriveInfo($conditions = NULL, $fields = array(), $limit = NULL, $offset = NULL, $order = array(), $isJoin = 1, $type = 'all') {
	if ($isJoin == 0)
	    $this->recursive = -1;
	return $this->find($type, array('conditions' => $conditions, 'fields' => $fields, 'limit' => $limit, 'offset' => $offset, 'order' => $order));
    }
    
    function deleteRows($conditions = NULL){
	return $this->deleteAll($conditions,false);
    }

}
?>