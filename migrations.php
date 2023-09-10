<?php
//Run php migrations.php to load migrations and db
/**
 * Script to execute and migrate the migrations file
 */
 use app\Controllers\SiteController;
 use app\Controllers\AuthController;
 use app\core\Application;
 
 require_once __DIR__ . '/vendor/autoload.php';
 
 //get env files
 $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
 $dotenv->load();
 
 $config = [
     'db' => [
         'dsn' => $_ENV['DB_DSN'],
         'user' => $_ENV['DB_USER'],
         'password' => $_ENV['DB_PASSWORD'],
     ],
 
 ];
 
 $app = new Application(__DIR__, $config);

 
 $app->db->applyMigrations();
