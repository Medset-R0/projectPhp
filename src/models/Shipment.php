<?php
	namespace models;

	use config\Database;

	class Shipment extends Model {
		protected static $table = 'shipments';

		public function __construct(
			public ?int $shipment_id=null,
			public ?int $store_id=null,
			public ?int $customer_id=null,
			public ?string $address_line1=null,
			public ?string $city=null,
			public ?string $state_province=null,
			public ?string $postal_code=null,
			public ?string $country=null,
			public ?string $shipment_status=null
		) { }

		public function save() {
			error_reporting(E_ALL);
			try {
				$db = new Database();
				$db->connectDB('C:/temp/config.db');

				$table = static::$table;

				if (isset($this->shipment_id)) {
					$sql = "INSERT INTO $table (shipment_id, store_id, customer_id, address_line1, city, state_province, postal_code, country, shipment_status)
							VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
							ON DUPLICATE KEY UPDATE
							store_id        = VALUES (store_id),
							customer_id     = VALUES (customer_id),
							address_line1   = VALUES (address_line1),
							city            = VALUES (city),
							state_province  = VALUES (state_province),
							postal_code     = VALUES (postal_code),
							country         = VALUES (country),
							shipment_status = VALUES (shipment_status)";
					$stmt = $db->conn->prepare($sql);
					$stmt->bind_param( "iiissssss",
										$this->shipment_id,
										$this->store_id,
										$this->customer_id,
										$this->address_line1,
										$this->city,
										$this->state_province,
										$this->postal_code,
										$this->country,
										$this->shipment_status
									);

					$stmt->execute();
				} else {
					throw new \Exception ("ID enviament no informat.");
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

				if (isset($this->shipment_id)) {
					$sql = "SELECT * FROM $table WHERE shipment_id = ?";
					$stmt = $db->conn->prepare($sql);
					$stmt->bind_param( "i", $this->shipment_id );
					$stmt->execute();
					$result = $stmt->get_result();

					if ($result->num_rows == 1) {
						$sql = "DELETE FROM $table
								WHERE shipment_id = ?";
						$stmt = $db->conn->prepare($sql);
						$stmt->bind_param( "i", $this->shipment_id );
						$stmt->execute();
					} else {
						throw new \Exception ("L'enviament no existeix.");
					}
				} else {
					throw new \Exception ("ID enviament no informat.");
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
