<?php

/* 
 * This class is useful for creating new mysql tables from scratch. If you want 
 * to edit a table, please use the TableEditor instead. 
 * Please remember to invoke the run() method, as most of the methods are just 
 * for configuration and do not execute until run() is called.
 */

namespace iRAP\TableCreator;


class TableCreator
{
    private $m_name; # The name of this table
    private $m_fields = array(); # DatabaseField objects table consists of.
    private $m_engine;
    private $m_combinedKeys = array(); # multi-column keys
    private $m_combinedUniqueKeys = array(); # unique multi-column keys
    private $m_combinedPrimaryKey;
    private $m_foreignKeys = array();
    private $m_mysqliConn; # the mysqli connection to the database.
    private $m_charSet = null; # if left null, will utilize the db default.
    
    const ENGINE_INNODB = 'INNODB';
    const ENGINE_MYISAM = 'MYISAM';
    
    
    /**
     * 
     * @param Mys$mysqliConn
     * @param MySQLi $name - the name of the table
     * @param string $engine - the engine, e.g use one of this classes ENGINE 
     *                         constants
     * @param array $fields - optionally specify the list of DatabaseField
                              objects that this table consists of. They can 
     *                        always be specified later with addField functions.
     * @throws Exception
     */
    public function __construct(\mysqli $conn,
                                $name, 
                                $engine="INNODB", 
                                array $fields = array())
    {
        $this->m_mysqliConn = $conn;
        
        $allowed_engines = array(self::ENGINE_INNODB, self::ENGINE_MYISAM);
        
        if (!in_array($engine, $allowed_engines))
        {
            throw new \Exception('table [' . $name . '] engine must be one ' .
                                 'of the allowed types.');
        }
        
        $this->m_name = $name;
        $this->m_engine = $engine;
        
        $this->addFields($fields);
    }
    
    
    /**
     * Adds the specified fields to this table.
     * @param array $fields - an array of DatabaseField objects
     */
    public function addFields(array $fields)
    {
        foreach ($fields as $field)
        {
            $this->addField($field);
        }
    }
    
    
    /**
     * Adds the DatabaseField object to this table.
     * @param DatabaseField $field
     */
    public function addField(DatabaseField $field)
    {
        $name = $field->getName();
        $this->m_fields[$name] = $field; # we key by name to prevent duplicates!
    }
    
    
    /**
     * Given a list of $keys, this adds each one as a key. A key can be a field
     * name or an array of field names which represents a combined-key.
     * @param array $keys - array of field names or arrays that represent a 
     *                      combined-key
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
     * @param mixed $key - string representing the field name to act as key, 
     *                     or array of field names for a single "combined" key.
     * @param bool $unique - optionally set to true to make this a unique key.
     * @return void.
     */
    public function addKey($key, $unique=false)
    {
        if (is_array($key))
        {
            if ($this->hasFields($key))
            {
                if ($unique)
                {
                    $this->m_combinedKeys[] = $key;
                }
                else
                {
                    $this->m_combinedUniqueKeys[] = $key;
                }
            }
            else 
            {
                $err_msg = 
                    'Table Creator: one or more fields in combined key not ' .
                    'found in table [' . $this->m_name . '] with fields: ' . 
                    print_r($this->m_fields, true) . PHP_EOL .
                    'when adding key: ' . print_r($key, true);

                throw new \Exception($err_msg);
            }
        }
        else
        {
            if (isset($this->m_fields[$key]))
            {
                $this->m_fields[$key]->setKey($unique);
            }
            else
            {
                $err_msg = '[' . $key . '] field not found in table ' .
                           '[' . $this->m_name . '] when adding a key';
                
                throw new \Exception($err_msg);
            }
        }
    }
    
    
    /**
     * Add a foreign key to the table
     * @param string $table_column - the column of this table that references 
     *                               another
     * @param string $reference_table - the table that we are referencing
     * @param string $reference_column - the name of column that should contain 
     *                                   the same value as the 
     *                                   value within this tables $table_column
     * @param bool $delete_cascade - override to true to have deletion cascade 
     *                               to child tables
     * @param bool $update_cascade - override to true to have updates carry 
     *                               over to child tables
     */
    public function addForeignKey($table_column, 
                                  $reference_table, 
                                  $reference_column, 
                                  $delete_cascade = false, 
                                  $update_cascade = false)
    {
        $foreignKey = new \stdClass();
        $foreignKey->table_column     = $table_column;
        $foreignKey->reference_table  = $reference_table;
        $foreignKey->reference_column = $reference_column;
        $foreignKey->delete_cascade   = $delete_cascade;
        $foreignKey->update_cascade   = $update_cascade;
        
        $this->m_foreignKeys[] = $foreignKey;
    }
    
    
    
