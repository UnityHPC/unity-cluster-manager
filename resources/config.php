<?php

define("SITE_LOCATION", str_replace("resources/config.php", "", __FILE__));  // if the location of config.php changes, this line must be changed

define("BIN", array(
  "IPMITOOL" => SITE_LOCATION . "bin/ipmitool",
  "IPMIEVD" => SITE_LOCATION . "bin/ipmievd",
  "GOTTY" => SITE_LOCATION . "bin/gotty",
  "SOL_WRAPPER" => SITE_LOCATION . "bin/sol-wrapper"
));

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
