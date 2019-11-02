<?php
require "../../../resources/config.php";

if (!isset($_GET["page"])) {
  die("Page paraemeter is required");
}

$node = $_GET["page"];

$ipmi = new ipmi($sql->getNode($node)["ipmi"],IPMI["user"],IPMI["pass"]);
$modules = array();

echo "<header id='pageTop'>";
echo "<div id='pageHeader'><span class='pageTitle'>" . $node . "</span><span class='nodeDevice vertical_center'>" . $ipmi->getFRU(0,"Product Manufacturer") . " " . $ipmi->getFRU(0,"Product Name") . "</span></div>";

echo "<div class='pageTabs'>";
// Grab PHP of each file (must have IPMI set)
foreach(glob("node-modules/*") as $file) {
  ob_start();
  include $file;
  $modules[$module_title] = ob_get_clean();
  echo "<a>$module_title</a>";
}
echo "</div>";

echo "</header>";

foreach($modules as $title => $code) {
  echo "<div data-module='" . str_replace(" ", "_", $title)  . "' class='pageModule'>";
  echo $code;
  echo "</div>";
}
?>
