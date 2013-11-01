<?php
ini_set('error_reporting', 1);
ini_set('display_errors', 1);
set_time_limit(0);

include "../WEB-INC/conf.php";
include "../WEB-INC/class.mysql.php";
include "../WEB-INC/class.api.php";

include "../WEB-INC/class.contain.feed.php";
include "../WEB-INC/class.contain.item.php";

include "../WEB-INC/class.downloader.php";
include "../WEB-INC/class.data.php";
include "../WEB-INC/class.feed.php";

include "../WEB-INC/class.keyword.php";

$api = new clsApi();

$api->crawler("all", array("limit" => 10));
