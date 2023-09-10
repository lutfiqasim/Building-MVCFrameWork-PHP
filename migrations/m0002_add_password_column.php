<?php

/**
 * @author Lutfi 
 * 
 * 
 */

class m0002_add_password_column
{
    public function up()
    {
        //Access DB
        $db = \app\core\Application::$app->db;
        $SQL = "ALTER TABLE users ADD COLUMN password VARCHAR(512) NOT NULL";
        $db->pdo->exec($SQL);
    }

    public function down()
    {
        //Access DB
        $db = \app\core\Application::$app->db;
        $SQL = "ALTER TABLE users DROP COLUMN password;";
        $db->pdo->exec($SQL);
    }
}