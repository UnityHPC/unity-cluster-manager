<html>
<head>
<link rel="stylesheet" type="text/css" href="css/global.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
</head>
<body>

<ul id='nodeMenu'>
  <span class='contextHeader'></span>
  <li data-vis="down" data-action="on">Power On</li>
  <li data-vis="up" data-action="reboot">Reboot</li>
  <li data-vis="up" data-action="off">Shutdown</li>
  <li data-vis="up" data-action="force-reboot">Force Reboot</li>
  <li data-vis="up" data-action="force-off">Force Shutdown</li>
</ul>

<?php
require "../resources/config.php";
?>

<nav id="mainNav">
  <a href="#">Nodes</a>
  <a href="#">Users</a>
  <a href="#">Images</a>
  <a href="#">Option 2</a>
  <a href="#">Option 3</a>
  <a href="#">Option 4</a>
</nav>

<main>
  <section id="leftSide">
    <?php
    echo "<ul id='nodes'>";
    foreach($sql->getNodes() as $node) {
      $ipNode = new ipmi($node["ipmi"], IPMI["user"], IPMI["pass"]);
      if ($ipNode->power()) {
        echo "<li><span class='vertical_center upDot'>&#11044;</span>" . $node["name"] . "</li>";
      } else {
        echo "<li><span class='vertical_center downDot'>&#11044;</span>" . $node["name"] . "</li>";
      }
    }
    echo "</ul>";
    ?>
  </section>
  <section id="rightSide">
    <h1>Unity Cluster Manager</h1>
    <p>Please click on a node to see info</p>
  </section>
</main>

<script src="js/global.js"></script>
</body>
</html>
