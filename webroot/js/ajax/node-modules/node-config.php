<?php
//
// Node Info Module (Cannot Execute Independently)
// $ipmi must be set
//

$module_title = "Info";
echo "<pre>" . $ipmi->getFRU(0) . "</pre>";
?>
