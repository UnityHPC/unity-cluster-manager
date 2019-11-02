<?php

require "../../../resources/config.php";

if (!isset($_GET["node"])) {
  die("Required parameter is node");
}

$ipmi = new ipmi($sql->getNode($_GET["node"])["ipmi"],IPMI["user"],IPMI["pass"]);
$ipmi->sol(false);  // Deactivate SOL

?>
