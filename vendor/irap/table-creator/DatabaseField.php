<?php

/*
 * Class to represent a single field/column in a MySQL database.
 * This is mainly used in the generation of tables.
 */

namespace iRAP\TableCreator;


class DatabaseField
{
    private $m_name; # e.g. the column name.
    private $m_type;
    private $m_default           = null; # This is very different to 'NULL'
    private $m_autoIncrementing  = false;
    private $m_constraint        = null;
    private $m_allowNull         = false;
    private $m_isKey             = false;
    private $m_isUnique          = false; # only affects if is_key set to true
    private $m_isPrimary         = false; # only affects if is_key set to true
    
    
    # Sepcify the types for easy creation (prevent typos/mistakes)
    const TYPE_CHAR      = 'CHAR';
    const TYPE_VARCHAR   = 'VARCHAR';
    const TYPE_TINYTEXT  = 'TINYTEXT';
    const TYPE_TEXT      = 'TEXT';
    const TYPE_LONGTEXT  = 'LONGTEXT';
    const TYPE_INT       = 'INT';
    const TYPE_DECIMAL   = 'DECIMAL';
    const TYPE_TINY_INT  = "tinyint";
    const TYPE_DATE      = "DATE";
    const TYPE_TIMESTAMP = "TIMESTAMP";
    
