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
	$status = null;

	try {
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$shipment = new Shipment((int) $_POST['shipment_id']);
			$shipment->destroy();
			$status = true;
		} else {
			$shipment = Shipment::find((int) ($_GET['shipment_id'] ?? 0));
			if ($shipment === null) {
				throw new Exception("No s'ha trobat l'enviament.");
			}
		}
	} catch (Error | Exception $e) {
		$errorMessage = "L'enviament no es pot eliminar: " . $e->getMessage();
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
		<title>Eliminar enviament</title>
		<link rel="stylesheet" href="<?= $root ?>src/css/estils.css">
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
	</head>
	<body>
		<?php require_once __DIR__ . '/../includes/header.php'; ?>
		<div id="content">
			<?php require_once __DIR__ . '/../includes/nav.php'; ?>
			<main id="section">
				<h3>Eliminar enviament</h3>

				<?php if ($status): ?>
					<p class="alert alert-success">Enviament eliminat correctament.</p>
				<?php elseif ($errorMessage != null): ?>
					<p class="alert alert-danger"><?= htmlspecialchars($errorMessage) ?></p>
				<?php else: ?>
					<p class="alert alert-warning">Segur que vols eliminar l'enviament <strong><?= htmlspecialchars((string) $shipment->shipment_id) ?> - <?= htmlspecialchars((string) $shipment->shipment_status) ?></strong>?</p>
					<form method="POST" action="">
						<input type="hidden" name="shipment_id" value="<?= htmlspecialchars((string) $shipment->shipment_id) ?>">
						<button class="btn btn-danger" type="submit">Eliminar</button>
					</form>
				<?php endif; ?>

				<a href="shipments_list.php" class="btn btn-secondary">Tornar</a>
			</main>
		</div>
		<?php require_once __DIR__ . '/../includes/footer.php'; ?>
	</body>
</html>
