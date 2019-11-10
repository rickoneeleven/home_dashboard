cp ./application/config/config.php.example ./application/config/config.php
cp ./application/config/database.php.example ./application/config/database.php

vim ./application/config/config.php
$config['base_url'] = "https://domain.com/";
$config['encryption_key'] = 'breasts';
:wq

vim ./application/config/database.php
	'username' => 'loopnova',
	'password' => 'shame',
	'database' => 'home',
:wq

crontabs
#insert magic time (0, 1 or 2) into DB
11 01 * * * elinks --dump https://domain.com/daemon/daniel/magicHappeningGenerator

#darksky get and insert all data
*/2 * * * * elinks --dump https://domain.com/daemon/getdark/insertdaily > /dev/null 2>&1
------------------------------------------------------------------------
database
mysqladmin -u root -p create home
mysql -u root -p mytestdatabase < database_structure.sql
------------------------------------------------------------------------
without weather data for yesterday, page will show errors. so once database is up and running and crontabs are good to go, give it a day before the page loads without errors.