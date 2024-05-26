<?php include('db_connect.php'); ?>
<style>
	input[type=checkbox] {
		/* Double-sized Checkboxes */
		-ms-transform: scale(1.3);
		/* IE */
		-moz-transform: scale(1.3);
		/* FF */
		-webkit-transform: scale(1.3);
		/* Safari and Chrome */
		-o-transform: scale(1.3);
		/* Opera */
		transform: scale(1.3);
		padding: 10px;
		cursor: pointer;
	}

	.card {
		background-color: #1F1D2B;
		box-shadow: 0 5px 10px rgba(203, 219, 175, 0.1);
	}

	.card-header {
		color: whitesmoke;
	}

	thead tr th {
		background-color: #FED108;
		color: black;
	}

	tbody td,
	tfoot {
		background-color: #252836;
	}

	th {
		color: white;
	}

	td {
		text-align: center !important;
		color: white;
	}

	img {
		max-width: 100px;
		max-height: 150px;
	}

	img {
		max-width: 100px;
		max-height: 150px;
	}
</style>
<div class="container-fluid">
	<div class="col-lg-12" style="margin-left:-100px">
		<div class="row mb-2 mt-3">
			<div class="col-md-12"></div>
		</div>
		<div class="row">
			<!-- FORM Panel -->

			<!-- Table Panel -->
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<b>List of Orders </b>
					</div>
					<div class="card-body">
						<table class="table table-condensed table-bordered table-striped table-hover">
							<thead>
								<tr>
									<th class="text-center">S.no.</th>
									<th class="text-center">Date</th>
									<th class="text-center">Invoice</th>
									<th class="text-center">Order Number</th>
									<th class="text-center">Amount</th>
									<th class="text-center">Status</th>
									<th class="text-center">Action</th>
								</tr>
							</thead>
							<tbody>
								<?php
								$i = 1;
								if ($_SESSION['login_type'] == 1) :
									$order = $conn->query("SELECT * FROM orders order by unix_timestamp(date_created) desc ");
								else :
									$currentYear = date('Y');
									$currentMonth = date('m');
									$order = $conn->query("SELECT * FROM orders WHERE YEAR(date_created) = '$currentYear' AND MONTH(date_created) = '$currentMonth' ORDER BY unix_timestamp(date_created) DESC");
								endif;
								while ($row = $order->fetch_assoc()) :
								?>
									<tr>
										<td class="text-center"><?php echo $i++ ?></td>
										<td>
											<p><b><?php echo date("M d,Y", strtotime($row['date_created'])) ?></b></p>
										</td>
										<td>
											<p><b><?php echo $row['amount_tendered'] > 0 ? $row['ref_no'] : 'N/A' ?></b></p>
										</td>
										<td>
											<p><b><?php echo $row['order_number'] ?></b></p>
										</td>
										<td>
											<p class="text-right"><b><?php echo number_format($row['total_amount'], 2) ?></b></p>
										</td>
										<td class="text-center">
											<?php if ($row['amount_tendered'] > 0) : ?>
												<span class="badge badge-success">Paid</span>
											<?php else : ?>
												<span class="badge badge-primary">Unpaid</span>
											<?php endif; ?>
										</td>
										<td class="text-center">
											<button class="btn btn-sm bg-primary text-white" type="button" onclick="location.href='billing/index.php?id=<?php echo $row['id'] ?>'" data-id="<?php echo $row['id'] ?>">
												<i class="bi bi-pencil-fill" style="color: white;"></i> Edit
											</button>
											<button class="btn btn-sm bg-success text-white view_order" type="button" data-id="<?php echo $row['id'] ?>">
												<i class="bi bi-eye-fill" style="color: white;"></i> View
											</button>
											<button class="btn btn-sm bg-danger text-white delete_order" type="button" data-id="<?php echo $row['id'] ?>">
												<i class="bi bi-trash3-fill" style="color: white;"></i> Delete
											</button>
										</td>
									</tr>
								<?php endwhile; ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<!-- Table Panel -->
		</div>
	</div>
</div>
<script>
	$(document).ready(function() {
		$('table').dataTable()
	})
	$('#new_order').click(function() {
		uni_modal("New order ", "manage_order.php", "mid-large")
	})
	$('.view_order').click(function() {
		uni_modal("Order  Details", "view_order.php?id=" + $(this).attr('data-id'), "mid-large")
	})
	$('.delete_order').click(function() {
		_conf("Are you sure to delete this order ?", "delete_order", [$(this).attr('data-id')])
	})

	function delete_order($id) {
		start_load()
		$.ajax({
			url: 'ajax.php?action=delete_order',
			method: 'POST',
			data: {
				id: $id
			},
			success: function(resp) {
				if (resp == 1) {
					alert_toast("Data successfully deleted", 'success')
					setTimeout(function() {
						location.reload()
					}, 1500)
				}
			}
		})
	}
</script>