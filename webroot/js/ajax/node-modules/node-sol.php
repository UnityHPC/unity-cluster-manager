<?php
//
// Node Info Module (Cannot Execute Independently)
// $ipmi must be set
//

$module_title = "Serial Console";

// Start grotty if not running
if (shell_exec("ps aux | grep '[g]otty -p 9556'") == "") {
  shell_exec("/opt/http/ucm/bin/gotty -p 9556 -w --permit-arguments /opt/http/ucm/bin/sol-wrapper > /dev/null 2>&1 &");
}

echo "<iframe class='serial' src='/tty/?arg=" . $node . "&arg=" . $sql->getNode($node)["ipmi"] . "&arg=" . IPMI["user"] . "&arg=" . IPMI["pass"] . "' scrolling='auto'></iframe>";
?>
