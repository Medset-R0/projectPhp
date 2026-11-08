<?php
	use models\Shipment;

	require_once __DIR__ . '/../../../vendor/autoload.php';

	$root = '../../../';

	function gestorErrors($errno, $errstr, $errfile, $errline) {
		throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
	}

	set_error_handler("gestorErrors");

	$shipment = null;
	$errorMessage = null;

	try {
		$shipment = Shipment::find((int) ($_GET['shipment_id'] ?? 0));
		if ($shipment === null) {
			throw new Exception("No s'ha trobat l'enviament.");
		}
	} catch (Error | Exception $e) {
		$errorMessage = "Error al carregar l'enviament: " . $e->getMessage();
	} finally {
		restore_error_handler();
	}
?>

<!DOCTYPE html>
<html lang="ca">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Mostrar enviament</title>
		<link rel="stylesheet" href="<?= $root ?>src/css/estils.css">
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
	</head>
	<body>
		<?php require_once __DIR__ . '/../includes/header.php'; ?>
		<div id="content">
			<?php require_once __DIR__ . '/../includes/nav.php'; ?>
			<main id="section">
				<h3>Dades de l'enviament</h3>

				<?php if ($shipment !== null): ?>
					<form>
						<div class="form-group">
							<label for="shipment_id">ID enviament:</label>
							<input class="form-control" type="text" id="shipment_id" value="<?= htmlspecialchars((string) $shipment->shipment_id) ?>" readonly>
						</div>
						<div class="form-group">
							<label for="store_id">ID botiga:</label>
							<input class="form-control" type="text" id="store_id" value="<?= htmlspecialchars((string) $shipment->store_id) ?>" readonly>
						</div>
						<div class="form-group">
							<label for="customer_id">ID client:</label>
							<input class="form-control" type="text" id="customer_id" value="<?= htmlspecialchars((string) $shipment->customer_id) ?>" readonly>
						</div>
						<div class="form-group">
							<label for="address_line1">Adreça:</label>
							<input class="form-control" type="text" id="address_line1" value="<?= htmlspecialchars((string) $shipment->address_line1) ?>" readonly>
						</div>
						<div class="form-group">
							<label for="city">Ciutat:</label>
							<input class="form-control" type="text" id="city" value="<?= htmlspecialchars((string) $shipment->city) ?>" readonly>
						</div>
						<div class="form-group">
							<label for="state_province">Província / Estat:</label>
							<input class="form-control" type="text" id="state_province" value="<?= htmlspecialchars((string) $shipment->state_province) ?>" readonly>
						</div>
						<div class="form-group">
							<label for="postal_code">Codi postal:</label>
							<input class="form-control" type="text" id="postal_code" value="<?= htmlspecialchars((string) $shipment->postal_code) ?>" readonly>
						</div>
						<div class="form-group">
							<label for="country">País:</label>
							<input class="form-control" type="text" id="country" value="<?= htmlspecialchars((string) $shipment->country) ?>" readonly>
						</div>
						<div class="form-group">
							<label for="shipment_status">Estat de l'enviament:</label>
							<input class="form-control" type="text" id="shipment_status" value="<?= htmlspecialchars((string) $shipment->shipment_status) ?>" readonly>
						</div>
						<a href="shipments_list.php" class="btn btn-secondary">Tornar</a>
					</form>
				<?php else: ?>
					<p class="alert alert-danger"><?= htmlspecialchars($errorMessage) ?></p>
					<a href="shipments_list.php" class="btn btn-secondary">Tornar</a>
				<?php endif; ?>
			</main>
		</div>
		<?php require_once __DIR__ . '/../includes/footer.php'; ?>
	</body>
</html>
