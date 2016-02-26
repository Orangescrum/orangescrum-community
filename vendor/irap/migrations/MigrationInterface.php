<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

namespace iRAP\Migrations;

interface MigrationInterface
{
    public function up(\mysqli $mysqliConn);
    public function down(\mysqli $mysqliConn);
}

?>
