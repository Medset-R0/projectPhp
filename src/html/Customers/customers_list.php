<?php
	use models\Customer;

	require_once __DIR__ . '/../../../vendor/autoload.php';

	$root = '../../../';

	$errorMessage = null;
	$rows = [];

	try {
		$rows = Customer::all();
	} catch (Error | Exception $e) {
		$errorMessage = "No es poden carregar els registres: " . $e->getMessage();
	}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Human Resource</title>
		<link rel="stylesheet" href="<?= $root ?>src/css/estils.css">
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
		<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
		<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
		<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
		<script>
			$(document).ready(function () {
				$('[data-toggle="tooltip"]').tooltip();
			});
		</script>
	</head>
	<body>
		<?php require_once __DIR__ . '/../includes/header.php'; ?>
		<div id="content">
			<?php require_once __DIR__ . '/../includes/nav.php'; ?>
			<main id="section">
				<h3>Customers</h3>
				<a href="customer_new.php" class="btn btn-primary">
					<span class="fa fa-plus" aria-hidden="true"></span> New Customer
				</a>

				<?php if ($errorMessage != null): ?>
					<p class="alert alert-danger"><?= htmlspecialchars($errorMessage) ?></p>
				<?php endif; ?>

				<table class="table table-bordered table-striped">
					<thead>
						<tr>
							<th>#</th>
							<th>Last Name</th>
							<th>First Name</th>
							<th>Email</th>
							<th>Actions</th>
						</tr>
					</thead>
					<tbody>
						<?php
							foreach ($rows as $row) {
								echo
									"<tr>" .
										"<td>" . htmlspecialchars((string) $row->customer_id) . "</td>" .
										"<td>" . htmlspecialchars((string) $row->last_name) . "</td>" .
										"<td>" . htmlspecialchars((string) $row->first_name) . "</td>" .
										"<td>" . htmlspecialchars((string) $row->email_address) . "</td>" .
										"<td>" .
											'<a href="customer_read.php?customer_id='   . urlencode((string) $row->customer_id) . '" class="mr-2" title="View File" data-toggle="tooltip"><span class="fa fa-eye"></span></a>' .
											'<a href="customer_update.php?customer_id=' . urlencode((string) $row->customer_id) . '" class="mr-2" title="Update File" data-toggle="tooltip"><span class="fa fa-pencil"></span></a>' .
											'<a href="customer_delete.php?customer_id=' . urlencode((string) $row->customer_id) . '" class="mr-2" title="Delete File" data-toggle="tooltip"><span class="fa fa-trash"></span></a>' .
										"</td>" .
									"</tr>";
							}
						?>
					</tbody>
				</table>
			</main>
		</div>

		<?php require_once __DIR__ . '/../includes/footer.php'; ?>
	</body>
</html>
