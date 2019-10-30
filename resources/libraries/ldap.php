<?php

class unityLDAP extends ldapConn {
  // Tree Constants
  const OU_USER = "ou=users";
  const OU_GROUP = "ou=groups";
  const OU_PI_GROUP = "ou=groups_pi";
  const DOMAIN = "dc=unity,dc=rc,dc=umass,dc=edu";
  const ID_MAP = array(1000, 9999);
  const PI_ID_MAP = array(10000, 19999);

  // Login Constants
  const HOST = CONFIG["ldap"]["host"];
  const DN = CONFIG["ldap"]["dn"];
  const PASS = CONFIG["ldap"]["pass"];

  public function __construct() {
    parent::__construct(self::HOST, self::DN, self::PASS);
  }

  public function getUser($user, $org) {
    return new ldapEntry($this->conn, "cn=$user," . self::OU_USER . ",o=$org," . self::DOMAIN);
  }

  public function getUserByUID($uid) {
    $user = $this->search("(&(uidnumber=$uid)(objectclass=posixAccount))");
    if (count($user) == 0) {
      return NULL;
    } else {
      return $user[0];
    }
  }

  public function getUsers() {
    return $this->search("(objectclass=posixAccount)");
  }

  public function getNextID() {
    $uid = self::ID_MAP[0];
    while ($this->getUserByUID($uid) != NULL) {
      if ($uid >= self::ID_MAP[1]) {
        die("We have run out of UIDs to give out. Contact an admin");
      }
      $uid++;
    }

    if ($this->getGroupByGID($uid) != NULL) {
      die("FATAL: UID and GID assignments don't match. Contact the admins.");
    }

    return $uid;
  }

  public function getGroup($user, $org) {
    return new ldapEntry($this->conn, "cn=$user" . "_$org," . self::OU_GROUP . ",o=$org," . self::DOMAIN);
  }

  public function getGroupByGID($gid) {
    $group = $this->search("(&(gidnumber=$gid)(objectclass=posixGroup))");
    if (count($group) == 0) {
      return NULL;
    } else {
      return $group[0];
    }
  }

  public function getPIGroup($user, $org) {
    return new ldapEntry($this->conn, "cn=pi_" . $user . "_$org," . self::OU_PI_GROUP . ",o=$org," . self::DOMAIN);
  }

  public function getNextPIID() {
    $gid = self::PI_ID_MAP[0];
    while ($this->getGroupByGID($gid) != NULL) {
      if ($gid >= self::PI_ID_MAP[1]) {
        die("We have run out of GIDs to give out. Conact the admins.");
      }
      $gid++;
    }
    return $gid;
  }
}

class ldapEntry {
  private $conn;
  private $dn;

  private $object;
  private $mods;

  public function __construct($conn, $dn) {
    $this->conn = $conn;
    $this->dn = $dn;
    $this->pullObject();
  }

  /**
  * Pulls an entry from the ldap connection, and sets the instance var $object. If entry does not exist, $object = null.
  */
  private function pullObject() {
    $search = @ldap_get_entries($this->conn, ldap_search($this->conn, $this->dn, "(cn=*)"));

    if (isset($search)) {
      // Object Exists
      if (count($search) > 2) {  // 1 For LDAP count element, and 1 for actual object
        // Duplicate Objects Found
        die("Fatal: Call to ldapObject with non-unique DN.");
      } else {
        $this->object = $search[0];
      }
    }
  }

  /**
  * Checks whether the entry in question exists
  * @return bool True if entry exists, False if it does not exist
  */
  public function exists() {
    return !is_null($this->object);
  }

  /**
  * Writes changes set in $mods array to the LDAP connection.
  * @return bool true on success, false on failure
  */
  public function write() {
    if ($this->object == NULL) {
      $success = ldap_add($this->conn, $this->dn, $this->mods);  // Create a New Entry
    } else {
      $success = ldap_mod_replace($this->conn, $this->dn, $this->mods);  // Modift Existing Entry
    }

    if ($success) {
      $this->pullObject();
      $this->mods = NULL;  // Reset Modifications Array to Null
    }
    return $success;
  }

  /**
  * Deletes the entry in question
  * @return bool true on success, false on failure
  */
  public function delete() {
    if ($this->object == NULL) {
      return false;
    } else {
      if(ldap_delete($this->conn, $this->dn)) {
        $this->pullObject();
        $this->mods = NULL;
        return true;
      } else {
        return false;
      }
    }
  }

