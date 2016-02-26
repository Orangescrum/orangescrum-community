<?php

/* 
 * Class for editing MySQL tables. Especially useful for migrations.
 */


namespace iRAP\TableCreator;


class TableEditor
{
    private $m_name; # The name of this table
    private $m_add_fields = array(); # array of DatabaseField objects that we are adding.
    private $m_remove_fields = array(); # array of DatabaseField objects that we are removing.
    private $m_remove_keys = array(); # array of indexes/keys we wish to remove.
   
    private $m_combined_keys = array(); # array of arrays that represent combined-keys.
    private $m_combined_unique_keys = array(); # array of arrays that represent unique combined keys
    
    private $m_mysqliConn; # the mysqli connection to the database.
    
    
    const ENGINE_INNODB = 'INNODB';
    const ENGINE_MYISAM = 'MYISAM';
    
    
    /**
     * 
     * @param Mys$mysqliConn
     * @param MySQLi $name - the name of the table
     * @param string $engine - the engine, e.g use one of this classes ENGINE constants
     * @param array $fields - optionally specify the array list of DatabaseField objects that this
     *                       table consists of. They can always be specified later with add_field 
     *                       functions.
     * @throws Exception
     */
    public function __construct($mysqliConn, $name)
    {
        $this->m_mysqliConn = $mysqliConn;
        $this->m_name = $name;
    }
    
        
    /**
     * Adds the specified fields to this table.
     * @param array $fields - an array of DatabaseField objects
     */
    public function addFields(array $fields)
    {
        foreach ($fields as $field)
        {
            $name = $field->getName();
            $addFields[$name] = $field; # we key by name to prevent duplicates!
        }
        
        $keysString = "";
        $fieldStrings = array();
        
        foreach ($addFields as $fieldName => $field)
        {
            $fieldStrings[] = $field->getFieldString();
                
            /* @var $field DatabaseField */
            if ($field->isPrimaryKey())
            {
                $errMsg = 'Do not set field to be primary key when adding fields. ' .
                          'Instead, use the changePrimaryKey method';
                throw new \Exception($errMsg);
            }
            elseif ($field->isKey())
            {
                if ($keysString !== "")
                {
                    $keysString .= ", ";
                }
                
                if ($field->isUnique())
                {
                    $keysString .= "UNIQUE ";
                }
                
                $keysString .= "KEY (`" . $fieldName . "`) ";
            }
        }
        
        $fieldsString = implode(", ", $fieldStrings);
        
        $fieldsString .= $keysString;
        
        $query = 
            "ALTER TABLE " . 
            "`" . $this->m_name . "` " . 
            "ADD " .
            "(" . $fieldsString . ") ";
        
        $result = $this->m_mysqliConn->query($query);
        
        if ($result !== TRUE)
        {
            throw new \Exception('Error creating table with query: ' . $query);
        }
    }
    
    
    /**
     * Adds the DatabaseField object to this table.
     * @param DatabaseField $field
     */
    public function removeField(String $fieldName)
    {
        $query = 
            "ALTER TABLE " . 
            '`' . $this->m_name . '` ' . 
            "DROP COLUMN `" . $field . "`";
        
        $result = $this->m_mysqliConn->query($query);
        return $result;
    }
    
    
    /**
     * Adds the DatabaseField object to this table.
     * @param array<String> $fields
     */
    public function removeFields(array $fields)
    {
        foreach ($fields as $fieldName)
        {
            $this->removeField($fieldName);
        }
    }
    
    
    /**
     * Remove a key from the database. This does not remove the field itself.
     * @param String $key - the name of the field that is currently a key.
     * @throws Exception
     */
    public function removeKey($key)
    {
        if (is_array($key))
        {
            $keyString = "(" . implode(',', $key) . ")";
        }
        else
        {
            $keyString = "`" . $key . "`";
        }
        
        $query = 
            "ALTER TABLE " . 
            '`' . $this->m_name . '` ' . 
            "DROP INDEX " . $keyString;
        
        $result = $this->m_mysqliConn->query($query);
        return $result;
    }
    
    
    /**
     * Given a list of $keys, this adds each one as a key. A key can be a field name or an array
     * of field names which represents a combined-key.
     * @param array $keys - array of field names or arrays that represent a combined-key
     * @return void
     */
    public function addKeys(array $keys, $unique=false)
    {
        foreach ($keys as $key)
        {
            $this->addKey($key, $unique);
        }
    }
    
    
    /**
     * Adds a key to the table. This is NOT for primary keys.
     * @param mixed $key - String representing the field name to act as key, or array of field names
     *               for a single "combined" key.
     * @param bool $unique - optionally set to true to make this a unique key.
     * @return void.
     */
    public function addKey($key, $unique=false)
    {
        if (is_array($key))
        {
            $keyString = implode(",", $key);
        }
        else
        {
            $keyString = "`" . $key . "`";
        }
        
        $uniqueString = "";
        
        if ($unique)
        {
            $uniqueString = "UNIQUE";
        }
        
        $query = "ALTER TABLE `" . $this->m_name . "` " .
                 "ADD " . $uniqueString . " KEY(" . $keyString . ")";
        
        $result = $this->m_mysqliConn->query($query);
        return $result;
    }
    
    
    
    /**
     * Change the primary key to something else (you can only have one primary key, although it
     * can be made up of multiple fields).
     * @param mixed $newPrimary - string fieldname or array of fieldnames that make up a combined
     *                            primary key.
     */
    public function changePrimaryKey($newPrimary)
    {
        if (is_array($newPrimary))
        {
            $primaryKeyString = "(" . implode(",", $key) . ")";
        }
        else
        {
            $primaryKeyString = "(`" . $newPrimary . "`)";
        }
        
        $query = 
            "ALTER TABLE `" . $this->m_name . "` " .
            "DROP PRIMARY KEY, ADD PRIMARY KEY " . $primaryKeyString;
        
        $result = $this->m_mysqliConn->query($query);
        return $result;
    }
    
    
    /**
     * Change the tables engine to something else. Please make use of this classes constants
     * when providing the engine parameter.
     * @param String $engine - the name of the engine to change to.
     * @return type
     * @throws Exception
     */
    public function changeEngine($engine)
    {
        $allowed_engines = array(
            self::ENGINE_INNODB,
            self::ENGINE_MYISAM
        );
        
        if (!in_array($engine, $allowed_engines))
        {
            throw new \Exception('Unrecognized engine: ' . $engine);
        }
        
        $query = 
            "ALTER TABLE `" . $this->m_name . "` " .
            "ENGINE=" . $engine;
        
        $result = $this->m_mysqliConn->query($query);
        return $result;
    }
}
