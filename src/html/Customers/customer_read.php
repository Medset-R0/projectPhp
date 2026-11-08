<?php
	use models\Customer;

	require_once __DIR__ . '/../../../vendor/autoload.php';

	$root = '../../../';

	function gestorErrors($errno, $errstr, $errfile, $errline) {
		throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
	}

	set_error_handler("gestorErrors");

	$customer = null;
	$errorMessage = null;

	try {
		$customer = Customer::find((int) ($_GET['customer_id'] ?? 0));
		if ($customer === null) {
			throw new Exception("No s'ha trobat el client.");
		}
	} catch (Error | Exception $e) {
		$errorMessage = "Error al carregar el client: " . $e->getMessage();
	} finally {
		restore_error_handler();
	}
?>

<!DOCTYPE html>
<html lang="ca">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Mostrar client</title>
		<link rel="stylesheet" href="<?= $root ?>src/css/estils.css">
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
	</head>
	<body>
		<?php require_once __DIR__ . '/../includes/header.php'; ?>
		<div id="content">
			<?php require_once __DIR__ . '/../includes/nav.php'; ?>
			<main id="section">
				<h3>Dades del client</h3>

				<?php if ($customer !== null): ?>
					<form>
						<div class="form-group">
							<label for="customer_id">ID client:</label>
							<input class="form-control" type="text" id="customer_id" value="<?= htmlspecialchars((string) $customer->customer_id) ?>" readonly>
						</div>
						<div class="form-group">
							<label for="email_address">Correu electrònic:</label>
							<input class="form-control" type="text" id="email_address" value="<?= htmlspecialchars((string) $customer->email_address) ?>" readonly>
						</div>
						<div class="form-group">
							<label for="first_name">Nom:</label>
							<input class="form-control" type="text" id="first_name" value="<?= htmlspecialchars((string) $customer->first_name) ?>" readonly>
						</div>
						<div class="form-group">
							<label for="last_name">Llinatge:</label>
							<input class="form-control" type="text" id="last_name" value="<?= htmlspecialchars((string) $customer->last_name) ?>" readonly>
						</div>
						<div class="form-group">
							<label for="phone_number">Telèfon:</label>
							<input class="form-control" type="text" id="phone_number" value="<?= htmlspecialchars((string) $customer->phone_number) ?>" readonly>
						</div>
						<div class="form-group">
							<label for="address_line1">Adreça:</label>
							<input class="form-control" type="text" id="address_line1" value="<?= htmlspecialchars((string) $customer->address_line1) ?>" readonly>
						</div>
						<div class="form-group">
							<label for="city">Ciutat:</label>
							<input class="form-control" type="text" id="city" value="<?= htmlspecialchars((string) $customer->city) ?>" readonly>
						</div>
						<div class="form-group">
							<label for="state_province">Província / Estat:</label>
							<input class="form-control" type="text" id="state_province" value="<?= htmlspecialchars((string) $customer->state_province) ?>" readonly>
						</div>
						<div class="form-group">
							<label for="postal_code">Codi postal:</label>
							<input class="form-control" type="text" id="postal_code" value="<?= htmlspecialchars((string) $customer->postal_code) ?>" readonly>
						</div>
						<div class="form-group">
							<label for="country">País:</label>
							<input class="form-control" type="text" id="country" value="<?= htmlspecialchars((string) $customer->country) ?>" readonly>
						</div>
						<div class="form-group">
							<label for="created_at">Data de creació:</label>
							<input class="form-control" type="text" id="created_at" value="<?= htmlspecialchars((string) $customer->created_at) ?>" readonly>
						</div>
						<a href="customers_list.php" class="btn btn-secondary">Tornar</a>
					</form>
				<?php else: ?>
					<p class="alert alert-danger"><?= htmlspecialchars($errorMessage) ?></p>
					<a href="customers_list.php" class="btn btn-secondary">Tornar</a>
				<?php endif; ?>
			</main>
		</div>
		<?php require_once __DIR__ . '/../includes/footer.php'; ?>
	</body>
</html>
