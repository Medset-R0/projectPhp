<?php
	namespace models;

	use config\Database;

	class Store extends Model {
		protected static $table = 'stores';

		public function __construct(
			public ?int $store_id=null,
			public ?string $store_name=null,
			public ?string $web_address=null,
			public ?string $address_line1=null,
			public ?string $city=null,
			public ?string $state_province=null,
			public ?string $postal_code=null,
			public ?string $country=null,
			public ?float $latitude=null,
			public ?float $longitude=null
		) { }

		public function save() {
			error_reporting(E_ALL);
			try {
				$db = new Database();
				$db->connectDB('C:/temp/config.db');

				$table = static::$table;

				if (isset($this->store_id)) {
					$sql = "INSERT INTO $table (store_id, store_name, web_address, address_line1, city, state_province, postal_code, country, latitude, longitude)
							VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
							ON DUPLICATE KEY UPDATE
							store_name      = VALUES (store_name),
							web_address     = VALUES (web_address),
							address_line1   = VALUES (address_line1),
							city            = VALUES (city),
							state_province  = VALUES (state_province),
							postal_code     = VALUES (postal_code),
							country         = VALUES (country),
							latitude        = VALUES (latitude),
							longitude       = VALUES (longitude)";
					$stmt = $db->conn->prepare($sql);
					$stmt->bind_param( "isssssssdd",
										$this->store_id,
										$this->store_name,
										$this->web_address,
										$this->address_line1,
										$this->city,
										$this->state_province,
										$this->postal_code,
										$this->country,
										$this->latitude,
										$this->longitude
									);

					$stmt->execute();
				} else {
					throw new \Exception ("ID botiga no informat.");
				}

				$db->conn->commit();
			} catch (\mysqli_sql_exception $e) {
				if ($db->conn)
					$db->conn->rollback();
				throw new \mysqli_sql_exception($e->getMessage());
			} finally {
				if ($db->conn)
					$db->closeDB();
			}
		}

		public function destroy() {
			error_reporting(E_ALL);
			try {
				$db = new Database();
				$db->connectDB('C:/temp/config.db');

				$table = static::$table;

				if (isset($this->store_id)) {
					$sql = "SELECT * FROM $table WHERE store_id = ?";
					$stmt = $db->conn->prepare($sql);
					$stmt->bind_param( "i", $this->store_id );
					$stmt->execute();
					$result = $stmt->get_result();

					if ($result->num_rows == 1) {
						$sql = "DELETE FROM $table
								WHERE store_id = ?";
						$stmt = $db->conn->prepare($sql);
						$stmt->bind_param( "i", $this->store_id );
						$stmt->execute();
					} else {
						throw new \Exception ("La botiga no existeix.");
					}
				} else {
					throw new \Exception ("ID botiga no informat.");
				}
				$db->conn->commit();
			} catch (\mysqli_sql_exception $e) {
				if ($db->conn)
					$db->conn->rollback();
				throw new \mysqli_sql_exception($e->getMessage());
			} finally {
				if ($db->conn)
					$db->closeDB();
			}
		}
	}
?>
