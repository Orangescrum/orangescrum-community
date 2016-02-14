<?php
class Company extends AppModel
{
    public $name = 'Company';
    
    
    function getCompanyFields($condition = array(), $fields = array()) 
    {
        $this->recursive = -1;
        return $this->find(
            'first', 
            array('conditions' => $condition, 'fields' => $fields)
        );
    }
    
    
    public function beforeSave($options = array()) 
    {
        if (trim($this->data['Company']['name'])) 
        {
            $this->data['Company']['name'] = htmlentities(strip_tags($this->data['Company']['name']));
        }
        
        if (trim($this->data['Company']['website'])) 
        {
            $this->data['Company']['website'] = htmlentities(strip_tags($this->data['Company']['website']));
        }
        
        if (trim($this->data['Company']['contact_phone'])) 
        {
            $this->data['Company']['contact_phone'] = htmlentities(strip_tags($this->data['Company']['contact_phone']));
        }
    }
}
