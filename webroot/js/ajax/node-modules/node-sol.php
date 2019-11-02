<?php
//
// Node Info Module (Cannot Execute Independently)
// $ipmi must be set
//

$module_title = "Serial Console";

// Start grotty if not running
if (shell_exec("ps aux | grep '[g]otty -w -p 9556'") == "") {
  shell_exec("/opt/http/ucm/webroot/bin/gotty -w -p 9556 /opt/http/ucm/webroot/bin/sol-wrapper > /dev/null 2>&1 &");
}

// Set Environment Vars
$solPrefs = fopen("tmp/solPrefs", "w");
fwrite($solPrefs, "node:" . $_GET["node"] . "\n");
fwrite($solPrefs, "host:" . $sql->getNode($_GET["node"])["ipmi"] . "\n");
fwrite($solPrefs, "user:" . IPMI["user"] . "\n");
fwrite($solPrefs, "pass:" . IPMI["pass"] . "\n");
fclose($solPrefs);

echo "<iframe class='serial' src='/tty/' scrolling='auto'></iframe>";
?>
