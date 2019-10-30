<html>
<head>
<link rel="stylesheet" type="text/css" href="css/global.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
</head>
<body>

<ul class='nodeMenu'>
  <span class='contextHeader'></span>
  <li data-vis="down" data-action="on">Power On</li>
  <li data-vis="up" data-action="reboot">Reboot</li>
  <li data-vis="up" data-action="off">Shutdown</li>
  <li data-vis="up" data-action="force-reboot">Force Reboot</li>
  <li data-vis="up" data-action="force-off">Force Shutdown</li>
</ul>

<?php

require "../resources/config.php";

echo "<ul class='nodes'>";
foreach(NODES as $key => $node) {
  $ipNode = new ipmi($node["ipmi"], IPMI["user"], IPMI["pass"]);
  if ($ipNode->power()) {
    echo "<li><span class='upDot'>&#11044;</span>" . $key . "</li>";
  } else {
    echo "<li><span class='downDot'>&#11044;</span>" . $key . "</li>";
  }
}
echo "</ul>";

?>

<script src="js/global.js"></script>
</body>
</html>
