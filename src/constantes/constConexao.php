<?php
require_once __DIR__ . '/../../config/initEnv.php';

define('HOST', $_ENV['DB_HOST']);
define('USER', $_ENV['DB_USER']);
define('SENHA', $_ENV['DB_PASS']);
define('DBNAME', $_ENV['DB_NAME']);

define('HOST2', $_ENV['DB2_HOST']);
define('USER2', $_ENV['DB2_USER']);
define('SENHA2', $_ENV['DB2_PASS']);
define('DBNAME2', $_ENV['DB2_NAME']);
