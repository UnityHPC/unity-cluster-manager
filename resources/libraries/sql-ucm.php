<?php

class sql {
  const db = "ucm";
  const host = SQL["host"];
  const user = SQL["user"];
  const pass = SQL["pass"];

  const tables = array(
    "prefs" => "prefs",
    "nodes" => "nodes"
  );

  private $conn;

  public function __construct() {
    try {
      $this->conn = new PDO("mysql:host=" . self::host . ";dbname=" . self::db, self::user, self::pass);
      // set the PDO error mode to exception
      $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    catch(PDOException $e)
    {
      echo "MySQL Connection Failed: " . $e->getMessage();
    }
  }

  public function getPref($pref) {
    $stmt = $this->conn->prepare("SELECT * FROM " . self::tables["prefs"] . " WHERE pref=:pref");
    $stmt->bindParam(":pref", $pref);
    $stmt->execute();
    return $stmt->fetch()["value"];
  }

  public function getNodes() {
    return $this->conn->query("SELECT * FROM " . self::tables["nodes"])->fetchAll();
  }

  public function getNode($name) {
    $stmt = $this->conn->prepare("SELECT * FROM " . self::tables["nodes"] . " WHERE name=:name");
    $stmt->bindParam(":name", $name);
    $stmt->execute();
    return $stmt->fetch();
  }
}

?>
