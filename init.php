<?php
/**
 * Created by JetBrains PhpStorm.
 * User: M. Yegorov
 * Date: 3/29/13
 * Time: 5:54 PM
 * To change this template use File | Settings | File Templates.
 */

// Параметры MySQL
define ('SERVER', 'localhost');
define ('USER', 'lopar740_therron');
define ('PASS','maedanaena');
define ('DB', 'lopar740_therron');
define ('LANG','ru');

require 'lib/db_mysql.php';
require 'lib/functions.php';
require 'l18n/'.LANG.'.php';

// соединение с БД и смена кодировки
$db = new DatabaseConnection (SERVER, USER, PASS, DB);