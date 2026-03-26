<?php declare(strict_types=1);

namespace src\Infrastructure;

use mysqli;
use RuntimeException;

class DatabaseConnector {
    private static ?self $instance = null;
    private mysqli $dbconnection;

    private function __construct() {
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

        $configPath = __DIR__ . '/../../config/database.ini';
        $config = parse_ini_file($configPath, false, INI_SCANNER_TYPED);

        if ($config === false) {
            throw new RuntimeException('Unable to read database configuration');
        }

        $host = (string) ($config['host'] ?? 'db');
        $username = (string) ($config['username'] ?? 'admin');
        $password = (string) ($config['password'] ?? 'admin');
        $database = (string) ($config['dbname'] ?? 'progettoPHP');

        $this->dbconnection = new mysqli($host, $username, $password, $database);
        $this->dbconnection->set_charset('utf8mb4');
    }

    public static function getInstance(): self {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function getConnection(): mysqli {
        return $this->dbconnection;
    }
}