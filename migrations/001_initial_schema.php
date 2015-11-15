<?php

/*
 * This is an example migration script. Please overwrite the up and down methods.
 */

class InitialSchema implements iRAP\Migrations\MigrationInterface
{
    public function up(\mysqli $mysqliConn) 
    {
        $queries = array();
        
        # This will create a $queries array of queries to execute.
        require_once (__DIR__ . '/resources/initialQueries.php');
        
        foreach($queries as $query)
        {
            $result = $mysqliConn->query($query);
            
            if ($result === FALSE)
            {
                $errMsg = 
                    "Initial migration failed. " . PHP_EOL .
                    "Query: " . $query . PHP_EOL . 
                    "Mysqli error: " . $mysqliConn->error;
                    
                throw new Exception($errMsg);
            }
        }
    }
    
    
    public function down(\mysqli $mysqliConn) 
    {
        $tables = array(
            'archives',
            'case_actions',
            'case_activities',
            'case_files',
            'case_file_drives',
            'case_filters',
            'case_recents',
            'case_settings',
            'case_templates',
            'case_user_emails',
            'case_user_views',
            'companies',
            'company_users',
            'custom_filters',
            'dailyupdate_notifications',
            'daily_updates',
            'default_project_templates',
            'default_project_template_cases',
            'default_templates',
            'easycases',
            'easycase_milestones',
            'email_reminders',
            'log_activities',
            'log_types',
            'mail_tbls',
            'milestones',
            'projects',
            'project_technologies',
            'project_templates',
            'project_template_cases',
            'project_users',
            'save_reports',
            'subscriptions',
            'template_module_cases',
            'timezones',
            'timezone_names',
            'transactions',
            'types',
            'type_companies',
            'users',
            'user_infos',
            'user_invitations',
            'user_logins',
            'user_notifications',
            'user_subscriptions',
        );
        
        foreach($tables as $table)
        {
            # Truncate table then delete it to prevent issues with foreign keys.
            $result = $mysqliConn->query("TRUNCATE `" . $table`");
            $result = $mysqliConn->query("DROP TABLE `" . $table`");
        }
    }
}
