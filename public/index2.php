<?php
/**
 * Created by PhpStorm.
 * User: sa
 * Date: 3/30/15
 * Time: 12:48 AM
 */

ini_set('error_reporting', 1);
ini_set('display_errors', 1);

include "../application/config.php";

include "../vendor/j4mie/idiorm/idiorm.php";
include "../vendor/twig/twig/lib/Twig/Autoloader.php";

include "../application/load.idiorm.php";
include "../application/load.twig.php";

$content['url']['home'] = "";
$content['statistics']['feeds']['total'] = ORM::for_table("feed_feeds")->count();
$content['statistics']['articles']['total'] = ORM::for_table("feed_items")->count();;

echo $twig->render("index.twig", $content);