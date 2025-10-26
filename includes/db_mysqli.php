<?php
/**************************************************************************
 *                                                                        *
 *    4images - A Web Based Image Gallery Management System               *
 *    ----------------------------------------------------------------    *
 *                                                                        *
 *             File: db_mysqli.php                                        *
 *        Copyright: (C) 2002-2023 4homepages.de                          *
 *            Email: 4images@4homepages.de                                *
 *              Web: http://www.4homepages.de                             *
 *    Scriptversion: 1.10                                                 *
 *                                                                        *
 **************************************************************************
 *                                                                        *
 *    Dieses Script ist KEINE Freeware. Bitte lesen Sie die Lizenz-       *
 *    bedingungen (Lizenz.txt) für weitere Informationen.                 *
 *    ---------------------------------------------------------------     *
 *    This script is NOT freeware! Please read the Copyright Notice       *
 *    (Licence.txt) for further information.                              *
 *                                                                        *
 *************************************************************************/
if (!defined('ROOT_PATH')) {
    die("Security violation");
}

class Db
{
    public $no_error = 0;
    public $connection;
    public $query_result = null;
    public $query_count = 0;
    public $query_time = 0;
    public $query_array = [];
    public $table_fields = [];

    public function __construct($db_host, $db_user, $db_password = "", $db_name = "", $db_pconnect = 0)
    {
        $connect_handle = "mysqli_connect";
        if (!$this->connection = @$connect_handle($db_host, $db_user, $db_password)) {
            $this->error("Could not connect to the database server (".safe_htmlspecialchars($db_host).", ".safe_htmlspecialchars($db_user).").", 1);
        }
        if ($db_name != "") {
            if (!mysqli_select_db($this->connection, $db_name)) {
                @mysqli_close($this->connection);
                $this->error("Could not select database (".safe_htmlspecialchars($db_name).").", 1);
            }
        }
        mysqli_set_charset($this->connection, 'utf8');
        return $this->connection;
    }

    public function escape($value)
    {
        return mysqli_real_escape_string($this->connection, $value);
    }

    public function close()
    {
        if ($this->connection) {
            if ($this->query_result instanceof mysqli_result && isset($this->query_result->field_count)) {
                mysqli_free_result($this->query_result);
            }
            return @mysqli_close($this->connection);
        } else {
            return false;
        }
    }

    public function query($query = "")
    {
        unset($this->query_result);
        if ($query != "") {
            if ((defined("PRINT_QUERIES") && PRINT_QUERIES == 1) || (defined("PRINT_STATS") && PRINT_STATS == 1)) {
                $startsqltime = explode(" ", microtime());
            }
            if (!$this->query_result = @mysqli_query($this->connection, $query)) {
                $this->error("<b>Bad SQL Query</b>: ".safe_htmlspecialchars($query)."<br /><b>".safe_htmlspecialchars(mysqli_error($this->connection))."</b>");
            }
            if ((defined("PRINT_QUERIES") && PRINT_QUERIES == 1) || (defined("PRINT_STATS") && PRINT_STATS == 1)) {
                $endsqltime = explode(" ", microtime());
                $totalsqltime = round($endsqltime[0]-$startsqltime[0]+$endsqltime[1]-$startsqltime[1], 3);
                $this->query_time += $totalsqltime;
                $this->query_count++;
            }
            if (defined("PRINT_QUERIES") && PRINT_QUERIES == 1) {
                $query_stats = htmlentities($query);
                $query_stats .= "<br><b>Querytime:</b> ".$totalsqltime;
                $this->query_array[] = $query_stats;
            }
            return $this->query_result;
        }
    }

    /**
     * Execute prepared statement with parameter binding (PHP 8.4+ secure)
     *
     * @param string $query SQL query with ? placeholders
     * @param array $params Array of parameters to bind
     * @param string|null $types Optional: Type string (i, d, s, b) - auto-detected if not provided
     * @return mysqli_result|bool Query result or false on error
     */
    public function prepared_query($query, $params = [], ?string $types = null)
    {
        if (empty($query)) {
            return false;
        }

        $stmt = mysqli_prepare($this->connection, $query);
        if (!$stmt) {
            $this->error("<b>Prepared Statement Error</b>: ".safe_htmlspecialchars($query)."<br /><b>".safe_htmlspecialchars(mysqli_error($this->connection))."</b>");
            return false;
        }

        // Bind parameters if provided
        if (!empty($params)) {
            // Auto-detect types if not provided
            if ($types === null) {
                $types = '';
                foreach ($params as $param) {
                    if (is_int($param)) {
                        $types .= 'i';
                    } elseif (is_float($param)) {
                        $types .= 'd';
                    } elseif (is_string($param)) {
                        $types .= 's';
                    } else {
                        $types .= 'b'; // blob
                    }
                }
            }

            mysqli_stmt_bind_param($stmt, $types, ...$params);
        }

        // Execute statement
        if (!mysqli_stmt_execute($stmt)) {
            $this->error("<b>Statement Execution Error</b>: ".safe_htmlspecialchars(mysqli_stmt_error($stmt)));
            mysqli_stmt_close($stmt);
            return false;
        }

        // Get result
        $this->query_result = mysqli_stmt_get_result($stmt);
        mysqli_stmt_close($stmt);

        return $this->query_result;
    }

