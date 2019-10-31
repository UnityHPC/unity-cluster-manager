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
  <div class='contextDivider'></div>
  <li data-action="edit">Edit Node</li>
  <li data-action="clone">Clone Node</li>
  <li data-action="delete">Delete Node</li>
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
    <ul id='nodes'></ul>
    <div id="newNode">Add Node</div>
  </section>
  <section id="rightSide">
    <div class='nodeHeader'><span class='nodeName'>Unity Cluster Manager</span></div>
  </section>
</main>

</body>

<script src="js/global.js"></script>
</html>
