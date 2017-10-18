<?php

defined('SC_SAFETY_CONST') or die;

class Db_Conn
{
    protected static $db;
    protected $db_host    = 'localhost';
    protected $db_name    = '***';
    protected $db_pass    = '***';
    protected $db_user    = '***';
    protected $db_charset = 'utf8';

    private function __construct ()
    {
        $dsn = 'mysql:host=' . $this->db_host . '; '
             . 'dbname='     . $this->db_name . '; '
             . 'charset='    . $this->db_charset;

        $attr = array(
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        );

        try {

            self::$db = new PDO($dsn, $this->db_user, $this->db_pass, $attr);

        } catch (PDOException $e) {

            $filename = SC_LOGS_DIR . 'error.log';
            $data     = date(SC_TIME_FORMAT) . ' Connection Error: '
                      . $e->getMessage() . BR;
            file_put_contents($filename, $data, FILE_APPEND | LOCK_EX);

        }
    }

    public static function getConnection ()
    {
        if (! self::$db) {
            new Db_Conn();
        }
        return self::$db;
    }
}

$dbh = Db_Conn::getConnection();

require_once SC_MODEL_DIR . 'db_delete.php';
require_once SC_MODEL_DIR . 'db_insert.php';
require_once SC_MODEL_DIR . 'db_select.php';
require_once SC_MODEL_DIR . 'db_update.php';