  /**
  * Sets the value of a single attribute in the LDAP object (This will overwrite any existing values in the attribute)
  * @param string $attr Attribute Key Name to modify
  * @param $value array or string value to set the attribute value to
  */
  public function setAttribute($attr, $value) {
    if (is_array($value)) {
      $this->mods[$attr] = $value;
    } else {
      $this->mods[$attr] = array($value);
    }
  }

  /**
  * Appends values to a given attribute, preserving initial values in the attribute
  * @param string $attr Attribute Key Name to modify
  * @param $value array or string value to append attribute
  */
  public function appendAttribute($attr, $value) {
    $objArr = array();
    if (isset($this->object[$attr])) {
      $objArr = $this->object[$attr];
      unset($objArr["count"]);  // Remove Count Key that LDAP puts in there
    }

    $modArr = array();
    if (isset($this->mods[$attr])) {
      $modArr = $this->mods[$attr];
    }

    if (is_array($value)) {
      $this->mods[$attr] = array_merge($objArr, $modArr, $value);
    } else {
      $this->mods[$attr] = array_merge($objArr, $modArr, (array) $value);
    }
  }

  /**
  * Sets and overwrites attributes based on a single array.
  * @param array $arr Array of keys and attributes. Key values must be attribute key
  */
  public function setAttributes($arr) {
    $this->mods = $arr;
  }

  /**
  * Appends attributes based on a single array
  * @param array $arr Array of keys and attributes. Key values must be attribute key
  */
  public function appendAttributes($arr) {
    foreach($arr as $attr) {
      $this->appendAttribute(key($attr), $attr);
    }
  }

  /**
  * Removes a given attribute
  * @param string $attr Key of attribute to be removed
  */
  public function removeAttribute($attr) {
    $this->mods[$attr] = array();
  }

  /**
  * Returns a given attribute of the object
  * @param string $attr Attribute key value to return
  * @return array value of requested attribute
  */
  public function getAttribute($attr) {
    if (isset($this->object[$attr])) {
      return $this->object[$attr];
    } else {
      return NULL;
    }
  }

  /**
  * Returns the entire objects attributes
  * @return array Array where keys are attributes
  */
  public function getAttributes() {
    return $this->object;
  }
}

class ldapConn {
  protected $conn;
  private $base;

  /**
  * Constructor the ldapConn which initializes a connection to an LDAP server
  * @param string $host Host ldap address of server
  * @param string $bind_dn Admin bind dn
  * @param string $bind_pass Admin bind pass
  * @param string (optional) Domain base (by default this will be first occurance of dc= in $bind_dn and after)
  */
  public function __construct($host, $bind_dn, $bind_pass, $base = NULL) {
    $this->conn = ldap_connect($host);
    if(is_null($base)) {
      $this->base = substr($bind_dn, strpos($bind_dn, "dc="));
    } else {
      $this->base = $base;
    }

    ldap_set_option($this->conn, LDAP_OPT_PROTOCOL_VERSION, 3);
    ldap_bind($this->conn, $bind_dn, $bind_pass);
  }

  /**
  * Returns the connection instance of the LDAP link
  * @return ldap_connect LDAP connection link
  */
  public function getConn() {
    return $this->conn;
  }

  /**
  * Returns base DN for the connection
  * @return string base DN for LDAP connection
  */
  public function getBase() {
    return $this->base;
  }

  /**
  * Runs a search on the LDAP server and returns entries
  * @param string $filter LDAP_search filter
  * @param string $base (optional) Search base, by defualt is the instance $base
  * @return array Array of ldapEntry objects
  */
  public function search($filter, $base = NULL) {
    $searchBase = (is_null($base)) ? $this->base : $base . "," . $this->base;
    $search = @ldap_get_entries($this->conn, ldap_search($this->conn, $searchBase, $filter));
    unset($search["count"]);

    $output = array();
    for($i = 0; isset($search) && $i < count($search); $i++) {
      array_push($output, new ldapEntry($this->conn, $search[$i]["dn"]));
    }

    return $output;
  }

  public function getEntry($dn) {
    return new ldapEntry($this->conn, $dn);
  }
}

?>
