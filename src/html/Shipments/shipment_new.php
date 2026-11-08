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

	$errorMessage = null;
	$status = null;

	try {
		if ($_SERVER["REQUEST_METHOD"] == "POST") {
			$shipment = new Shipment(
				shipment_id: $_POST['shipment_id'],
				store_id: $_POST['store_id'],
				customer_id: $_POST['customer_id'],
				address_line1: convertToNull($_POST['address_line1']),
				city: convertToNull($_POST['city']),
				state_province: convertToNull($_POST['state_province']),
				postal_code: convertToNull($_POST['postal_code']),
				country: convertToNull($_POST['country']),
				shipment_status: $_POST['shipment_status']
			);

			$shipment->save();
			$status = true;
		}
	} catch (Error | Exception $e) {
		$errorMessage = "L'enviament no es pot afegir: " . $e->getMessage();
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
		<title>Formulari de enviament</title>
		<link rel="stylesheet" href="<?= $root ?>src/css/estils.css">
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
	</head>
	<body>
		<?php require_once __DIR__ . '/../includes/header.php'; ?>
		<div id="content">
			<?php require_once __DIR__ . '/../includes/nav.php'; ?>
			<main id="section">
				<h3>Afegir un Nou Enviament</h3>

				<form method="POST" action="">
					<div class="form-group">
						<label for="shipment_id">ID enviament:</label>
						<input class="form-control" type="number" id="shipment_id" name="shipment_id" required>
					</div>
					<div class="form-group">
						<label for="store_id">ID botiga:</label>
						<input class="form-control" type="number" id="store_id" name="store_id" required>
					</div>
					<div class="form-group">
						<label for="customer_id">ID client:</label>
						<input class="form-control" type="number" id="customer_id" name="customer_id" required>
					</div>
					<div class="form-group">
						<label for="address_line1">Adreça:</label>
						<input class="form-control" type="text" id="address_line1" name="address_line1" maxlength="255">
					</div>
					<div class="form-group">
						<label for="city">Ciutat:</label>
						<input class="form-control" type="text" id="city" name="city" maxlength="100">
					</div>
					<div class="form-group">
						<label for="state_province">Província / Estat:</label>
						<input class="form-control" type="text" id="state_province" name="state_province" maxlength="100">
					</div>
					<div class="form-group">
						<label for="postal_code">Codi postal:</label>
						<input class="form-control" type="text" id="postal_code" name="postal_code" maxlength="20">
					</div>
					<div class="form-group">
						<label for="country">País:</label>
						<input class="form-control" type="text" id="country" name="country" maxlength="100">
					</div>
					<div class="form-group">
						<label for="shipment_status">Estat de l'enviament:</label>
						<input class="form-control" type="text" id="shipment_status" name="shipment_status" maxlength="100" required>
					</div>
					<button class="btn btn-primary" type="submit">Afegir Enviament</button>

					<a href="shipments_list.php" class="btn btn-secondary">Tornar</a>
				</form>

				<?php if ($status): ?>
					<p class="alert alert-success">Enviament afegit correctament.</p>
				<?php elseif ($errorMessage != null): ?>
					<p class="alert alert-danger"><?= htmlspecialchars($errorMessage) ?></p>
				<?php endif; ?>
			</main>
		</div>
		<?php require_once __DIR__ . '/../includes/footer.php'; ?>
	</body>
</html>
