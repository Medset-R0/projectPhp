<?php
	use models\Store;

	require_once __DIR__ . '/../../../vendor/autoload.php';

	$root = '../../../';

	function gestorErrors($errno, $errstr, $errfile, $errline) {
		throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
	}

	set_error_handler("gestorErrors");

	$store = null;
	$errorMessage = null;

	try {
		$store = Store::find((int) ($_GET['store_id'] ?? 0));
		if ($store === null) {
			throw new Exception("No s'ha trobat la botiga.");
		}
	} catch (Error | Exception $e) {
		$errorMessage = "Error al carregar la botiga: " . $e->getMessage();
	} finally {
		restore_error_handler();
	}
?>

<!DOCTYPE html>
<html lang="ca">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Mostrar botiga</title>
		<link rel="stylesheet" href="<?= $root ?>src/css/estils.css">
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
	</head>
	<body>
		<?php require_once __DIR__ . '/../includes/header.php'; ?>
		<div id="content">
			<?php require_once __DIR__ . '/../includes/nav.php'; ?>
			<main id="section">
				<h3>Dades de la botiga</h3>

				<?php if ($store !== null): ?>
					<form>
						<div class="form-group">
							<label for="store_id">ID botiga:</label>
							<input class="form-control" type="text" id="store_id" value="<?= htmlspecialchars((string) $store->store_id) ?>" readonly>
						</div>
						<div class="form-group">
							<label for="store_name">Nom:</label>
							<input class="form-control" type="text" id="store_name" value="<?= htmlspecialchars((string) $store->store_name) ?>" readonly>
						</div>
						<div class="form-group">
							<label for="web_address">Adreça web:</label>
							<input class="form-control" type="text" id="web_address" value="<?= htmlspecialchars((string) $store->web_address) ?>" readonly>
						</div>
						<div class="form-group">
							<label for="address_line1">Adreça:</label>
							<input class="form-control" type="text" id="address_line1" value="<?= htmlspecialchars((string) $store->address_line1) ?>" readonly>
						</div>
						<div class="form-group">
							<label for="city">Ciutat:</label>
							<input class="form-control" type="text" id="city" value="<?= htmlspecialchars((string) $store->city) ?>" readonly>
						</div>
						<div class="form-group">
							<label for="state_province">Província / Estat:</label>
							<input class="form-control" type="text" id="state_province" value="<?= htmlspecialchars((string) $store->state_province) ?>" readonly>
						</div>
						<div class="form-group">
							<label for="postal_code">Codi postal:</label>
							<input class="form-control" type="text" id="postal_code" value="<?= htmlspecialchars((string) $store->postal_code) ?>" readonly>
						</div>
						<div class="form-group">
							<label for="country">País:</label>
							<input class="form-control" type="text" id="country" value="<?= htmlspecialchars((string) $store->country) ?>" readonly>
						</div>
						<div class="form-group">
							<label for="latitude">Latitud:</label>
							<input class="form-control" type="text" id="latitude" value="<?= htmlspecialchars((string) $store->latitude) ?>" readonly>
						</div>
						<div class="form-group">
							<label for="longitude">Longitud:</label>
							<input class="form-control" type="text" id="longitude" value="<?= htmlspecialchars((string) $store->longitude) ?>" readonly>
						</div>
						<a href="stores_list.php" class="btn btn-secondary">Tornar</a>
					</form>
				<?php else: ?>
					<p class="alert alert-danger"><?= htmlspecialchars($errorMessage) ?></p>
					<a href="stores_list.php" class="btn btn-secondary">Tornar</a>
				<?php endif; ?>
			</main>
		</div>
		<?php require_once __DIR__ . '/../includes/footer.php'; ?>
	</body>
</html>
