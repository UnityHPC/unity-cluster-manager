<?php

require "../../../resources/config.php";

if (!isset($_GET["node"])) {
  die("Required parameter is node");
}

$ipmi = new ipmi($sql->getNode($_GET["node"])["ipmi"],IPMI["user"],IPMI["pass"]);
echo "<div class='nodeHeader'><span class='nodeName'>" . $_GET["node"] . "</span><span class='nodeDevice vertical_center'>" . $ipmi->getFRU(0,"Product Manufacturer") . " " . $ipmi->getFRU(0,"Product Name") . "</span></div>";
echo "<div class='nodeTabs'><a class='selected' href='#'>Info</a><a href='#'>Config</a><a href='#'>Serial-Over-Lan</a><a href='#'>Slurm</a></div>";
echo "<pre>" . $ipmi->getFRU(0) . "</pre>";
echo "<pre>" . $ipmi->ipmiTool("sdr") . "</pre>";

?>
