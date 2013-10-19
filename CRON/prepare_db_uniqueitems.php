<?php
ini_set('error_reporting', 1);
ini_set('display_errors', 1);
set_time_limit(0);

include '../WEB-INC/conf.php';
include '../WEB-INC/class.db.php';

$cDb = new db();

$checksum = array();

$q0 = mysql_query("SELECT `item_id`,`checksum` FROM `feed_items` WHERE `checksum` IS NOT NULL") or die(mysql_error());

while ($css = mysql_fetch_array($q0)) {
    $checksum[$css['checksum']] = $css['item_id'];
}
mysql_free_result($q0);

// get all
$q1 = mysql_query("SELECT `item_id`,`description` FROM `feed_items` WHERE `checksum` IS NULL");
while ($fis = mysql_fetch_object($q1)) {
    $md5 = md5($fis->description);

echo $fis->item_id;
    if (isset($checksum[$md5])) {
        // delete
echo " DELETE\n";
        mysql_query("DELETE FROM `feed_items` WHERE `item_id`='{$fis->item_id}'") or die (mysql_error());
        mysql_query("DELETE FROM `feed_item_json` WHERE `item_id`='{$fis->item_id}'") or die (mysql_error());
        
    } else {
echo " UPDATE\n";
        // update
        mysql_query("UPDATE `feed_items` SET `checksum`='{$md5}' WHERE `item_id`='{$fis->item_id}'") or die (mysql_error());
        $checksum[$md5] = $fis->item_id;
    }

    // check
    //$sum = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM `feed_items` WHERE `checksum`='{$md5}'")) or die (mysql_error());

    // if exists
    //if ($sum[0] == 0) {
    //} else {
        // delete
    //    mysql_query("DELETE FROM `feed_items` WHERE `item_id`='{$fis->item_id}'") or die (mysql_error());
    //    mysql_query("DELETE FROM `feed_item_json` WHERE `item_id`='{$fis->item_id}'") or die (mysql_error());
    //}
}

