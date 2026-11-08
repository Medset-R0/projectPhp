<?php

    namespace models;

    use config\Database;

    abstract class Model {
        public abstract function save();
        public abstract function destroy();

        public static function all() : array {
            try {
                $db = new Database();
                $db->connectDB('C:/temp/config.db');

                $table = static::$table;  

                $columns = implode(', ', self::getTableColumns($db, $table));

                $sql = "SELECT $columns FROM $table ORDER BY 1";
                $result = $db->conn->query($sql);

                $rows = [];
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $objecte = new static( ...array_values($row) );

                        $rows[] = $objecte;
                    }
                }

                return $rows;
            } catch (\mysqli_sql_exception $e) {
                throw new \mysqli_sql_exception($e->getMessage()); 
            } finally {
                if ($db->conn)
                    $db->closeDB(); 
            } 
        }

        public static function find(int|string $id): static|null {
            try {
                $db = new Database();
                $db->connectDB('C:/temp/config.db');

                $table = static::$table;

                $primaryKey = self::getPrimaryKey($db, $table);
                if ($primaryKey === null) {
                    throw new \RuntimeException("No s'ha pogut trobar la clau primària de la taula $table.");
                }

                $columns = implode(', ', self::getTableColumns($db, $table));

                $sql = "SELECT $columns FROM $table WHERE $primaryKey = ?";
                $stmt = $db->conn->prepare($sql);
                $stmt->bind_param('s', $id);
                $stmt->execute();
                $row = $stmt->get_result()->fetch_assoc();
                
                return $row === null ? null : new static(...array_values($row));
            } catch (\mysqli_sql_exception $e) {
                throw new \mysqli_sql_exception($e->getMessage());
            } finally {
                if ($db->conn)
                    $db->closeDB();
            }
        }

        public static function getTableColumns($db, $table) : array {
            $databaseName = $db->conn->query("SELECT DATABASE()")->fetch_row()[0];

            $sql = "SELECT column_name 
                    FROM information_schema.columns 
                    WHERE table_schema = '$databaseName' 
                    AND table_name = '$table' 
                    ORDER BY ordinal_position";

            $result = $db->conn->query($sql);

            $columns = [];
            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $columns[] = $row['COLUMN_NAME'];
                }
            }

            return $columns;
        }

        public static function getPrimaryKey($db, $table) {
            $databaseName = $db->conn->query("SELECT DATABASE()")->fetch_row()[0];
            
            $sql = "SELECT column_name 
                    FROM information_schema.key_column_usage 
                    WHERE table_schema = '$databaseName' 
                    AND table_name = '$table' 
                    AND constraint_name = 'PRIMARY'";
            $result = $db->conn->query($sql);
            $row = $result->fetch_assoc();
            
            return $row['COLUMN_NAME'] ?? null;
        }

        public static function getParamTypes(array $values) : string {
            $types = '';
            foreach ($values as $v)
                if (is_int($v))
                    $types .= 'i';
                elseif (is_float($v))
                    $types .= 'd';
                else
                    $types .= 's';

            return $types;
        }

    }

?>
