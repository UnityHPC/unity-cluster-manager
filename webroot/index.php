<?php
require "../resources/templates/header.php";
?>

<ul id='nodeMenu' class='contextMenu'>
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

<main>
  <section id="leftSide">
    <ul id='nodes'></ul>
    <div id="newNode">Add Node</div>
  </section>
  <section id="rightSide"></section>
</main>

<?php
require "../resources/templates/footer.php";
?>
