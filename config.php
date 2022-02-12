<?php
// define variables for the database connection
define("DB_SERVER", 'localhost');
define("DB_USER", 'root');
define("DB_PASS", '');
define("DB_NAME", 'url_short');

// global variables 
define("URL_LENGTH", 6);//length of the short url . Change in the value should be updated in .htaccess
define("BASE_URL", 'http://localhost/php-shorturl');

// defene the character set for the url codes to generate from
define("CHARSET", "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ");

?>