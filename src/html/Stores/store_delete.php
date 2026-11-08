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
	$status = null;

	try {
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$store = new Store((int) $_POST['store_id']);
			$store->destroy();
			$status = true;
		} else {
			$store = Store::find((int) ($_GET['store_id'] ?? 0));
			if ($store === null) {
				throw new Exception("No s'ha trobat la botiga.");
			}
		}
	} catch (Error | Exception $e) {
		$errorMessage = "La botiga no es pot eliminar: " . $e->getMessage();
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
		<title>Eliminar botiga</title>
		<link rel="stylesheet" href="<?= $root ?>src/css/estils.css">
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
	</head>
	<body>
		<?php require_once __DIR__ . '/../includes/header.php'; ?>
		<div id="content">
			<?php require_once __DIR__ . '/../includes/nav.php'; ?>
			<main id="section">
				<h3>Eliminar botiga</h3>

				<?php if ($status): ?>
					<p class="alert alert-success">Botiga eliminada correctament.</p>
				<?php elseif ($errorMessage != null): ?>
					<p class="alert alert-danger"><?= htmlspecialchars($errorMessage) ?></p>
				<?php else: ?>
					<p class="alert alert-warning">Segur que vols eliminar la botiga <strong><?= htmlspecialchars((string) $store->store_id) ?> - <?= htmlspecialchars((string) $store->store_name) ?></strong>?</p>
					<form method="POST" action="">
						<input type="hidden" name="store_id" value="<?= htmlspecialchars((string) $store->store_id) ?>">
						<button class="btn btn-danger" type="submit">Eliminar</button>
					</form>
				<?php endif; ?>

				<a href="stores_list.php" class="btn btn-secondary">Tornar</a>
			</main>
		</div>
		<?php require_once __DIR__ . '/../includes/footer.php'; ?>
	</body>
</html>