    public function fetch_array(mysqli_result $query_result = null, $assoc = 0)
    {
        if ($query_result != null) {
            $this->query_result = $query_result;
        }
        if ($this->query_result) {
            return ($assoc) ? mysqli_fetch_assoc($this->query_result) : mysqli_fetch_array($this->query_result);
        }
    }

    public function free_result(mysqli_result $query_result = null)
    {
        if ($query_result != null) {
            $this->query_result = $query_result;
        }
        if ($this->query_result instanceof mysqli_result  && isset($this->query_result->field_count)) {
            return mysqli_free_result($this->query_result);
        }
    }

    public function query_firstrow($query = "")
    {
        if ($query != "") {
            $this->query($query);
        }
        $result = $this->fetch_array($this->query_result);
        $this->free_result();
        return $result;
    }

    public function get_numrows(mysqli_result $query_result = null)
    {
        if ($query_result != null) {
            $this->query_result = $query_result;
        }
        return mysqli_num_rows($this->query_result);
    }

    public function get_insert_id()
    {
        return ($this->connection) ? @mysqli_insert_id($this->connection) : 0;
    }

    public function get_next_id($column = "", $table = "")
    {
        if (!empty($column) && !empty($table)) {
            $sql = "SELECT MAX($column) AS max_id
              FROM $table";
            $row = $this->query_firstrow($sql);
            return (($row['max_id'] + 1) > 0) ? $row['max_id'] + 1 : 1;
        } else {
            return null;
        }
    }

    public function get_numfields(mysqli_result $query_result = null)
    {
        if ($query_result != null) {
            $this->query_result = $query_result;
        }
        return @mysqli_num_fields($this->query_result);
    }

    public function get_fieldname($offset, mysqli_result $query_result = null)
    {
        if ($query_result != null) {
            $this->query_result = $query_result;
        }
        mysqli_field_seek($this->query_result, $offset);
        $finfo = @mysqli_fetch_field($this->query_result);
        return $finfo->name;
    }

    public function get_fieldtype($offset, mysqli_result $query_result = null)
    {
        if ($query_result != null) {
            $this->query_result = $query_result;
        }
        mysqli_field_seek($this->query_result, $offset);
        $finfo = @mysqli_fetch_field($this->query_result);
        return $finfo->type;
    }

    public function affected_rows()
    {
        return ($this->connection) ? @mysqli_affected_rows($this->connection) : 0;
    }

    public function is_empty($query = "")
    {
        if ($query != "") {
            $this->query($query);
        }
        return (!mysqli_num_rows($this->query_result)) ? 1 : 0;
    }

    public function not_empty($query = "")
    {
        if ($query != "") {
            $this->query($query);
        }
        return (!mysqli_num_rows($this->query_result)) ? 0 : 1;
    }

    public function get_table_fields($table)
    {
        if (!empty($this->table_fields[$table])) {
            return $this->table_fields[$table];
        }
        $this->table_fields[$table] = [];
        $result = $this->query("SHOW FIELDS FROM $table");
        while ($row = $this->fetch_array($result)) {
            $this->table_fields[$table][$row['Field']] = $row['Type'];
        }
        return $this->table_fields[$table];
    }

    public function error($errmsg, $halt = 0)
    {
        if (!$this->no_error) {
            global $user_info;
            if (!defined("4IMAGES_ACTIVE") || (isset($user_info['user_level']) && $user_info['user_level'] == ADMIN)) {
                echo "<br /><font color='#FF0000'><b>DB Error</b></font>: ".$errmsg."<br />";
            } else {
                // Enhanced error display for debugging (ALT+Click to show details)
                // Source: https://www.4homepages.de/forum/index.php?topic=24177.0
                echo "<br /><span onclick=\"if(event.altKey)this.getElementsByTagName('SPAN')[0].style.display='block';\"><font color='#FF0000'><b>An unexpected error occured. Please try again later.</b></font><span style=\"display:none;\"><pre style=\"border:1px solid black;padding:3px;\">".$errmsg."</pre></span></span><br />";
            }
            if ($halt) {
                exit;
            }
        }
    }
} // end of class
