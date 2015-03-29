<?php
/**
 * Created by PhpStorm.
 * User: sa
 * Date: 3/30/15
 * Time: 12:15 AM
 */
ORM::configure("{$settings['db']['engine']}:host={$settings['db']['host']};dbname={$settings['db']['database']}");
if ($settings['db']['engine'] == 'mysql') {
    ORM::configure('driver_options', array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8', PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => 1));
}
ORM::configure('username', $settings['db']['user']);
ORM::configure('password', $settings['db']['password']);
ORM::configure('logging', true);
