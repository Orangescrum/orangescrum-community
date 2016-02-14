<?php

/* 
 * Script to execute once upon startup to run database migrations.
 */

require_once(__DIR__ . '/../vendor/autoload.php');

$connection = new mysqli(
    getenv("MYSQL_PORT_3306_TCP_ADDR"),
    "root", 
    "changeme123", # @TODO use a variable/define
    "orangescrum" # @TODO use a variable/define
);

$migrationManager = new \iRAP\Migrations\MigrationManager(__DIR__ . '/../migrations', $connection);
$migrationManager->migrate();

