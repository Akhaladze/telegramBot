<?php
echo "Test completed";
$latestupdateid = "123423423\n";
$myfile = fopen("/var/www.api/telegramBot/testfile.txt", "w");
fwrite($myfile, $latestupdateid);
fclose($myfile);
?>