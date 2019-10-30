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

  public function power($state = "status", $hard = false) {
    switch($state) {
      case "on":
      return shell_exec("ipmitool -I lanplus -H " . $this->ip . " -U " . $this->user . " -P " . $this->pass . " chassis power on") === "Chassis Power Control: Up/On\n";

      case "off":
      return shell_exec("ipmitool -I lanplus -H " . $this->ip . " -U " . $this->user . " -P " . $this->pass . " chassis power off") === "Chassis Power Control: Down/Off\n";

      case "reboot":
      return shell_exec("ipmitool -I lanplus -H " . $this->ip . " -U " . $this->user . " -P " . $this->pass . " chassis power cycle") === "Chassis Power Control: Cycle\n";

      case "status":
      return shell_exec("ipmitool -I lanplus -H " . $this->ip . " -U " . $this->user . " -P " . $this->pass . " chassis power status") === "Chassis Power is on\n";

      default:
      return false;
    }
  }
}

?>
