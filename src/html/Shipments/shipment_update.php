<?php
	use models\Shipment;

	require_once __DIR__ . '/../../../vendor/autoload.php';

	$root = '../../../';

	function convertToNull($value) {
		return $value === '' ? null : $value;
	}

	function gestorErrors($errno, $errstr, $errfile, $errline) {
		throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
	}

	set_error_handler("gestorErrors");

	$shipment = null;
	$errorMessage = null;
	$status = null;

	try {
		$shipment = Shipment::find((int) ($_GET['shipment_id'] ?? $_POST['shipment_id'] ?? 0));
		if ($shipment === null) {
			throw new Exception("No s'ha trobat l'enviament.");
		}

		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$shipment->store_id = $_POST['store_id'] ?? '';
			$shipment->customer_id = $_POST['customer_id'] ?? '';
			$shipment->address_line1 = convertToNull($_POST['address_line1'] ?? '');
			$shipment->city = convertToNull($_POST['city'] ?? '');
			$shipment->state_province = convertToNull($_POST['state_province'] ?? '');
			$shipment->postal_code = convertToNull($_POST['postal_code'] ?? '');
			$shipment->country = convertToNull($_POST['country'] ?? '');
			$shipment->shipment_status = $_POST['shipment_status'] ?? '';

			$shipment->save();
			$status = true;
		}
	} catch (Error | Exception $e) {
		$errorMessage = "L'enviament no es pot modificar: " . $e->getMessage();
		$status = false;
	} finally {
		restore_error_handler();
	}
?>

<!DOCTYPE html>
<html lang="ca">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Modificar enviament</title>
		<link rel="stylesheet" href="<?= $root ?>src/css/estils.css">
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
	</head>
	<body>
		<?php require_once __DIR__ . '/../includes/header.php'; ?>
		<div id="content">
			<?php require_once __DIR__ . '/../includes/nav.php'; ?>
			<main id="section">
				<h3>Modificar enviament</h3>

				<?php if ($shipment !== null): ?>
					<form method="POST" action="">
						<div class="form-group">
							<label for="shipment_id">ID enviament:</label>
							<input class="form-control" type="number" id="shipment_id" name="shipment_id" value="<?= htmlspecialchars((string) $shipment->shipment_id) ?>" readonly required>
						</div>
						<div class="form-group">
							<label for="store_id">ID botiga:</label>
							<input class="form-control" type="number" id="store_id" name="store_id" value="<?= htmlspecialchars((string) $shipment->store_id) ?>" required>
						</div>
						<div class="form-group">
							<label for="customer_id">ID client:</label>
							<input class="form-control" type="number" id="customer_id" name="customer_id" value="<?= htmlspecialchars((string) $shipment->customer_id) ?>" required>
						</div>
						<div class="form-group">
							<label for="address_line1">Adreça:</label>
							<input class="form-control" type="text" id="address_line1" name="address_line1" value="<?= htmlspecialchars((string) $shipment->address_line1) ?>" maxlength="255">
						</div>
						<div class="form-group">
							<label for="city">Ciutat:</label>
							<input class="form-control" type="text" id="city" name="city" value="<?= htmlspecialchars((string) $shipment->city) ?>" maxlength="100">
						</div>
						<div class="form-group">
							<label for="state_province">Província / Estat:</label>
							<input class="form-control" type="text" id="state_province" name="state_province" value="<?= htmlspecialchars((string) $shipment->state_province) ?>" maxlength="100">
						</div>
						<div class="form-group">
							<label for="postal_code">Codi postal:</label>
							<input class="form-control" type="text" id="postal_code" name="postal_code" value="<?= htmlspecialchars((string) $shipment->postal_code) ?>" maxlength="20">
						</div>
						<div class="form-group">
							<label for="country">País:</label>
							<input class="form-control" type="text" id="country" name="country" value="<?= htmlspecialchars((string) $shipment->country) ?>" maxlength="100">
						</div>
						<div class="form-group">
							<label for="shipment_status">Estat de l'enviament:</label>
							<input class="form-control" type="text" id="shipment_status" name="shipment_status" value="<?= htmlspecialchars((string) $shipment->shipment_status) ?>" maxlength="100" required>
						</div>
						<button class="btn btn-primary" type="submit">Modificar enviament</button>
						<a href="shipments_list.php" class="btn btn-secondary">Tornar</a>
					</form>
				<?php endif; ?>

				<?php if ($status): ?>
					<p class="alert alert-success">Enviament modificat correctament.</p>
				<?php elseif ($errorMessage != null): ?>
					<p class="alert alert-danger"><?= htmlspecialchars($errorMessage) ?></p>
				<?php endif; ?>
			</main>
		</div>
		<?php require_once __DIR__ . '/../includes/footer.php'; ?>
	</body>
</html>
