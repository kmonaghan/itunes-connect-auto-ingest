<?php
define('HOST', 'YOUR-HOST');
define('DATABASE', 'itunes_connect_stats');
define('DB_USER', 'YOUR-DBUSER');
define('DB_PASSWORD', 'YOUR-DBPASS');

$accounts = array(array('username' => 'YOUR-USERNAME',
						'password' => 'YOUR-PASSWORD',
						'vndnumber' => 'YOUR-VND',
						),

				);
				
$dbh = new PDO("mysql:host=" . HOST . ";dbname=" . DATABASE, DB_USER, DB_PASSWORD);