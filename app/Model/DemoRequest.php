<?php

App::uses('AppModel', 'Model');

/**
 * DemoRequest Model
 *
 */
class DemoRequest extends AppModel
{
    /**
     * Display field
     *
     * @var string
     */
    public $displayField = 'name';
    
    /**
     * Validation rules
     *
     * @var array
     */
    public $validate = array(
        'name' => array(
            'notEmpty' => array(
                'rule' => array('notEmpty'),
            ),
        ),
        'message' => array(
            'notEmpty' => array(
                'rule' => array('notEmpty'),
            ),
        ),
        'email' => array(
            'email' => array(
                'rule' => array('email'),
            ),
            'notEmpty' => array(
                'rule' => array('notEmpty'),
            ),
        ),
    );
}
