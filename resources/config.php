<?php

define("IPMI", array(
  "user" => "admin",
  "pass" => "uma_xclar_1920"
));

define("NODES", array(
  "cpu1" => array(
    "ipmi" => "205.172.168.185",
    "ip" => "10.0.1.1"
  ),
  "cpu2" => array(
    "ipmi" => "205.172.168.186",
    "ip" => "10.0.1.2"
  ),
  "cpu3" => array(
    "ipmi" => "205.172.168.187",
    "ip" => "10.0.1.3"
  ),
  "cpu4" => array(
    "ipmi" => "205.172.168.188",
    "ip" => "10.0.1.4"
  ),
  "cpu5" => array(
    "ipmi" => "205.172.168.180",
    "ip" => "10.0.1.5"
  ),
  "cpu6" => array(
    "ipmi" => "205.172.168.181",
    "ip" => "10.0.1.6"
  ),
  "cpu7" => array(
    "ipmi" => "205.172.168.182",
    "ip" => "10.0.1.7"
  ),
  "cpu8" => array(
    "ipmi" => "205.172.168.183",
    "ip" => "10.0.1.8"
  ),
  "gpu1" => array(
    "ipmi" => "205.172.168.189",
    "ip" => "10.0.16.1"
  ),
  "gpu2" => array(
    "ipmi" => "205.172.168.190",
    "ip" => "10.0.16.2"
  ),
));

require "libraries/ipmi.php";

?>
