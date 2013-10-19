<?php
/**
 * MySQL Wrapper Class
 *
 * @version 3.1.008
 * @author Andrejs Naumovs
 * @link http://www.naumovs.de/class.mysql/class.mysql.html
 * @license http://www.gnu.org/licenses/gpl.html
 *
 * @return:       $value  = $class->Query("SELECT [ COUNT(*) || MAX(*) || MIN(*) || 123 || .. ] FROM ..."); 
 *                 $value  = $class->Query("SELECT `one` FROM ... LIMIT 0,1"); 
 *                 $object = $class->Query("SELECT `one`,`two` AS second, ..., `any` FROM ... LIMIT 0,1"); 
 *                           $one = $object->one; 
 *                           $two = $object->second; 
 *                           ... 
 *                           $any = $object->any 
 *                 $array  = $class->Query("SELECT `one`,`two` AS second, ..., `any` FROM ... "); 
 *                           $array[0] = $object 
 *                                              $one = $object->one; 
 *                                              $two = $object->second; 
 *                                              ... 
 *                                              $any = $object->any 
 *                           ... 
 *                           ... 
 *                           $array[n] = $object 
 *                                              $one = $object->one; 
 *                                              $two = $object->second; 
 *                                              ... 
 *                                              $any = $object->any 
 * 
 *    Try it:      $sql = "SELECT COUNT(*) FROM `table`"; 
 *                 $sql = "SELECT COUNT(*) as count, `any` FROM `table` WHERE 1 GROUP BY `any`"; 
 *                 $sql = "SELECT * FROM `table` ; 
 *              
 */

//  extends ClConfig
class clsMysql {
    // SET THESE VALUES TO MATCH YOUR DATA CONNECTION
    private $db_host    = null;  // server name
    private $db_user    = null;       // user name
    private $db_pass    = null;           // password
    private $db_dbname  = null;           // database name
    private $db_charset = "utf8";           // optional character set (i.e. utf8)
    private $db_pcon    = false;        // use persistent connection?

    // class-internal variables - do not change
    private $error_desc     = "";       // mysql error string
    private $error_number   = 0;        // mysql error number
    private $mysql_link     = 0;        // mysql link resource
    private $sql            = "";       // mysql query
    private $result;                    // mysql query result
    public $insert_id = null;
    public $num_rows = null;
    public $connect_success = false;

    /**
     * Determines if an error throws an exception
     *
     * @var boolean Set to true to throw error exceptions
     */
    public $ThrowExceptions = false;

    /**
     * Constructor: Opens the connection to the database
     *
     * @param boolean $connect (Optional) Auto-connect when object is created
     * @param string $database (Optional) Database name
     * @param string $server   (Optional) Host address
     * @param string $username (Optional) User name
     * @param string $password (Optional) Password
     * @param string $charset  (Optional) Character set
     */

	function __construct($pcon = false, $server = "", $username = "", $password = "", $database = "", $charset = "") {
		if($pcon)                 $this->db_pcon    = true;
		if(strlen($server)   > 0) $this->db_host    = $server; else $this->db_host    = DB_HOST;
		if(strlen($username) > 0) $this->db_user = $username; else $this->db_user    = DB_USER;
		if(strlen($password) > 0) $this->db_pass    = $password; else $this->db_pass    = ''.DB_PASS;
		if(strlen($database) > 0) $this->db_dbname  = $database; else $this->db_dbname  = DB_NAME;
		if(strlen($charset)  > 0) $this->db_charset = $charset;

		//
		if (strlen($this->db_host) > 0 && strlen($this->db_user) > 0) {
			$this->Open();
		}
    }

    /**
     * Connect to specified MySQL server
     *
     * @return boolean Returns TRUE on success or FALSE on error
     */
    private function Open() {
        $this->ResetError();

        // Open persistent or normal connection
        if ($this->db_pcon) {
            $this->mysql_link = mysql_pconnect($this->db_host, $this->db_user, $this->db_pass);
        } else {
            $this->mysql_link = mysql_connect ($this->db_host, $this->db_user, $this->db_pass);
        }

        // Connect to mysql server failed?
        if (! $this->IsConnected()) {
            $this->SetError();

            return false;
        } else { // Connected to mysql server
            // Select a database (if specified)
            if (strlen($this->db_dbname) > 0) {
                if (strlen($this->db_charset) == 0) {
                    if (! $this->SelectDatabase($this->db_dbname)) {
                        return false;
                    } else {
                    	$this->connect_success = true;
                        return true;
                    }
                } else {
                    if(!$this->SelectDatabase($this->db_dbname, $this->db_charset)) {
                        return false;
                    } else {
                    	$this->connect_success = true;
                        return true;
                    }
                }
            } else {
            	$this->connect_success = true;
                return true;
            }
        }
    }

