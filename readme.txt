+++++++++INTRO
codeigniter 3.1.3 project

cp ./application/config/config.php.example ./application/config/config.php
cp ./application/config/database.php.example ./application/config/database.php

vim ./application/config/config.php
$config['base_url'] = "http(s)://home.test/";
$config['encryption_key'] = 'breasts';
:wq

vim ./application/config/database.php
	'username' => 'loopnova',
	'password' => 'shame',
	'database' => 'home',
:wq
------------------------------------------------------------------------
database
mysqladmin -u root -p create home
mysql -u root -p mytestdatabase < database_structure.sql
------------------------------------------------------------------------
crontabs
#insert magic time (0, 1 or 2) into DB
11 01 * * * elinks --dump http(s)://home.test/daemon/daniel/magicHappeningGenerator

#darksky get and insert all data
*/2 * * * * elinks --dump http(s)://home.test/daemon/getdark/insertdaily > /dev/null 2>&1
------------------------------------------------------------------------
without weather data for yesterday, page will show errors. so once database is up and running and crontabs are good to go, give it a day before the page loads without errors.
------------------------------------------------------------------------


+++++++++TO DO
-create a DB that hold items, for example the basic tasks from here: https://code.visualstudio.com/shortcuts/keyboard-shortcuts-windows.pdf - then on the homepage show a shortcut/quote per day to help memorise