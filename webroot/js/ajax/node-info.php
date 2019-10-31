<?php

require "../../../resources/config.php";

if (!isset($_GET["node"])) {
  die("Required parameter is node");
}

$ipmi = new ipmi($sql->getNode($_GET["node"])["ipmi"],IPMI["user"],IPMI["pass"]);
echo "<div class='nodeHeader'><span class='nodeName'>" . $_GET["node"] . "</h1><span class='nodeDevice vertical_center'>" . $ipmi->getFRU(0,"Product Manufacturer") . " " . $ipmi->getFRU(0,"Product Name") . "</span></div>";
echo "<pre>" . $ipmi->getFRU(0) . "</pre>";

?>
