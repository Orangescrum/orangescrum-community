<?php

/**
 * This is a class to handle database migrations. 
 * For the time being, this object takes a raw mysqli connection so it has complte flexibility when
 * when manipulating mysqli databases. A database driver may be better for when we can simply run
 * "create table", add column etc. 
 *
 * This script relies on a table called 'migrations' in the database for version info. If it does
 * not yet exist, then it will be created and the database will be considere to be at version 0
 */

namespace iRAP\Migrations;

class MigrationManager
{
    const MIGRATIONS_TABLE = 'migrations';
    
    private $m_mysqli_conn; #  A mysqli connection object that will be used to manipulate the db.
    private $m_schemas_folder; # The folder in which migration scripts are located.
    
    
    /**
     * Creates the Migration object in preparation for migration.
     * @param type $migration_folder - the path to the folder containing all the migration scripts
     *                                this may be absolute or relative.
     * @param type $connection - a Mysqli object connecting us to the database.
     */
    public function __construct($migration_folder, \mysqli $connection)
    {
        $this->m_schemas_folder = $migration_folder;
        $this->m_mysqli_conn = $connection;
    }
    
    
    /**
     * Migrates the database to the specified version. If the version is not specified (null) then 
     * this will automatically migrate the database to the furthest point which is determined by 
     * looking at the schemas.
     * 
     * @param $version - optional parameter to specify the version we wish to migrate to.
     *                   if not set, then this will automatically migrate to the latest version
     *                   which is discovered by looking at the files.
     * @return void - updates database.
     */
    public function migrate($desired_version = null)
    {
        $databaseVersion = intval($this->get_db_version());
        $migrationFiles = $this->get_migration_files();
        
        if ($desired_version === null)
        {
            end($migrationFiles); # move the internal pointer to the end of the array
            $desired_version = intval(key($migrationFiles));
        }
        
        if ($desired_version !== $databaseVersion)
        {                        
            if ($desired_version > $databaseVersion)
            {
                # Performing an upgrade
                foreach ($migrationFiles as $migrationFileVersion => $filepath)
                {
                    if 
                    (
                        $migrationFileVersion > $databaseVersion && 
                        $migrationFileVersion <= $desired_version
                    )
                    {
                        $className = self::include_file_and_get_class_name($filepath);
                        
                        /* @var $migrationObject MigrationInterface */
                        $migrationObject = new $className();
                        $migrationObject->up($this->m_mysqli_conn);
                        
                        # Update the version after every successful migration in case a later one
                        # fails
                        $this->insert_db_version($migrationFileVersion);
                    }
                }
            }
            else
            {
                # performing a downgrade
                krsort($migrationFiles);
                
                foreach ($migrationFiles as $migrationFileVersion => $filepath)
                {
                    if 
                    (
                        $migrationFileVersion <= $databaseVersion && 
                        $migrationFileVersion > $desired_version
                    )
                    {
                        $className = self::include_file_and_get_class_name($filepath);
                        
                        /* @var $migrationObject MigrationInterface */
                        $migrationObject = new $className();
                        $migrationObject->down($this->m_mysqli_conn);
                        
                        # Update the version after every successful migration in case a later one
                        # fails
                        $this->insert_db_version($migrationFileVersion);
                    }
                }
            }
        }
    }
    
    
    /**
     * Fetches the migration files from the migrations folder.
     * @param void
     * @return Array<int,string> $keyedFiles - map of verstion/filepath to migration script
     * @throws Exception if two files have same version or there is a gap in versions.
     */
    private function get_migration_files() 
    {
        // Find all the migration files in the directory and return the sorted.
        $files = scandir($this->m_schemas_folder);
        
        $keyedFiles = array();
        
        foreach ($files as $filename)
        {
            if (!is_dir($this->m_schemas_folder . '/' . $filename)) 
            {
                $fileVersion = self::get_file_version($filename);
                
                if (isset($keyedFiles[$fileVersion]))
                {
                    throw new \Exception('Migration error: two files have the same version!');
                }
                
                $keyedFiles[$fileVersion] = $this->m_schemas_folder . '/' . $filename;
            }
        }
        
        ksort($keyedFiles);
        
        # Check that the migration files dont have gaps which could be the result of human error.
        $cachedVersion = null;
        
        $versions = array_keys($keyedFiles);
        
        foreach ($versions as $version)
        {
            if ($cachedVersion !== null)
            {
                if ($version != ($cachedVersion + 1))
                {
                    throw new \Exception('There is a gap in your migration file versions!');
                }
                
                $cachedVersion = $version;
            }
        }
        
        return $keyedFiles;
    }
    
    
    /**
     * Given a file that has NOT already been included, this function will return the name
     * of the class within that file AFTER having included it.
     * Warning: This function works on the assumption that only one class is defined in the 
     * migration script!
     * @param filepath
     */
    private function include_file_and_get_class_name($filepath)
    {
        $existingClasses = get_declared_classes();
        require_once($filepath);
        $afterClasses = get_declared_classes();
        $new_classes = array_diff($afterClasses, $existingClasses);
        
        if (count($new_classes) == 0)
        {
            $errMsg = 'Migration error: Could not find new class from including migration script' .
                      '. This could be caused by having duplicate class names, or having already ' .
                      'included the migration script.';
            
            throw new \Exception($errMsg);
        }
        elseif (count($new_classes) > 1) 
        {
            $errMsg = 'Migration error: Found more than 1 class defined in the migration script ' .
                       '[' . $filepath . ']';
            
            throw new \Exception('Migration error:');
        }
        
        # newClasses array keeps its keys, so the first element is not at 0 at this point
        $new_classes = array_values($new_classes);
        return $new_classes[0];
    }
    

        
    /**
     * Function responsible for deciphering the 'version' from a filename. This is a function 
     * because we may wish to change it easily.
     * @param string $filename - the name of the file (not full path) that is a migration class.
     * @return int $version - the version the file represents.
     */
    private static function get_file_version($filename) 
    {
        $version = intval($filename);
        return $version;
    }
    
    
    /**
     * Inserts the specified version number into the database.
     * @param int $version - the new version of the database.
     * @return void.
     */
    private function insert_db_version($version)
    {
        $query = 
            "REPLACE INTO `" . self::MIGRATIONS_TABLE . "` " . 
            "SET `id`='1', `version`='" . $version . "'";
        
        $result = $this->m_mysqli_conn->query($query);
        
        if ($result === false)
        {
            throw new \Exception("Migrations: error inserting version into the database");
        }
    }
    
    
    /**
     * Fetches the version of the database from the database.
     * @param void
     * @return int $version - the version in the dataase if it exists, -1 if it doesnt.
     * @throws Exception if migration table exists but failed to fetch version.
     */
    private function get_db_version()
    {
        $result = $this->m_mysqli_conn->query("SHOW TABLES LIKE '" . self::MIGRATIONS_TABLE . "'");
        
        if ($result->num_rows > 0)
        {
            $query = "SELECT * FROM `" . self::MIGRATIONS_TABLE . "`";
            $result = $this->m_mysqli_conn->query($query);
            
            if ($result === FALSE || $result->num_rows == 0)
            {
                # Appears that we have the migrations table but no version row, which may be the 
                # result of a previously erroneous upgrade attempt, so return that no version is set.
                $version = -1;
            }
            else
            {
                $row = $result->fetch_assoc();
            
                if ($row == null || !isset($row['version']))
                {
                    throw new \Exception('Migrations: error reading database version from database');
                }
                
                $version = $row['version'];
            }
            
        }
        else
        {
            $this->create_migration_table();
            $version = -1; # just in case the users migration files start at 0 and not 1
        }
        
        return $version;
    }
    
    
    /**
     * Creates the migration table for if it doesnt exist yet to store the version within.
     * @param void
     * @return void.
     */
    private function create_migration_table()
    {
        $tableCreator = new \iRAP\TableCreator\TableCreator($this->m_mysqli_conn, self::MIGRATIONS_TABLE);
        
        $fields = array(
            \iRAP\TableCreator\DatabaseField::createInt('id', 1, true),
            \iRAP\TableCreator\DatabaseField::createInt('version', 4)
        );
        
        $tableCreator->addFields($fields);
        $tableCreator->setPrimaryKey('id');
        $tableCreator->run();
    }
}

