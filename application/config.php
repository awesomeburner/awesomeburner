<?php
/**
 * Created by PhpStorm.
 * User: sa
 * Date: 3/30/15
 * Time: 12:14 AM
 */

$settings = array();

// database settings
$settings['db']['engine'] = "mysql"; // can be pgsql or mysql
$settings['db']['host'] = "localhost";
$settings['db']['user'] = "";
$settings['db']['password'] = "";
$settings['db']['database'] = "parking";

// twig settings
$settings['cache_enable'] = false;
$settings['cache_path'] = "../cache/compilation_cache";

$content = array();
$twig_env = array();
$settings['template_name'] = "default";


if (file_exists(dirname(__FILE__) . "/config_local.php")) {
    include dirname(__FILE__) . "/config_local.php";
}
