<?php

class TypeCompany extends AppModel {

    var $name = 'TypeCompany';
    
    /**
    * Getting selected task types
    * 
    * @method getSelTypes
    * @author Orangescrum
    * @return
    * @copyright (c) Aug/2014, Andolsoft Pvt Ltd.
    */
    function getSelTypes() {
	return $this->find("list", array("conditions" => array('TypeCompany.company_id' =>  SES_COMP ), 'fields' => array('TypeCompany.id', 'TypeCompany.type_id')));
    }
    
    /**
    * Getting all task types
    * 
    * @method getTypes
    * @author Orangescrum
    * @return
    * @copyright (c) Aug/2014, Andolsoft Pvt Ltd.
    */
    function getTypes() {
	return $this->find("list", array("conditions" => array('TypeCompany.company_id' =>  SES_COMP )));
    }

}

?>