    /**
     * After having configured evertying, this actually creates the query and 
     * executes it to create the table.
     * @param void
     * @return void
     */
    public function run()
    {
        $keysString = "";
        $primaryKeyString = "";
        $fieldStrings = array();
        
        foreach ($this->m_fields as $field_name => $field)
        {
            $fieldStrings[] = $field->getFieldString();
                
            /* @var $field DatabaseField */
            if ($field->isPrimaryKey())
            {
                if ($primaryKeyString != "")
                {
                    throw new \Exception('Table Creator: you appear to have ' .
                                         'multiple primary keys');
                }
                
                $primaryKeyString = "PRIMARY KEY (" . $field_name . ")";
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
                
                $keysString .= "KEY (`" . $field_name . "`) ";
            }
        }
        
        $fieldsString = implode(", ", $fieldStrings);
        
        
        # Add combined keys.
        foreach ($this->m_combinedKeys as $key)
        {
            if ($keysString !== "")
            {
                $keysString .= ", ";
            }

            $keysString .= "KEY (" . implode(",", $key) . ") ";
        }
        
        
        # Add foreign keys        
        if (count($this->m_foreignKeys) != 0)
        {
            foreach ($this->m_foreignKeys as $foreign_key_obj)
            {
                $table_column     = $foreign_key_obj->table_column;
                $reference_table  = $foreign_key_obj->reference_table;
                $reference_column = $foreign_key_obj->reference_column;
                $deleteCascade   = $foreign_key_obj->delete_cascade;
                $updateCascade   = $foreign_key_obj->update_cascade;
                
                if ($keysString !== "")
                {
                    $keysString .= ", ";
                }
                
                $keysString .= 
                    'FOREIGN KEY (`' . $table_column . '`) ' .
                    'REFERENCES `' . $reference_table . '` (`' . $reference_column . '`) ';
                
                if ($deleteCascade)
                {
                    $keysString .= 'ON DELETE CASCADE ';
                }
                
                if ($updateCascade)
                {
                    $keysString .= 'ON UPDATE CASCADE ';
                }
            }
        }
        
        # Add unique combined keys.
        foreach ($this->m_combinedUniqueKeys as $key)
        {
            if ($keysString !== "")
            {
                $keysString .= ", ";
            }
            
            $keysString .= "UNIQUE KEY (" . implode(",", $key) . ") ";
        }
        
        # Add the Primary key (can only be one)
        if ($this->m_combinedPrimaryKey != null)
        {
            if ($primaryKeyString != "")
            {
                throw new \Exception('Table Creator: you appear to have ' .
                                     'multiple primary keys');
            }
            
            $primaryKeyString = "PRIMARY KEY (" . 
                                    implode(",", $this->m_combinedPrimaryKey) . 
                                ") ";
        }
        
        $engine_string = "ENGINE=" . $this->m_engine . " ";
        
        
        if ($primaryKeyString != "")
        {
            $primaryKeyString = ", " . $primaryKeyString;
        }
        
        if ($keysString != "")
        {
            $keysString = ", " . $keysString;
        }
        
        $fieldsString .= $primaryKeyString . $keysString;
        
        $characterset_string = "";
        if ($this->m_charSet !== null)
        {
            $characterset_string = "DEFAULT CHARSET=" . $this->m_charSet . " ";
        }
        
        $query = 
            "CREATE TABLE " . 
            '`' . $this->m_name . '` ' . 
            "(" . $fieldsString . ") " . 
            $engine_string . 
            $characterset_string;
        
        $result = $this->m_mysqliConn->query($query);
        
        if ($result !== TRUE)
        {
            $err_array = array(
                "Class"         => get_called_class(),
                "Message"       => "Error creating table",
                "Query"         => $query,
                "Mysqli Error"  => $this->m_mysqliConn->error
            );
                
            throw new \Exception(json_encode($err_array, JSON_PRETTY_PRINT, JSON_UNESCAPED_SLASHES));
        }
    }
    
    
    /**
     * Given a list of field names, this will set all those fields to default 
     * to null.
     * @param mixed $fieldNames - either an array of field names or a single 
     *                            field name string
     */
    public function setDefaultNull($fieldNames)
    {
        if (is_array($fieldNames))
        {
            foreach ($fieldNames as $field_name)
            {
                $this->setDefaultNull($field_name);
            }
        }
        else
        {
            if (isset($this->m_fields[$fieldNames]))
            {
                $this->m_fields[$fieldNames]->setDefault('NULL');
            }
            else
            {
                $err_msg = '[' . $fieldNames . '] field not found in table ' .
                           '[' . $this->m_name . '] when setting null fields';

                throw new \Exception($err_msg);
            }
        }
    }
    
    
    /**
     * Specify the character set of the table. This is useful because as the 
     * developer of the application, we may not know what the default character 
     * set was set to when the database itself was created.
     * @param string $charSet - the short name for a character set, such as 
     *                          big5 or utf8mb4
     * @throws Exception - if invalid character set was specified.
     */
    public function setCharacterSet($charSet)
    {
        $possible_encodings = array(
            "big5",
            "dec8",
            "cp850",
            "hp8",
            "koi8r",
            "latin1",
            "latin2",
            "swe7",
            "ascii",
            "ujis",
            "sjis",
            "hebrew",
            "tis620",
            "euckr",
            "koi8u",
            "gb2312",
            "greek",
            "cp1250",
            "gbk",
            "latin5",
            "armscii8",
            "utf8",
            "ucs2",
            "cp866",
            "keybcs2",
            "macce",
            "macroman",
            "cp852",
            "latin7",
            "utf8mb4",
            "cp1251",
            "utf16",
            "cp1256",
            "cp1257",
            "utf32",
            "binary",
            "geostd8",
            "cp932",
            "eucjpms"
        );
        
        if (!in_array($charSet, $possible_encodings))
        {
            throw new Exception("Unrecognized characterset: " . $charSet);
        }
        
        $this->m_charSet = $charSet;
    }
    
    
    /**
     * Given a list of field names, this will set all those fields to default 
     * to the specified value
     * @param mixed $fieldNames - an array of field names or a single field 
     *                            name string
     * @param mixed $default - the default value you wish to set.
     * @return void
     */
    public function setDefault($fieldNames, $default)
    {
        if (is_array($fieldNames))
        {
            foreach ($fieldNames as $field_name)
            {
                $this->setDefault($field_name, $default);
            }
        }
        else
        {
            if (isset($this->m_fields[$fieldNames]))
            {
                $this->m_fields[$fieldNames]->setDefault($default);
            }
            else
            {
                $fields = print_r($this->m_fields, true);
                $err_msg = '[' . $fieldNames . '] field not found in table ' .
                           '[' . $this->m_name . '] when setting default ' . 
                           'fields. Fields: ' . $fields;

                throw new \Exception($err_msg);
            }
        }
    }
    
    
    /**
     * Given a list of field names, this will set those fields to allow null.
     * Note that this will not default them to null, if you want that then you 
     * need to use setDefaultNull()
     * @param array $fieldNames - array of field names that need to be set to 
     *                            allow null
     * @throws Exception
     */
    public function setAllowNull(array $fieldNames)
    {
        foreach ($fieldNames as $field_name)
        {
            if (isset($this->m_fields[$field_name]))
            {
                $this->m_fields[$field_name]->setAllowNull();
            }
            else
            {
                $err_msg = '[' . $field_name . '] field not found in table ' .
                           '[' . $this->m_name . '] when setting allow_null';
                
                throw new \Exception($err_msg);
            }
        }
    }
    
    
    /**
     * Sets the field (or group of fields) to be a primary key
     * @param mixed $primary_key - the name of the field that should be the 
     *                             primary key or an array of field names if 
     *                             that combination itself is the key.
     * @return void
     * @throws Exception if this table does not have that field.
     */
    public function setPrimaryKey($primary_key)
    {
        if (is_array($primary_key))
        {
            $this->m_combinedPrimaryKey = $primary_key;
        }
        else
        {
            if (isset($this->m_fields[$primary_key]))
            {
                $this->m_fields[$primary_key]->setPrimaryKey();
            }
            else
            {
                $errMsg = '[' . $primary_key . '] does not exist in table ' .
                          '[' . $this->m_name . ']';
                
                throw new \Exception($errMsg);
            }
        }
    }
    
    
    /**
     * Returns flag for if we have all of the fields specified by name.
     * @param array $fields - an array of string names that represent the fields
     * @return bool - true if we have all the fields, false otherwise.
     */
    private function hasFields($fields)
    {
        $hasFields = true;
        
        foreach ($fields as $field_name)
        {
            if (!isset($this->m_fields[$field_name]))
            {
                $dbgMsg = 'Hasfields returning that ' . $field_name . ' does ' .
                          'not exist in ' . print_r($this->m_fields, true);
                
                LogModel::debugLog($dbgMsg);
                $hasFields = false;
                break;
            }
        }
        
        return $hasFields;
    }
    
    
    # Accesssors
    
    
    /**
     * Retrieves a field from the table by name
     * @param string $field_name - the name of the field we desire
     * @return DatabaseField $field - the fetched field
     * @throws Exception if the field does not exist in this table.
     */
    public function getField($field_name)
    {
        $field = null;
        
        if (isset($this->m_fields[$field_name]))
        {
            $field = $this->m_fields[$field_name];
        }
        else
        {
            $err_msg = '[' . $field_name . '] does not exist in table ' .
                       '[' . $this->m_name . ']';
            
            throw new \Exception($err_msg);
        }
        
        return $field;
    }
}