    /**
     * Executes the given SQL query and returns the result
     *
     * @param string $sql The query string
     * @return (boolean, string, object, array with objects) result
     */
    public function Query($sql, $debug = false) {
		$this->ResetError();
		$this->sql    = $sql;
		$this->result = mysql_query(
		$this->sql,
		$this->mysql_link);

		// show debug info
		if($debug) {
			self::ShowDebugInfo("sql=".$this->sql);
		}

        // start the analysis
		if(TRUE === $this->result) {   // simply result
			$return = TRUE;         // successfully (for example: INSERT INTO ...)
			// return last insert ID
			$this->insert_id = mysql_insert_id($this->mysql_link);
		} else if (FALSE === $this->result) {
            $this->SetError();

            if($debug) {
                self::ShowDebugInfo("error=".$this->error_desc);
                self::ShowDebugInfo("number=".$this->error_number);
            }

            $return = FALSE;        // error occured (for example: syntax error)
        } else { // complex result
			$nr = mysql_num_rows($this->result);
			$this->num_rows = $nr;
            switch($nr) {
                case 0 :
					$return = NULL; // return NULL rows
					break;
                case 1: // return one row ...
					if(1 != mysql_num_fields( $this->result)) {
						$return = mysql_fetch_object($this->result);    // as object
					} else {
                        $row    = mysql_fetch_row($this->result);       // or as single value
                        $return = $row[0];
                    }
                    break;
                default:
                    $return = array();
                    while( $obj = mysql_fetch_object($this->result)) array_push($return, $obj);
            }
        }
        return $return;
    }


    /**
     * Determines if a valid connection to the database exists
     *
     * @return boolean TRUE idf connectect or FALSE if not connected
     */
    public function IsConnected() {
        if (gettype($this->mysql_link) == "resource") {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Selects a different database and character set
     *
     * @param string $database Database name
     * @param string $charset (Optional) Character set (i.e. utf8)
     * @return boolean Returns TRUE on success or FALSE on error
     */
    public function SelectDatabase($database, $charset = "")
    {
        $return_value = true;

        if (! $charset) {
            $charset = $this->db_charset;
        }

        $this->ResetError();
        if (! (mysql_select_db($database))) {
            $this->SetError();
            $return_value = false;
        } else {
            if ((strlen($charset) > 0)) {
                if (! (mysql_query("SET CHARACTER SET '{$charset}'", $this->mysql_link))) {
                    $this->SetError();
                    $return_value = false;
                }
            }
        }
        return $return_value;
    }

    /**
     * Clears the internal variables from any error information
     *
     */
    private function ResetError() {
        $this->error_desc = '';
        $this->error_number = 0;
    }

    /**
     *  Show debug info
     */
    static function ShowDebugInfo($string=""){
        print "\n<!-- ".$string." --->\r\n";
    }

    /**
     * Sets the local variables with the last error information
     *
     * @param string $errorMessage The error description
     * @param integer $errorNumber The error number
     */
    private function SetError($errorMessage = '', $errorNumber = 0) {
        try {
            // get/set error message
            if (strlen($errorMessage) > 0) {
                $this->error_desc = $errorMessage;
            } else {
                if ($this->IsConnected()) {
                    $this->error_desc = mysql_error($this->mysql_link);
                } else {
                    $this->error_desc = mysql_error();
                }
            }
            // get/set error number
            if ($errorNumber <> 0) {
                $this->error_number = $errorNumber;
            } else {
				if($this->IsConnected()) {
					$this->error_number = @mysql_errno($this->mysql_link);
				} else {
					$this->error_number = @mysql_errno();
				}
			}
		} catch(Exception $e) {
			$this->error_desc = $e->getMessage();
			$this->error_number = -999;
		}

		if ($this->ThrowExceptions) {
			throw new Exception($this->error_desc);
		}
	}

    /**
     * Destructor: Closes the connection to the database
     *
     */
    public function __destruct() {
        $this->Close();
    }

    /**
     * Close current MySQL connection
     *
     * @return object Returns TRUE on success or FALSE on error
     */
    public function Close() {
		$this->ResetError();
		$success = $this->Release();

		if ($success) {
			$success = @mysql_close($this->mysql_link);

			if (! $success) {
				$this->SetError();
			} else {
				unset($this->sql);
				unset($this->result);
				unset($this->mysql_link);
			}
		}
		return $success;
	}

	/**
	 * Frees memory used by the query results and returns the function result
	 *
	 * @return boolean Returns TRUE on success or FALSE on failure
	 */
	public function Release() {
		$this->ResetError();

		if (! $this->result) {
			$success = true;
		} else {
			$success = @mysql_free_result($this->result);

			if(!$success) {
				$this->SetError();
			}
		}
		return $success;
	}
}// end
