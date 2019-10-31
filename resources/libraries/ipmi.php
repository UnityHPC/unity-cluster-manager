<?php

class ipmi {
  private $ip;
  private $user;
  private $pass;

  public function __construct($ip, $user, $pass) {
    $this->ip = $ip;
    $this->user = $user;
    $this->pass = $pass;
  }

  private function ipmiTool($cmd) {
    return shell_exec("ipmitool -I lanplus -H " . $this->ip . " -U " . $this->user . " -P " . $this->pass . " " . $cmd);
  }

  public function power($state = "status", $hard = false) {
    switch($state) {
      case "on":
      return $this->ipmiTool("chassis power on") === "Chassis Power Control: Up/On\n";

      case "off":
      return $this->ipmiTool("chassis power off") === "Chassis Power Control: Down/Off\n";

      case "reboot":
      return $this->ipmiTool("chassis power cycle") === "Chassis Power Control: Cycle\n";

      case "status":
      return $this->ipmiTool("chassis power status") === "Chassis Power is on\n";

      default:
      return false;
    }
  }

  public function getFRU($id, $attr=NULL) {
    $cmd = "fru print " . $id;
    if (!is_null($attr)) {
      $cmd .= " | grep -i \"$attr\"";
    }

    $output = $this->ipmiTool($cmd);
    if (!is_null($attr)) {
      return substr($output, strpos($output, ": ") + 1);
    } else {
      return $output;
    }
  }

  public function getSensor($sensor) {
    return $this->ipmiTool("sdr get \"" + $sensor . "\"");
  }
}

?>
