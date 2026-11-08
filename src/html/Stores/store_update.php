<?php
	use models\Store;

	require_once __DIR__ . '/../../../vendor/autoload.php';

	$root = '../../../';

	function convertToNull($value) {
		return $value === '' ? null : $value;
	}

	function gestorErrors($errno, $errstr, $errfile, $errline) {
		throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
	}

	set_error_handler("gestorErrors");

	$store = null;
	$errorMessage = null;
	$status = null;

	try {
		$store = Store::find((int) ($_GET['store_id'] ?? $_POST['store_id'] ?? 0));
		if ($store === null) {
			throw new Exception("No s'ha trobat la botiga.");
		}

		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$store->store_name = $_POST['store_name'] ?? '';
			$store->web_address = convertToNull($_POST['web_address'] ?? '');
			$store->address_line1 = convertToNull($_POST['address_line1'] ?? '');
			$store->city = convertToNull($_POST['city'] ?? '');
			$store->state_province = convertToNull($_POST['state_province'] ?? '');
			$store->postal_code = convertToNull($_POST['postal_code'] ?? '');
			$store->country = convertToNull($_POST['country'] ?? '');
			$store->latitude = convertToNull($_POST['latitude'] ?? '');
			$store->longitude = convertToNull($_POST['longitude'] ?? '');

			$store->save();
			$status = true;
		}
	} catch (Error | Exception $e) {
		$errorMessage = "La botiga no es pot modificar: " . $e->getMessage();
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
		<title>Modificar botiga</title>
		<link rel="stylesheet" href="<?= $root ?>src/css/estils.css">
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
	</head>
	<body>
		<?php require_once __DIR__ . '/../includes/header.php'; ?>
		<div id="content">
			<?php require_once __DIR__ . '/../includes/nav.php'; ?>
			<main id="section">
				<h3>Modificar botiga</h3>

				<?php if ($store !== null): ?>
					<form method="POST" action="">
						<div class="form-group">
							<label for="store_id">ID botiga:</label>
							<input class="form-control" type="number" id="store_id" name="store_id" value="<?= htmlspecialchars((string) $store->store_id) ?>" readonly required>
						</div>
						<div class="form-group">
							<label for="store_name">Nom:</label>
							<input class="form-control" type="text" id="store_name" name="store_name" value="<?= htmlspecialchars((string) $store->store_name) ?>" maxlength="255" required>
						</div>
						<div class="form-group">
							<label for="web_address">Adreça web:</label>
							<input class="form-control" type="text" id="web_address" name="web_address" value="<?= htmlspecialchars((string) $store->web_address) ?>" maxlength="100">
						</div>
						<div class="form-group">
							<label for="address_line1">Adreça:</label>
							<input class="form-control" type="text" id="address_line1" name="address_line1" value="<?= htmlspecialchars((string) $store->address_line1) ?>" maxlength="255">
						</div>
						<div class="form-group">
							<label for="city">Ciutat:</label>
							<input class="form-control" type="text" id="city" name="city" value="<?= htmlspecialchars((string) $store->city) ?>" maxlength="100">
						</div>
						<div class="form-group">
							<label for="state_province">Província / Estat:</label>
							<input class="form-control" type="text" id="state_province" name="state_province" value="<?= htmlspecialchars((string) $store->state_province) ?>" maxlength="100">
						</div>
						<div class="form-group">
							<label for="postal_code">Codi postal:</label>
							<input class="form-control" type="text" id="postal_code" name="postal_code" value="<?= htmlspecialchars((string) $store->postal_code) ?>" maxlength="20">
						</div>
						<div class="form-group">
							<label for="country">País:</label>
							<input class="form-control" type="text" id="country" name="country" value="<?= htmlspecialchars((string) $store->country) ?>" maxlength="100">
						</div>
						<div class="form-group">
							<label for="latitude">Latitud:</label>
							<input class="form-control" type="number" id="latitude" name="latitude" value="<?= htmlspecialchars((string) $store->latitude) ?>" step="0.0000001">
						</div>
						<div class="form-group">
							<label for="longitude">Longitud:</label>
							<input class="form-control" type="number" id="longitude" name="longitude" value="<?= htmlspecialchars((string) $store->longitude) ?>" step="0.0000001">
						</div>
						<button class="btn btn-primary" type="submit">Modificar botiga</button>
						<a href="stores_list.php" class="btn btn-secondary">Tornar</a>
					</form>
				<?php endif; ?>

				<?php if ($status): ?>
					<p class="alert alert-success">Botiga modificada correctament.</p>
				<?php elseif ($errorMessage != null): ?>
					<p class="alert alert-danger"><?= htmlspecialchars($errorMessage) ?></p>
				<?php endif; ?>
			</main>
		</div>
		<?php require_once __DIR__ . '/../includes/footer.php'; ?>
	</body>
</html>
