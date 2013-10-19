<?php
$var = ', '.$_GET['var'];

$fp = @fopen("file.txt", "a") or die("Couldn't open file.txt for writing! ". $var ); 
$numBytes = @fwrite($fp, $var) or die("Couldn't write values to file! " .$var ); 
 
@fclose($fp); 
 

?>
