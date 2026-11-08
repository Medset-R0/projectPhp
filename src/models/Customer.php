<?php
	namespace models;

	use config\Database;

	class Customer extends Model {
		protected static $table = 'customers';

		public function __construct(
			public ?int $customer_id=null,
			public ?string $email_address=null,
			public ?string $first_name=null,
			public ?string $last_name=null,
			public ?string $phone_number=null,
			public ?string $address_line1=null,
			public ?string $city=null,
			public ?string $state_province=null,
			public ?string $postal_code=null,
			public ?string $country=null,
			public ?string $created_at=null
		) { }

		public function save() {
			error_reporting(E_ALL);
			try {
				$db = new Database();
				$db->connectDB('C:/temp/config.db');

				$table = static::$table;

				if (isset($this->customer_id)) {
					$sql = "INSERT INTO $table (customer_id, email_address, first_name, last_name, phone_number, address_line1, city, state_province, postal_code, country, created_at)
							VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, COALESCE(?, NOW()))
							ON DUPLICATE KEY UPDATE
							email_address   = VALUES (email_address),
							first_name      = VALUES (first_name),
							last_name       = VALUES (last_name),
							phone_number    = VALUES (phone_number),
							address_line1   = VALUES (address_line1),
							city            = VALUES (city),
							state_province  = VALUES (state_province),
							postal_code     = VALUES (postal_code),
							country         = VALUES (country)";
					$stmt = $db->conn->prepare($sql);
					$stmt->bind_param( "issssssssss",
										$this->customer_id,
										$this->email_address,
										$this->first_name,
										$this->last_name,
										$this->phone_number,
										$this->address_line1,
										$this->city,
										$this->state_province,
										$this->postal_code,
										$this->country,
										$this->created_at
									);

					$stmt->execute();
				} else {
					throw new \Exception ("ID client no informat.");
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

				if (isset($this->customer_id)) {
					$sql = "SELECT * FROM $table WHERE customer_id = ?";
					$stmt = $db->conn->prepare($sql);
					$stmt->bind_param( "i", $this->customer_id );
					$stmt->execute();
					$result = $stmt->get_result();

					if ($result->num_rows == 1) {
						$sql = "DELETE FROM $table
								WHERE customer_id = ?";
						$stmt = $db->conn->prepare($sql);
						$stmt->bind_param( "i", $this->customer_id );
						$stmt->execute();
					} else {
						throw new \Exception ("El client no existeix.");
					}
				} else {
					throw new \Exception ("ID client no informat.");
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
