<?php
//
// Node Info Module (Cannot Execute Independently)
// $ipmi must be set
//

$module_title = "Config";
echo "<pre>This is the config page!" . $ipmi->getFRU(0) . "</pre>";
?>
