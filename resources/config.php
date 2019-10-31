<?php

define("SQL", array(
  "host" => "127.0.0.1",
  "user" => "ucm",
  "pass" => "Nrno2tt4Kk3Mm"
));

require "libraries/sql-ucm.php";
$sql = new sql();

define("IPMI", array(
  "user" => $sql->getPref("ipmi_user"),
  "pass" => $sql->getPref("ipmi_pass")
));

require "libraries/ipmi.php";

?>
