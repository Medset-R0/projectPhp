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
	$status = null;

	try {
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$customer = new Customer((int) $_POST['customer_id']);
			$customer->destroy();
			$status = true;
		} else {
			$customer = Customer::find((int) ($_GET['customer_id'] ?? 0));
			if ($customer === null) {
				throw new Exception("No s'ha trobat el client.");
			}
		}
	} catch (Error | Exception $e) {
		$errorMessage = "El client no es pot eliminar: " . $e->getMessage();
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
		<title>Eliminar client</title>
		<link rel="stylesheet" href="<?= $root ?>src/css/estils.css">
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
	</head>
	<body>
		<?php require_once __DIR__ . '/../includes/header.php'; ?>
		<div id="content">
			<?php require_once __DIR__ . '/../includes/nav.php'; ?>
			<main id="section">
				<h3>Eliminar client</h3>

				<?php if ($status): ?>
					<p class="alert alert-success">Client eliminat correctament.</p>
				<?php elseif ($errorMessage != null): ?>
					<p class="alert alert-danger"><?= htmlspecialchars($errorMessage) ?></p>
				<?php else: ?>
					<p class="alert alert-warning">Segur que vols eliminar el client <strong><?= htmlspecialchars((string) $customer->customer_id) ?> - <?= htmlspecialchars((string) $customer->last_name) ?></strong>?</p>
					<form method="POST" action="">
						<input type="hidden" name="customer_id" value="<?= htmlspecialchars((string) $customer->customer_id) ?>">
						<button class="btn btn-danger" type="submit">Eliminar</button>
					</form>
				<?php endif; ?>

				<a href="customers_list.php" class="btn btn-secondary">Tornar</a>
			</main>
		</div>
		<?php require_once __DIR__ . '/../includes/footer.php'; ?>
	</body>
</html>
