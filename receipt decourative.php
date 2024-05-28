<?php
include 'db_connect.php';

// Fetch system settings to get the uploaded image path, email, and contact number
$qry = $conn->query("SELECT * FROM system_settings LIMIT 1");
$meta = $qry->fetch_array();

// Set the uploaded image path
$image_path = isset($meta['cover_img']) ? 'assets/uploads/' . $meta['cover_img'] : '';

// Fetch order details
$order = $conn->query("SELECT * FROM orders WHERE id = {$_GET['id']}");
foreach ($order->fetch_array() as $k => $v) {
	$$k = $v;
}

// Fetch order items
$items = $conn->query("SELECT o.*, p.name FROM order_items o INNER JOIN products p ON p.id = o.product_id WHERE o.order_id = $id ");

// Determine the payment method text based on the value
$payment_method_text = '';
if ($payment_method == '1') {
	$payment_method_text = 'Cash';
} elseif ($payment_method == '2') {
	$payment_method_text = 'Online';
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Receipt</title>
	<style>
		.container-fluid {
			position: relative;
			padding-left: 80px;
			/* margin-left: 100px; */
			/* Adjust as needed */
		}

		.vertical-text {
			position: absolute;
			top: 0;
			bottom: 0;
			width: 30px;
			overflow: hidden;
			color: #000;
			font-weight: bold;
			font-size: 12px;
			writing-mode: vertical-rl;
			text-orientation: mixed;
			white-space: nowrap;
		}

		.left-side {
			left: -30px;
		}

		.right-side {
			right: 5px;
		}

		.flex {
			display: inline-flex;
			width: 100%;
		}

		.w-50 {
			width: 50%;
		}

		.text-center {
			text-align: center;
		}

		.text-right {
			text-align: right;
		}

		table.wborder {
			width: 100%;
			border-collapse: collapse;
		}

		table.wborder>tbody>tr,
		table.wborder>tbody>tr>td {
			border: 1px solid;
		}

		p {
			margin: unset;
		}

		.table-hr {
			border-top: 1px solid black;
		}

		@media print {

			body,
			html {
				margin: 0;
				margin-left: 10px;
				padding: 0;
				width: 80mm;
			}

			.container-fluid {
				font-size: 12px;
				margin: 0;
				padding: 0;
				position: relative;
			}

			img {
				display: block;
				margin-left: auto;
				margin-right: auto;
				max-width: 50%;
			}

			table.wborder {
				width: 100%;
				border-collapse: collapse;
			}

			table.wborder>tbody>tr,
			table.wborder>tbody>tr>td {
				border: 1px solid;
			}

			p {
				margin: unset;
			}

			.table-hr {
				border-top: 1px solid black;
			}
		}
	</style>
</head>

<body>
	<div class="container-fluid">
		<div class="vertical-text left-side">Juice & Jug Juice & Jug Juice & Jug Juice & Jug Juice & Jug Juice & Jug Juice & Jug Juice & Jug Juice & Jug Juice & Jug Juice & Jug Juice & Jug</div>
		<div class="vertical-text right-side">Juice & Jug Juice & Jug Juice & Jug Juice & Jug Juice & Jug Juice & Jug Juice & Jug Juice & Jug Juice & Jug Juice & Jug Juice & Jug Juice & Jug</div>
		<img src="<?php echo $image_path; ?>">
		<div class="text-center">
			<p>Junction Main Rd, opposite to Sona college,<br> Subramania Nagar, Suramangalam, Salem,<br> Tamil Nadu 636005</p>
			<!-- <p>Email: <b><?php #echo $meta['email']; 
								?></b></p>
			<p>Contact: <b><?php #echo $meta['contact']; 
							?></b></p> -->
		</div>
		<p class="text-center"><b><?php echo $amount_tendered > 0 ? "Receipt" : "Bill" ?></b></p>
		<h3 class="text-center"><b>Order: <?php echo $order_number ?></b></h3>
		<hr>
		<div class="flex">
			<div class="w-70">
				<?php if ($amount_tendered > 0) : ?>
					<p>Invoice Number: <b><?php echo $ref_no ?></b></p>
				<?php endif; ?>
				<p>Date: <b><?php echo date("d-m-Y", strtotime($date_created)) ?> <?php echo date("h:i A") ?></b></p> <!-- Current Time in 12-hour format with AM/PM -->
			</div>
		</div>
		<hr class="table-hr">
		<table width="100%">
			<thead>
				<tr>
					<td><b>Order</b></td>
					<td class="text-right"><b>Qty</b></td>
					<td class="text-right"><b>Amount</b></td>
				</tr>
				<tr>
					<td colspan="3">
						<hr class="table-hr">
					</td>
				</tr>
			</thead>

			<tbody>
				<?php while ($row = $items->fetch_assoc()) : ?>
					<tr>
						<td>
							<p>
								<?php echo $row['name'] ?>
							</p>
							<?php if ($row['qty'] > 0) : ?>
								<!-- <small>(<?php #echo number_format($row['price'], 2) 
												?>)
								</small> -->
							<?php endif; ?>
						</td>
						<td class="text-right"><?php echo $row['qty'] ?></td>
						<td class="text-right">&#8377; <?php echo number_format($row['amount'], 2) ?></td>

					</tr>
				<?php endwhile; ?>
			</tbody>
		</table>
		<hr class="table-hr">
		<table width="100%">
			<tbody>
				<tr>
					<td><b>Grand Total</b></td>
					<td class="text-right"><b>&#8377;<?php echo number_format($total_amount, 2) ?></b></td>
				</tr>

				<!-- Conditional Display for Discount Row -->
				<?php if ($discount != 0) : ?>
					<tr>
						<td><b>Discount</b></td>
						<td class="text-right"><b><?php echo number_format($discount * 1, 0) ?>%</b></td>
					</tr>

					<!-- Add After Discount Row -->
					<tr>
						<td><b>After Discount</b></td>
						<td class="text-right"><b>&#8377;<?php echo number_format($after_discount, 2) ?></b></td>
					</tr>
				<?php endif; ?>

				<?php if ($amount_tendered > 0) : ?>
					<tr>
						<td><b>Amount Received</b></td>
						<td class="text-right"><b>&#8377;<?php echo number_format($amount_tendered, 2) ?></b></td>
					</tr>
					<tr>
						<td><b>Balance</b></td>
						<td class="text-right"><b>&#8377;<?php echo number_format($amount_tendered - $after_discount, 2) ?></b></td>
					</tr>

					<tr>
						<td><b>Payment Method</b></td>
						<td class="text-right"><b><?php echo $payment_method_text; ?></b></td>
					</tr>
				<?php endif; ?>
			</tbody>
		</table>
		<?php if (!empty($meta['about_content'])) : ?>
			<div class="text-center">
				<b><?php echo html_entity_decode($meta['about_content']); ?></b>
			</div>
		<?php endif; ?>
	</div>
</body>

</html>