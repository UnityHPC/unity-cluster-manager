<?php

require "../../../resources/config.php";

foreach($sql->getNodes() as $node) {
  $ipNode = new ipmi_ucm($node["ipmi"], IPMI["user"], IPMI["pass"]);
  if ($ipNode->power()) {
    echo "<li><span class='vertical_center upDot'>&#11044;</span>" . $node["name"] . "</li>";
  } else {
    echo "<li><span class='vertical_center downDot'>&#11044;</span>" . $node["name"] . "</li>";
  }
}

?>