    # Spatial types
    const TYPE_POINT               = "POINT";
    const TYPE_LINESTRING          = "LINESTRING";
    const TYPE_POLYGON             = "POLYGON";
    const TYPE_MULTI_POINT         = "MULTIPOINT";
    const TYPE_MULTI_LINE_STRING   = "MULTILINESTRING";
    const TYPE_MULTI_POLYGON       = "MULTIPOLYGON";
    const TYPE_GEOMETRY_COLLECTION = "GEOMETRYCOLLECTION";
    const TYPE_GEOMETRY            = "GEOMETRY";
    
    
    /**
     * Private constructor because this object must be created by one of the 
     * various 'factory' static methods.
     * @param type $name - the name of the field/column.
     */
    private function __construct($name, $type) 
    {
        $this->m_name = $name;
        $this->m_type = $type;
    }
    
    
    /**
     * for creating a varchar.
     * @param type $numChars
     * @param type $allow_null
     * @param type $default_null
     * @return array $specification - the specification necessary for dbforge.
     */
    public static function createChar($name, $size)
    {
        $field = new DatabaseField($name, self::TYPE_CHAR);
        $field->m_constraint = intval($size);
        return $field;
    }
    
    
    /**
     * Create a VARCHAR field
     * @param string $name - the name to give the field
     * @param int $size - how many characters the field can hold
     * @return \iRAP\TableCreator\DatabaseField
     */
    public static function createVarchar($name, $size)
    {
        $field = new DatabaseField($name, self::TYPE_VARCHAR);
        $field->m_constraint = intval($size);
        return $field;
    }
    
    
    /**
     * Factory method for creating a boolean (tiny int) type
     * @param string $name - the name of the field/column
     * @return iRAP\TableCreator\DatabaseField
     */
    public static function createBool($name)
    {
        $field = new DatabaseField($name, self::TYPE_TINY_INT);
        $field->m_constraint = 1;
        return $field;
    }
    
    
    /**
     * Creates a date field in the database (yyyy-mm-dd).
     * @param type $name
     * @return $field DatabaseField
     */
    public static function createDate($name)
    {
        $field = new DatabaseField($name, self::TYPE_DATE);
        return $field;
    }
    
    
    /**
     * Factory method for creating a TEXT type database field
     * @param type $name - the name of the field for when it is in the database;
     * @return \iRAP\TableCreator\DatabaseField
     */
    public static function createText($name)
    {
        $field = new DatabaseField($name, self::TYPE_TEXT);
        return $field;
    }
    
    
    /**
     * Factory method for creating a TINYTEXT type database field
     * @param type $name - the name of the field for when it is in the database;
     * @return \iRAP\TableCreator\DatabaseField
     */
    public static function createTinyText($name)
    {
        $field = new DatabaseField($name, self::TYPE_TINYTEXT);
        return $field;
    }
    
    
    /**
     * Factory method for creating a LONG_TEXT type database field
     * @param type $name - the name of the field for when it is in the database;
     * @return \iRAP\TableCreator\DatabaseField
     */
    public static function createLongText($name)
    {
        $field = new DatabaseField($name, self::TYPE_LONGTEXT);
        return $field;
    }
    
    
    /**
     * Creates an integer type field.
     * @param string $name
     * @param int $size - the size the int can reach, 
     *                    e.g. 2 means you can reach the number 99
     * @param bool $autoInc - auto increment the field 
     *                        (will mark it as primary key!)
     * @return \iRAP\TableCreator\DatabaseField
     */
    public static function createInt($name, $size, $autoInc=false)
    {
        $field = new DatabaseField($name, self::TYPE_INT);
        $field->m_constraint = $size;
        $field->m_autoIncrementing = $autoInc;
        
        # Only one field can auto increment and it must be a primary key
        if ($autoInc)
        {
            $field->setPrimaryKey();
        }
        
        return $field;
    }
    
    
    /**
     * Creates an timestamp type field.
     * By default, if a default value is not specified, the field will default to
     * the current timestamp and will automatically update to the current timestamp
     * whenever any of the other fields in the row changes. To stop the ON UPDATE 
     * behaviour, set a default value (such as "CURRENT_TIMESTAMP")
     * @param string $name - the name of the database field
     * @param bool $defaultValue - specify a default value for when creating rows.
     *                             defining this will prevent the field automatically
     *                             updating whenever another field in the row changes.
     * @return \iRAP\TableCreator\DatabaseField
     */
    public static function createTimestamp($name, $defaultValue=NULL)
    {
        $field = new DatabaseField($name, self::TYPE_TIMESTAMP);
        
        if ($defaultValue !== NULL)
        {
            $field->setDefault('CURRENT_TIMESTAMP');
        }
        
        return $field;
    }
    
    
    /**
     * Creates the DECIMAL field type
     * @param string $name - the name of the field/column in the database.
     * @param int $precisionBefore - the precision before the decimal place. 
     *                                eg. 2 means you can reach the number 99
     * @param int $precisionAfter - precision after the decimal place. 
     *                               e.g. 2 means you can be accurate to 0.01
     * @return \iRAP\TableCreator\DatabaseField
     */
    public static function createDecimal($name, 
                                         $precisionBefore, 
                                         $precisionAfter)
    {
        $field = new DatabaseField($name, self::TYPE_DECIMAL);
        
        $field->m_constraint = intval($precisionBefore) . ',' . 
                               intval($precisionAfter);
        return $field;
    }
    
    
    /**
     * Create a POINT field
     * https://mariadb.com/kb/en/mariadb/point/
     * @param string $name - the name to give the field/column
     * @return \iRAP\TableCreator\DatabaseField
     */
    public static function createPoint($name)
    {
        $field = new DatabaseField($name, self::TYPE_POINT);
        return $field;
    }
    
    
    /**
     * Create a LineString field
     * https://mariadb.com/kb/en/mariadb/linestring/
     * @param string $name - the name to give the field/column
     * @return \iRAP\TableCreator\DatabaseField
     */
    public static function createLineString($name)
    {
        $field = new DatabaseField($name, self::TYPE_LINESTRING);
        return $field;
    }
    
