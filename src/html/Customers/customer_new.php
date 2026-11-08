<?php
	use models\Customer;

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
			$customer = new Customer(
				customer_id: $_POST['customer_id'],
				email_address: $_POST['email_address'],
				first_name: $_POST['first_name'],
				last_name: $_POST['last_name'],
				phone_number: convertToNull($_POST['phone_number']),
				address_line1: convertToNull($_POST['address_line1']),
				city: convertToNull($_POST['city']),
				state_province: convertToNull($_POST['state_province']),
				postal_code: convertToNull($_POST['postal_code']),
				country: convertToNull($_POST['country'])
			);

			$customer->save();
			$status = true;
		}
	} catch (Error | Exception $e) {
		$errorMessage = "El client no es pot afegir: " . $e->getMessage();
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
		<title>Formulari de client</title>
		<link rel="stylesheet" href="<?= $root ?>src/css/estils.css">
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
	</head>
	<body>
		<?php require_once __DIR__ . '/../includes/header.php'; ?>
		<div id="content">
			<?php require_once __DIR__ . '/../includes/nav.php'; ?>
			<main id="section">
				<h3>Afegir un Nou Client</h3>

				<form method="POST" action="">
					<div class="form-group">
						<label for="customer_id">ID client:</label>
						<input class="form-control" type="number" id="customer_id" name="customer_id" required>
					</div>
					<div class="form-group">
						<label for="email_address">Correu electrònic:</label>
						<input class="form-control" type="email" id="email_address" name="email_address" maxlength="255" required>
					</div>
					<div class="form-group">
						<label for="first_name">Nom:</label>
						<input class="form-control" type="text" id="first_name" name="first_name" maxlength="100" required>
					</div>
					<div class="form-group">
						<label for="last_name">Llinatge:</label>
						<input class="form-control" type="text" id="last_name" name="last_name" maxlength="100" required>
					</div>
					<div class="form-group">
						<label for="phone_number">Telèfon:</label>
						<input class="form-control" type="text" id="phone_number" name="phone_number" maxlength="25">
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
					<button class="btn btn-primary" type="submit">Afegir Client</button>

					<a href="customers_list.php" class="btn btn-secondary">Tornar</a>
				</form>

				<?php if ($status): ?>
					<p class="alert alert-success">Client afegit correctament.</p>
				<?php elseif ($errorMessage != null): ?>
					<p class="alert alert-danger"><?= htmlspecialchars($errorMessage) ?></p>
				<?php endif; ?>
			</main>
		</div>
		<?php require_once __DIR__ . '/../includes/footer.php'; ?>
	</body>
</html>
