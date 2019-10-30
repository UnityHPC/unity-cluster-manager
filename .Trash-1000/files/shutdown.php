<?php

require "../../../resources/config.php";

$ipmi = new ipmi(NODES[$_GET["node"]]["ip"],IPMI["user"],IPMI["pass"]);

if (isset($_GET["hard"]) && $_GET["hard"] == "true") {
  if (!$ipmi->shutdown(true)) {
    die("Error shutting down node");
  }
} else {
  if (!$ipmi->shutdown()) {
    die("Error shutting down node");
  }
}

?>