    /**
     * Create a Polygon field
     * https://mariadb.com/kb/en/mariadb/polygon/
     * @param string $name - the name to give the field/column
     * @return \iRAP\TableCreator\DatabaseField
     */
    public static function createPolygon($name)
    {
        $field = new DatabaseField($name, self::TYPE_POLYGON);
        return $field;
    }
    
    
    /**
     * Create a MultiPoint field
     * https://mariadb.com/kb/en/mariadb/multipoint/
     * @param string $name - the name to give the field/column
     * @return \iRAP\TableCreator\DatabaseField
     */
    public static function createMultiPoint($name)
    {
        $field = new DatabaseField($name, self::TYPE_MULTI_POINT);
        return $field;
    }
    
    
    /**
     * Create a MultiPoint field
     * https://mariadb.com/kb/en/mariadb/multilinestring/
     * @param string $name - the name to give the field/column
     * @return \iRAP\TableCreator\DatabaseField
     */
    public static function createMultiLineString($name)
    {
        $field = new DatabaseField($name, self::TYPE_MULTI_LINE_STRING);
        return $field;
    }
    
    
    /**
     * Create a MultiPoint field
     * https://mariadb.com/kb/en/mariadb/multipolygon/
     * @param string $name - the name to give the field/column
     * @return \iRAP\TableCreator\DatabaseField
     */
    public static function createMultiPolygon($name)
    {
        $field = new DatabaseField($name, self::TYPE_MULTI_POLYGON);
        return $field;
    }
    
    
    /**
     * Create a GeometryCollection field
     * https://mariadb.com/kb/en/mariadb/geometrycollection/
     * @param string $name - the name to give the field/column
     * @return \iRAP\TableCreator\DatabaseField
     */
    public static function createGeometryCollection($name)
    {
        $field = new DatabaseField($name, self::TYPE_GEOMETRY_COLLECTION);
        return $field;
    }
    
    
    /**
     * Create a geometry field
     * https://mariadb.com/kb/en/mariadb/geometry-types/#geometrygeometry
     * @param string $name - the name to give the field/column
     * @return \iRAP\TableCreator\DatabaseField
     */
    public static function createGeometry($name)
    {
        $field = new DatabaseField($name, self::TYPE_GEOMETRY);
        return $field;
    }
    
    
    
    
    /**
     * Specify that this field is allowed to be null.
     * @return void
     */
    public function setAllowNull()
    {
        $this->m_allowNull = true;
    }
    
    
    /**
     * Disables the ability to set null in the database for this field
     * Just in case someone uses prototypes for rapid creation on one that has 
     * null enabled and then wants to disable on certain fields.
     */
    public function disableNull()
    {
        $this->m_allowNull = true;
        
        if ($this->m_default == 'NULL')
        {
            $this->m_default = null; # unset it
        }
    }
    
    
    /**
     * Specify that this field is a primary key
     */
    public function setPrimaryKey()
    {
        $this->setKey();
        $this->m_isPrimary = true;
    }
    
    
    /**
     * Specify that this field acts as a key (but is not a primary key)
     * @param bool $unique - optionally set to true to make this a UNIQUE key.
     */
    public function setKey($unique=false)
    {
        $this->m_isKey = true;
        $this->m_isUnique = $unique;
    }
    
    
    /**
     * Sets the default for this field.
     * @param mixed $default - the default value for this field in the database
     */
    public function setDefault($default)
    {
        $this->m_default = $default;
        
        if (strtoupper($default) === 'NULL')
        {
            $this->m_allowNull = true;
            $this->m_default = 'NULL';
        }
    }
    
    
    /**
     * Returns the text representing this field's definition inside a create 
     * table statement.
     * @return string - the string for defining this field in a mysql table.
     */
    public function getFieldString()
    {
        $fieldString = "`" . $this->m_name . "` " . $this->m_type;
        
        if ($this->m_constraint != null)
        {
            $fieldString .= " (" . $this->m_constraint . ")";
        }
                
        if ($this->isAutoIncrementing())
        {
            $fieldString .= " AUTO_INCREMENT";
        }
        
        if ($this->m_default !== null)
        {
            $fieldString .= " DEFAULT " . $this->m_default;
        }
        
        if (!$this->m_allowNull)
        {
            $fieldString .= " NOT NULL";
        }
        
        return $fieldString;
    }
    

    # Accessors
    public function getName()             { return $this->m_name; }
    public function getType()             { return $this->m_type; }
    public function getDefault()          { return $this->m_default; }
    public function getConstraint()       { return $this->m_constraint; }
    public function getAllowNull()        { return $this->m_allowNull; }
    public function isKey()               { return $this->m_isKey; }
    public function isPrimaryKey()        { return $this->m_isPrimary; }
    public function isUnique()            { return $this->m_isUnique; }
    public function isAutoIncrementing()  { return $this->m_autoIncrementing; }
}