<?php
// Define development mode
define('DEVELOPMENT_MODE', true); // Set to false in production

class LoggedMysqli extends mysqli {
    private $logs = [];

    public function __construct($host, $user, $pass, $db) {
        parent::__construct($host, $user, $pass, $db);
        if (DEVELOPMENT_MODE && session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function query($query, $resultmode = MYSQLI_STORE_RESULT): mysqli_result|bool {
        if (!DEVELOPMENT_MODE) {
            return parent::query($query, $resultmode);
        }

        $start_time = microtime(true);
        $result = parent::query($query, $resultmode);
        $end_time = microtime(true);
        $execution_time = round(($end_time - $start_time) * 1000, 2); // in ms

        $status = $result === false ? 'FAILED' : 'SUCCESS';
        $this->logQuery($query, $execution_time, $status);

        return $result;
    }

    public function prepare($query): mysqli_stmt|false {
        if (!DEVELOPMENT_MODE) {
            return parent::prepare($query);
        }

        // For prepared statements, we'll log when executed
        $stmt = parent::prepare($query);
        if ($stmt) {
            $this->logQuery($query, 0, 'PREPARED');
        }
        return $stmt;
    }

    private function logQuery($sql, $time, $status) {
        if (!DEVELOPMENT_MODE) return;

        $log_entry = [
            'timestamp' => date('Y-m-d H:i:s'),
            'sql' => trim($sql),
            'execution_time' => $time,
            'status' => $status,
            'type' => $this->getQueryType($sql)
        ];

        if (!isset($_SESSION['query_logs'])) {
            $_SESSION['query_logs'] = [];
        }
        $_SESSION['query_logs'][] = $log_entry;
    }

    private function getQueryType($sql) {
        $sql = strtoupper(trim($sql));
        if (strpos($sql, 'SELECT') === 0) return 'SELECT';
        if (strpos($sql, 'INSERT') === 0) return 'INSERT';
        if (strpos($sql, 'UPDATE') === 0) return 'UPDATE';
        if (strpos($sql, 'DELETE') === 0) return 'DELETE';
        if (strpos($sql, 'CREATE') === 0) return 'CREATE';
        if (strpos($sql, 'ALTER') === 0) return 'ALTER';
        if (strpos($sql, 'DROP') === 0) return 'DROP';
        return 'OTHER';
    }

    public static function getLogs() {
        if (!DEVELOPMENT_MODE || !isset($_SESSION['query_logs'])) {
            return [];
        }
        return $_SESSION['query_logs'];
    }

    public static function clearLogs() {
        if (DEVELOPMENT_MODE) {
            $_SESSION['query_logs'] = [];
        }
    }
}

// Function to get query logs for frontend
function get_query_logs() {
    return LoggedMysqli::getLogs();
}

// Function to clear query logs
function clear_query_logs() {
    LoggedMysqli::clearLogs();
}
?>
