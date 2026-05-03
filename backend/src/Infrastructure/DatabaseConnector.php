<?php declare(strict_types=1);

namespace src\Infrastructure;

use mysqli;
use RuntimeException;

/**
 * DatabaseConnector — gestisce la connessione al database MariaDB.
 *
 * Usa il pattern Singleton: esiste una sola istanza per tutta la durata della richiesta.
 * Questo evita di aprire una nuova connessione ad ogni repository che ne ha bisogno.
 *
 * I parametri di connessione (host, utente, password, nome DB) vengono letti
 * dal file conf/database.ini, che non è versionato nel repository per sicurezza.
 */
class DatabaseConnector {
    private static ?self $instance = null;
    private mysqli $dbconnection;

    private function __construct() {
        // Abilita le eccezioni mysqli: invece di silenziare gli errori SQL,
        // viene lanciata un'eccezione che risale fino al gestore globale in index.php.
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

        $configPath = __DIR__ . '/../../conf/database.ini';
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
        // Prima chiamata: crea la connessione. Chiamate successive: restituisce quella esistente.
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function getConnection(): mysqli {
        return $this->dbconnection;
    }
}