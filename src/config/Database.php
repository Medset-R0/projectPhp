<?php
    namespace config;

    class Database {
        public function __construct(public ?\mysqli $conn = null) {}

        public static function loadConfig($fitxer): array {
            $config = [];

            if (file_exists($fitxer)) {
                $linies = file($fitxer, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                
                foreach ($linies as $linia) {
                    $linia = trim($linia);
                    if (strpos(trim($linia), '#') !== 0) {
                        list($clau, $valor) = explode('=', $linia, 2);
                        $config[trim($clau)] = trim($valor);
                    }
                }
            } else {
                die("El fitxer de configuració no existeix.");
            }

            return $config;
        }

        public function connectDB($configPath) {
            $config = self::loadConfig($configPath);

            $this->conn = new \mysqli($config['DB_HOST'], $config['DB_USERNAME'], $config['DB_PASSWORD'], $config['DB_DATABASE'], $config['DB_PORT']);
            $this->conn->autocommit(false);
            $this->conn->begin_transaction();

            if ($this->conn->connect_error) {
                die("Error en la connexió: " . $this->conn->connect_error);
            }
        }

        public function closeDB() {
            if ($this->conn) {
                $this->conn->close();
            }
        }
    }

?>